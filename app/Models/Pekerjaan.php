<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'pekerjaan';

    /**
     * Atribut yang dapat diisi massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'alumni_id',
        'nama_perusahaan',
        'jabatan',
        'tahun_mulai',
        'tahun_selesai',
        'alamat_perusahaan'
    ];

    /**
     * Relasi ke model Alumni (Many-to-One).
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
