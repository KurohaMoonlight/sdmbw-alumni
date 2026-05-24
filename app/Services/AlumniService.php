<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\AdminLog;
use App\Models\User;
use App\Enums\AlumniStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Admin\LaporanController;
use Exception;

class AlumniService
{
    /**
     * Memperbarui informasi dasar Alumni.
     */
    public function updateAlumni(Alumni $alumni, array $data, int $adminId): Alumni
    {
        DB::beginTransaction();
        try {
            $alumni->update($data);

            AdminLog::log(
                $adminId,
                AdminLog::ACTION_UPDATE_ALUMNI,
                'alumni',
                $alumni->id,
                "Mengupdate data alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn})"
            );

            DB::commit();
            $this->clearDashboardCache();
            
            return $alumni;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Memverifikasi, Menolak, atau Mengembalikan status pendaftaran Alumni.
     */
    public function verifyAlumni(Alumni $alumni, string $status, int $adminId): string
    {
        DB::beginTransaction();
        try {
            $alumni->update(['status_verifikasi' => $status]);

            if ($alumni->user) {
                $alumni->user->update([
                    'is_active' => ($status === AlumniStatus::VERIFIED->value) ? 1 : 0,
                ]);
            }

            $action = match($status) {
                AlumniStatus::VERIFIED->value => AdminLog::ACTION_VERIFY_ALUMNI,
                AlumniStatus::REJECTED->value => AdminLog::ACTION_REJECT_ALUMNI,
                AlumniStatus::PENDING->value  => AdminLog::ACTION_PENDING_ALUMNI,
                default => AdminLog::ACTION_UPDATE_ALUMNI,
            };

            $description = match($status) {
                AlumniStatus::VERIFIED->value => "Memverifikasi alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn}, Angkatan: {$alumni->angkatan?->nama_angkatan}). Akun diaktifkan.",
                AlumniStatus::REJECTED->value => "Menolak pendaftaran alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn}, Angkatan: {$alumni->angkatan?->nama_angkatan}). Akun dinonaktifkan.",
                AlumniStatus::PENDING->value  => "Mengubah status {$alumni->nama_lengkap} (NISN: {$alumni->nisn}) kembali ke Pending.",
                default => "Mengubah status verifikasi {$alumni->nama_lengkap} ke {$status}.",
            };

            AdminLog::log($adminId, $action, 'alumni', $alumni->id, $description);

            DB::commit();
            $this->clearDashboardCache();

            return match($status) {
                AlumniStatus::VERIFIED->value => 'Alumni berhasil diverifikasi dan akun diaktifkan.',
                AlumniStatus::REJECTED->value => 'Pendaftaran alumni berhasil ditolak dan akun dinonaktifkan.',
                default => 'Status verifikasi berhasil diperbarui.',
            };
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mereset password user menjadi string acak secara otomatis.
     */
    public function resetPassword(Alumni $alumni, int $adminId): string
    {
        DB::beginTransaction();
        try {
            if (!$alumni->user) {
                throw new Exception('Akun user tidak ditemukan.');
            }

            $newPassword = Str::random(8);

            $alumni->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true,
            ]);

            AdminLog::log(
                $adminId,
                AdminLog::ACTION_RESET_PASSWORD,
                'alumni',
                $alumni->id,
                "Reset password alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn}) menjadi password acak"
            );

            DB::commit();
            $this->clearDashboardCache();
            
            return $newPassword;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mereset password user berdasarkan NISN dengan password spesifik yang diberikan.
     */
    public function resetPasswordByNisn(string $nisn, string $newPassword, int $adminId): Alumni
    {
        DB::beginTransaction();
        try {
            $alumni = Alumni::where('nisn', $nisn)->firstOrFail();

            if (!$alumni->user) {
                throw new Exception('Akun user tidak ditemukan untuk alumni ini.');
            }

            $alumni->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true,
            ]);

            AdminLog::log(
                $adminId,
                AdminLog::ACTION_RESET_PASSWORD_NISN,
                'alumni',
                $alumni->id,
                "Reset password alumni (by NISN): {$alumni->nama_lengkap} ({$nisn})"
            );

            DB::commit();
            $this->clearDashboardCache();
            
            return $alumni;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Menghapus satu data Alumni secara permanen beserta seluruh data relasinya.
     */
    public function deleteAlumni(Alumni $alumni, int $adminId): void
    {
        $namaAlumni = $alumni->nama_lengkap;
        $nisnAlumni = $alumni->nisn;

        DB::beginTransaction();
        try {
            $alumni->pendidikan()->delete();
            $alumni->pekerjaan()->delete();
            $alumni->fotos()->delete();

            if ($alumni->user_id) {
                User::where('id', $alumni->user_id)->forceDelete();
            }

            $alumni->forceDelete();

            AdminLog::log(
                $adminId,
                AdminLog::ACTION_DELETE_ALUMNI,
                'alumni',
                null,
                "Menghapus permanen data alumni: {$namaAlumni} (NISN: {$nisnAlumni})"
            );

            DB::commit();
            $this->clearDashboardCache();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Menghapus seluruh data alumni dari sistem secara massal (Bulk Delete).
     */
    public function deleteAllAlumni(int $adminId): void
    {
        DB::beginTransaction();
        try {
            DB::table('alumni_pendidikan')->delete();
            DB::table('alumni_pekerjaan')->delete();
            DB::table('alumni_fotos')->delete();
            
            User::where('role', UserRole::ALUMNI->value)->forceDelete();
            Alumni::query()->forceDelete();

            AdminLog::log(
                $adminId,
                AdminLog::ACTION_DELETE_ALL_ALUMNI,
                'alumni',
                null,
                "MENGHAPUS SELURUH DATA ALUMNI DAN AKUN TERKAIT DARI SISTEM SECARA PERMANEN."
            );

            DB::commit();
            $this->clearDashboardCache();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Membersihkan seluruh cache dashboard admin dan kepala sekolah.
     */
    public function clearDashboardCache(): void
    {
        Cache::forget('admin_dashboard_stats');
        Cache::forget('admin_dashboard_recent_alumni');
        Cache::forget('admin_dashboard_angkatan_stats');
        Cache::forget('admin_dashboard_recent_updates');
        
        Cache::forget('kepala_sekolah_dashboard_stats');
        Cache::forget('kepala_sekolah_angkatan_stats');
        Cache::forget('kepala_sekolah_recent_alumni');
        
        LaporanController::clearLaporanCache();
        Cache::forget('landing_stats');
    }
}
