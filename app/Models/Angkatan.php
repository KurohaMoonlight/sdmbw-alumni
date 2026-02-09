<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';

    /**
     * Atribut yang dapat diisi massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_angkatan',
        'tahun_ajaran',
        'status',
    ];

    /**
     * Type casting untuk attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_ajaran' => 'string',
    ];

    /**
     * Boot method untuk menambahkan default ordering
     */
    protected static function boot()
    {
        parent::boot();

        // Default ordering berdasarkan angka di nama_angkatan
        static::addGlobalScope('order', function ($query) {
            $query->orderByRaw('CAST(SUBSTRING(nama_angkatan, 10) AS UNSIGNED) ASC');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Antar Tabel
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi: Satu angkatan punya banyak alumni
     *
     * @return HasMany
     */
    public function alumni(): HasMany
    {
        return $this->hasMany(Alumni::class, 'angkatan_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter angkatan dengan status LULUS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLulus($query)
    {
        return $query->where('status', 'LULUS');
    }

    /**
     * Scope: Filter angkatan dengan status AKTIF
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'AKTIF');
    }

    /**
     * Scope: Filter angkatan dengan status BELUM_LULUS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBelumLulus($query)
    {
        return $query->where('status', 'BELUM_LULUS');
    }
}
