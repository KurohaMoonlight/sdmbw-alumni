<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'must_change_password',
    ];

    /**
     * Atribut yang disembunyikan saat serialize
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type casting untuk attributes
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Antar Tabel
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi: User punya 1 profil alumni (jika role = alumni)
     *
     * @return HasOne
     */
    public function alumni(): HasOne
    {
        return $this->hasOne(Alumni::class, 'user_id');
    }

    /**
     * Relasi: User (admin) punya banyak activity logs
     *
     * @return HasMany
     */
    public function adminLogs(): HasMany
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods (Logika Bisnis)
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah user adalah admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah kepala sekolah
     *
     * @return bool
     */
    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    /**
     * Cek apakah user adalah alumni
     *
     * @return bool
     */
    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    /**
     * Cek apakah akun sudah diaktifkan/diverifikasi
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Update waktu login terakhir
     *
     * @return void
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter user dengan role admin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: Filter user dengan role alumni
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni');
    }

    /**
     * Scope: Filter user yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
