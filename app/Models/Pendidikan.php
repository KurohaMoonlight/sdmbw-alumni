<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikan';

    /**
     * Atribut yang dapat diisi massal.
     * Pastikan kolom-kolom ini sudah ada di database melalui migrasi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumni_id',
        'nama_instansi',
        'jenjang',
        'program_studi',
        'tahun_masuk',
        'tahun_lulus',
        'is_ongoing',
    ];

    /**
     * Cast is_ongoing ke boolean agar mudah digunakan di Blade.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_ongoing' => 'boolean',
    ];

    /**
     * Relasi ke model Alumni
     *
     * @return BelongsTo
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
