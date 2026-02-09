<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlumniPendidikan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'alumni_pendidikan';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
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
        'catatan',
    ];

    /**
     * Casting atribut ke tipe data tertentu.
     * is_ongoing: true = Masih Belajar, false = Sudah Lulus
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_ongoing' => 'boolean',
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer',
    ];

    /**
     * Relasi ke model Alumni.
     * Satu data pendidikan dimiliki oleh satu alumni.
     *
     * @return BelongsTo
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
