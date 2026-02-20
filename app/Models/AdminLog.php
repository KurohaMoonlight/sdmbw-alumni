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
    | Konstanta Action
    | Digunakan di AlumniController agar tidak ada typo string action
    |--------------------------------------------------------------------------
    */
    const ACTION_VERIFY_ALUMNI      = 'verify_alumni';
    const ACTION_REJECT_ALUMNI      = 'reject_alumni';
    const ACTION_PENDING_ALUMNI     = 'pending_alumni';
    const ACTION_UPDATE_ALUMNI      = 'update_alumni';
    const ACTION_DELETE_ALUMNI      = 'delete_alumni';
    const ACTION_RESET_PASSWORD     = 'reset_password';
    const ACTION_RESET_PASSWORD_NISN = 'reset_password_nisn';

    /*
    |--------------------------------------------------------------------------
    | Label yang ditampilkan di halaman Activity Log
    | Tambahkan action baru di sini jika nanti ada fitur baru
    |--------------------------------------------------------------------------
    */
    const ACTION_LABELS = [
        'verify_alumni'       => 'Verifikasi Alumni',
        'reject_alumni'       => 'Tolak Alumni',        // ← action baru
        'pending_alumni'      => 'Kembalikan ke Pending',
        'update_alumni'       => 'Edit Data Alumni',
        'delete_alumni'       => 'Hapus Alumni',
        'reset_password'      => 'Reset Password',
        'reset_password_nisn' => 'Reset Password (NISN)',
        'update_status_alumni'=> 'Ubah Status Alumni',
    ];

    /*
    |--------------------------------------------------------------------------
    | Warna badge per action (untuk tampilan di blade)
    |--------------------------------------------------------------------------
    */
    const ACTION_BADGES = [
        'verify_alumni'       => 'success',
        'reject_alumni'       => 'danger',
        'pending_alumni'      => 'secondary',
        'update_alumni'       => 'primary',
        'delete_alumni'       => 'dark',
        'reset_password'      => 'warning',
        'reset_password_nisn' => 'warning',
        'update_status_alumni'=> 'info',
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
    | Accessor / Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Dapatkan label action yang human-readable
     * Dipakai di blade: {{ $log->action_label }}
     *
     * @return string
     */
    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? ucwords(str_replace('_', ' ', $this->action));
    }

    /**
     * Dapatkan warna badge Bootstrap untuk action ini
     * Dipakai di blade: badge bg-{{ $log->action_badge }}
     *
     * @return string
     */
    public function getActionBadgeAttribute(): string
    {
        return self::ACTION_BADGES[$this->action] ?? 'secondary';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes (untuk filter di halaman Activity Log)
    |--------------------------------------------------------------------------
    */

    /**
     * Filter log berdasarkan action
     *
     * Contoh: AdminLog::byAction('reject_alumni')->get()
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Filter log berdasarkan admin_id
     */
    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Filter log berdasarkan target_type
     */
    public function scopeByTarget($query, string $targetType)
    {
        return $query->where('target_type', $targetType);
    }

    /**
     * Filter log berdasarkan rentang tanggal
     */
    public function scopeInDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Fungsi statis untuk mencatat log dengan cepat
     *
     * @param int         $adminId
     * @param string      $action      Gunakan konstanta ACTION_* di atas
     * @param string      $targetType
     * @param int|null    $targetId
     * @param string      $description
     * @return static
     */
    public static function log($adminId, $action, $targetType, $targetId, $description)
    {
        return self::create([
            'admin_id'    => $adminId,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'description' => $description,
        ]);
    }
}
