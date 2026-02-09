<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlumniFoto extends Model
{
    use HasFactory;

    protected $table = 'alumni_fotos';

    /**
     * Atribut yang dapat diisi massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumni_id',
        'path_file',
        'kategori',
        'deskripsi',
        'is_main',
    ];

    /**
     * Type casting untuk attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_main' => 'boolean',
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
