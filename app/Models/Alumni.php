<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alumni';

    /**
     * Atribut yang dapat diisi massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nisn',
        'nama_lengkap',
        'angkatan_id',
        'tahun_lulus',
        'alamat',
        'no_hp',
        'show_no_hp',
        'email',
        'harapan',
        'status_verifikasi',
        'is_profile_complete',
    ];

    /**
     * Type casting untuk attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_lulus' => 'integer',
        'show_no_hp' => 'boolean',
        'is_profile_complete' => 'boolean',
    ];

    /**
     * Default values untuk attributes
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status_verifikasi' => 'pending',
        'is_profile_complete' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Antar Tabel
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke User (Sumber utama email login)
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Angkatan
     *
     * @return BelongsTo
     */
    public function angkatan(): BelongsTo
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    /**
     * Relasi ke Pendidikan Alumni
     *
     * @return HasMany
     */
    public function pendidikan(): HasMany
    {
        return $this->hasMany(AlumniPendidikan::class, 'alumni_id');
    }

    /**
     * Relasi ke Pekerjaan Alumni
     *
     * @return HasMany
     */
    public function pekerjaan(): HasMany
    {
        return $this->hasMany(AlumniPekerjaan::class, 'alumni_id');
    }

    /**
     * Relasi ke Foto Alumni
     *
     * @return HasMany
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(AlumniFoto::class, 'alumni_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope untuk filter alumni yang terverifikasi
     * Pastikan string 'verified' sesuai dengan nilai di database Anda
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    /**
     * Scope untuk filter alumni dengan status pending
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    /**
     * Scope untuk filter alumni yang ditolak
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status_verifikasi', 'rejected');
    }

    /**
     * Scope untuk filter alumni dengan profil lengkap
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProfileComplete($query)
    {
        return $query->where('is_profile_complete', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek kelengkapan profil alumni
     * Alumni dianggap lengkap jika memiliki:
     * - Alamat dan No HP yang tidak kosong
     * - Minimal 2 jenjang pendidikan wajib (SMP/MTS dan SMA/MA/SMK)
     *
     * @return bool
     */
    public function isDataComplete(): bool
    {
        // Validasi data kontak dasar
        $hasBasicInfo = !empty(trim($this->alamat ?? ''))
            && !empty(trim($this->no_hp ?? ''));

        // Menghitung jenjang pendidikan wajib yang sudah diisi
        $requiredEducationCount = $this->pendidikan()
            ->whereIn('jenjang', ['SMP/MTS', 'SMA/MA/SMK'])
            ->select('jenjang')
            ->distinct()
            ->count();

        return $hasBasicInfo && ($requiredEducationCount >= 2);
    }
}
