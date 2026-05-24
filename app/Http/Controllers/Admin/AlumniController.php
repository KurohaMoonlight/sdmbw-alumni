<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Angkatan;
use App\Models\AdminLog;
use App\Models\User;
use App\Http\Requests\UpdateAdminAlumniRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AlumniController extends Controller
{
    /**
     * Tampilkan daftar alumni dengan filter lengkap
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Hanya memuat relasi yang benar-benar ditampilkan di tabel
        // untuk menghemat konsumsi RAM server (menghindari N+1 Over-fetching)
        $query = Alumni::with(['user', 'angkatan']);

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->angkatan_id);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        if ($request->filled('complete')) {
            $query->where('is_profile_complete', $request->complete === '1');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $alumnis   = $query->latest()->paginate(20)->withQueryString();
        $angkatans = Angkatan::get();

        return view('admin.alumni.index', compact('alumnis', 'angkatans'));
    }

    public function show(Alumni $alumni)
    {
        $alumni->load(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        $alumni->load(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);
        $angkatans = Angkatan::get();
        return view('admin.alumni.edit', compact('alumni', 'angkatans'));
    }

    public function update(UpdateAdminAlumniRequest $request, Alumni $alumni)
    {
        // Validasi ditangani oleh UpdateAdminAlumniRequest
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $alumni->update($validated);

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_UPDATE_ALUMNI,
                'alumni',
                $alumni->id,
                "Mengupdate data alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn})"
            );

            DB::commit();
            $this->clearDashboardCache();
            return redirect()->route('admin.alumni.show', $alumni)
                ->with('success', 'Data alumni berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Verifikasi / Tolak / Pending status alumni
     *
     * FIX BUG 2:
     * - Action log sekarang terpisah per status menggunakan konstanta AdminLog::ACTION_*
     *   sehingga activity log menampilkan label berbeda untuk setiap aksi.
     * - 'verified' → AdminLog::ACTION_VERIFY_ALUMNI ('verify_alumni')  → label "Verifikasi Alumni"
     * - 'rejected' → AdminLog::ACTION_REJECT_ALUMNI ('reject_alumni')  → label "Tolak Alumni"  ← FIX
     * - 'pending'  → AdminLog::ACTION_PENDING_ALUMNI ('pending_alumni') → label "Kembalikan ke Pending"
     */
    public function verify(Request $request, Alumni $alumni)
    {
        $request->validate([
            'status' => 'required|in:verified,pending,rejected',
        ]);

        $status = $request->input('status');

        DB::beginTransaction();
        try {
            $alumni->update(['status_verifikasi' => $status]);

            if ($alumni->user) {
                $alumni->user->update([
                    'is_active' => ($status === 'verified') ? 1 : 0,
                ]);
            }

            // Gunakan konstanta ACTION_* dari AdminLog — tidak ada string literal
            $action = match($status) {
                'verified' => AdminLog::ACTION_VERIFY_ALUMNI,
                'rejected' => AdminLog::ACTION_REJECT_ALUMNI,
                'pending'  => AdminLog::ACTION_PENDING_ALUMNI,
                default    => AdminLog::ACTION_UPDATE_ALUMNI,
            };

            $description = match($status) {
                'verified' => "Memverifikasi alumni: {$alumni->nama_lengkap} "
                            . "(NISN: {$alumni->nisn}, Angkatan: {$alumni->angkatan?->nama_angkatan}). "
                            . "Akun diaktifkan.",
                'rejected' => "Menolak pendaftaran alumni: {$alumni->nama_lengkap} "
                            . "(NISN: {$alumni->nisn}, Angkatan: {$alumni->angkatan?->nama_angkatan}). "
                            . "Akun dinonaktifkan.",
                'pending'  => "Mengubah status {$alumni->nama_lengkap} (NISN: {$alumni->nisn}) "
                            . "kembali ke Pending.",
                default    => "Mengubah status verifikasi {$alumni->nama_lengkap} ke {$status}.",
            };

            AdminLog::log(Auth::id(), $action, 'alumni', $alumni->id, $description);

            // Fitur pengiriman Email Notifikasi (Dinonaktifkan)
            // Mengingat server belum memiliki konfigurasi SMTP, proses verifikasi 
            // sekarang murni mengubah status di sistem (in-app logic) tanpa mengirim email
            // agar tidak terjadi timeout atau error.

            DB::commit();
            $this->clearDashboardCache();

            $message = match($status) {
                'verified' => 'Alumni berhasil diverifikasi dan akun diaktifkan.',
                'rejected' => 'Pendaftaran alumni berhasil ditolak dan akun dinonaktifkan.',
                default    => 'Status verifikasi berhasil diperbarui.',
            };

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }

    public function resetPassword(Alumni $alumni)
    {
        DB::beginTransaction();
        try {
            if (!$alumni->user) {
                return back()->with('error', 'Akun user tidak ditemukan.');
            }

            // Generate password acak 8 karakter
            $newPassword = \Illuminate\Support\Str::random(8);

            $alumni->user->update([
                'password' => Hash::make($newPassword),
                'must_change_password' => true,
            ]);

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_RESET_PASSWORD,
                'alumni',
                $alumni->id,
                "Reset password alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn}) menjadi password acak"
            );

            DB::commit();
            $this->clearDashboardCache();
            session()->flash('new_password', $newPassword);
            return back()->with('success', "Password {$alumni->nama_lengkap} berhasil direset. Silakan salin password baru di atas.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }

    public function resetPasswordForm()
    {
        return view('admin.alumni.reset-password');
    }

    public function resetPasswordByNisn(Request $request)
    {
        $request->validate([
            'nisn'     => 'required|string|exists:alumni,nisn',
            'password' => 'required|min:8|confirmed',
        ], [
            'nisn.required'      => 'NISN wajib diisi',
            'nisn.exists'        => 'NISN tidak ditemukan dalam sistem',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        DB::beginTransaction();
        try {
            $alumni = Alumni::where('nisn', $request->nisn)->firstOrFail();

            if (!$alumni->user) {
                return back()->with('error', 'Akun user tidak ditemukan untuk alumni ini.');
            }

            $alumni->user->update([
                'password' => Hash::make($request->password),
                'must_change_password' => true,
            ]);

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_RESET_PASSWORD_NISN,
                'alumni',
                $alumni->id,
                "Reset password alumni (by NISN): {$alumni->nama_lengkap} ({$request->nisn})"
            );

            DB::commit();
            $this->clearDashboardCache();
            session()->flash('new_password', $request->password);
            return redirect()->route('admin.alumni.resetPasswordForm')
                ->with('success', "Password alumni NISN {$request->nisn} ({$alumni->nama_lengkap}) berhasil direset!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $alumni     = Alumni::withTrashed()->findOrFail($id);
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
                Auth::id(),
                AdminLog::ACTION_DELETE_ALUMNI,
                'alumni',
                null,
                "Menghapus permanen data alumni: {$namaAlumni} (NISN: {$nisnAlumni})"
            );

            DB::commit();
            $this->clearDashboardCache();
            return redirect()->route('admin.alumni.index')
                ->with('success', 'Data alumni berhasil dihapus permanen!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportForm()
    {
        $angkatans = Angkatan::get();
        return view('admin.alumni.export', compact('angkatans'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['status', 'angkatan_id', 'complete']);
        
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "Data_Alumni_{$timestamp}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AlumniExport($filters), 
            $fileName
        );
    }

    public function importForm()
    {
        return view('admin.alumni.import');
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AlumniTemplateExport, 
            'template_import_alumni.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // max 5MB
        ], [
            'file.required' => 'File Excel wajib diunggah',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        $filePath = null;
        try {
            // Simpan file sementara untuk diproses
            $filePath = $request->file('file')->store('imports');
            
            $import = new \App\Imports\AlumniImport(Auth::id());
            \Maatwebsite\Excel\Facades\Excel::import($import, $filePath);

            $this->clearDashboardCache();
            
            return redirect()->route('admin.alumni.index')
                ->with('success', 'Data alumni berhasil di-import! Silakan cek daftar di bawah.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import initiation failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memulai proses import. Silakan coba lagi atau hubungi administrator.');
        } finally {
            // P1-5 FIX: Selalu hapus file sementara setelah proses selesai
            // untuk mencegah penumpukan file di storage/imports/
            if ($filePath && \Illuminate\Support\Facades\Storage::exists($filePath)) {
                \Illuminate\Support\Facades\Storage::delete($filePath);
            }
        }
    }

    /**
     * Hapus seluruh data alumni dari sistem (Permanen)
     * Fitur ini berisiko tinggi, sehingga memerlukan konfirmasi teks khusus.
     */
    public function deleteAll(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|string|in:HAPUS SEMUA DATA',
        ], [
            'confirmation.in' => 'Kata konfirmasi tidak sesuai. Ketik "HAPUS SEMUA DATA" untuk melanjutkan.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Hapus data relasi (Pendidikan, Pekerjaan, Foto)
            DB::table('alumni_pendidikan')->delete();
            DB::table('alumni_pekerjaan')->delete();
            DB::table('alumni_fotos')->delete();
            
            // 2. Hapus Akun User (Hanya yang rolenya 'alumni')
            User::where('role', 'alumni')->forceDelete();
            
            // 3. Hapus Data Alumni
            Alumni::query()->forceDelete();

            // 4. Catat Log
            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_DELETE_ALL_ALUMNI,
                'alumni',
                null,
                "MENGHAPUS SELURUH DATA ALUMNI DAN AKUN TERKAIT DARI SISTEM SECARA PERMANEN."
            );

            DB::commit();
            $this->clearDashboardCache();

            return redirect()->route('admin.alumni.index')
                ->with('success', 'Seluruh data alumni berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Bulk delete failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data masal: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua cache dashboard admin agar statistik selalu up-to-date
     * setelah ada perubahan data alumni.
     */
    private function clearDashboardCache(): void
    {
        Cache::forget('admin_dashboard_stats');
        Cache::forget('admin_dashboard_recent_alumni');
        Cache::forget('admin_dashboard_angkatan_stats');
        Cache::forget('admin_dashboard_recent_updates');
        
        // Bersihkan cache dashboard Kepala Sekolah
        Cache::forget('kepala_sekolah_dashboard_stats');
        Cache::forget('kepala_sekolah_angkatan_stats');
        Cache::forget('kepala_sekolah_recent_alumni');
        
        // Bersihkan juga cache laporan dan landing
        LaporanController::clearLaporanCache();
        Cache::forget('landing_stats');
    }
}
