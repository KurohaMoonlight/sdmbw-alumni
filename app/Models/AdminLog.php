<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    /**
     * Atribut yang dapat diisi massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'description',
    ];

    /**
     * Type casting untuk attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Antar Tabel
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke User (Admin yang melakukan aksi)
     *
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Fungsi statis untuk mencatat log dengan cepat
     *
     * @param int $adminId
     * @param string $action
     * @param string $targetType
     * @param int|null $targetId
     * @param string $description
     * @return static
     */
    public static function log($adminId, $action, $targetType, $targetId, $description)
    {
        return self::create([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
        ]);
    }
}
