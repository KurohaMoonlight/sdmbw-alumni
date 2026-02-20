<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Angkatan;
use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AlumniController extends Controller
{
    /**
     * Tampilkan daftar alumni dengan filter lengkap
     */
    public function index(Request $request)
    {
        $query = Alumni::with(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->angkatan_id);
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
        $angkatans = Angkatan::orderByRaw('CAST(SUBSTRING(nama_angkatan, 10) AS UNSIGNED) ASC')->get();

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
        $angkatans = Angkatan::orderByRaw('CAST(SUBSTRING(nama_angkatan, 10) AS UNSIGNED) ASC')->get();
        return view('admin.alumni.edit', compact('alumni', 'angkatans'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn'         => 'required|string|max:20|unique:alumni,nisn,' . $alumni->id,
            'angkatan_id'  => 'required|exists:angkatan,id',
            'tahun_lulus'  => 'required|numeric|digits:4',
            'alamat'       => 'nullable|string',
            'no_hp'        => 'nullable|numeric|digits_between:10,14',
            'email'        => 'nullable|email|max:255',
            'harapan'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $alumni->update($request->only([
                'nama_lengkap', 'nisn', 'angkatan_id',
                'tahun_lulus', 'alamat', 'no_hp', 'email', 'harapan',
            ]));

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_UPDATE_ALUMNI,
                'alumni',
                $alumni->id,
                "Mengupdate data alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn})"
            );

            DB::commit();
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

            DB::commit();

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
        if (!$alumni->nisn) {
            return back()->with('error', 'Gagal reset! Data NISN tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            if (!$alumni->user) {
                return back()->with('error', 'Akun user tidak ditemukan.');
            }

            $alumni->user->update([
                'password' => Hash::make($alumni->nisn),
            ]);

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_RESET_PASSWORD,
                'alumni',
                $alumni->id,
                "Reset password alumni: {$alumni->nama_lengkap} (NISN: {$alumni->nisn}) menjadi NISN"
            );

            DB::commit();
            return back()->with('success', "Password {$alumni->nama_lengkap} berhasil direset ke NISN.");
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
            'password' => 'required|min:6|confirmed',
        ], [
            'nisn.required'      => 'NISN wajib diisi',
            'nisn.exists'        => 'NISN tidak ditemukan dalam sistem',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
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
            ]);

            AdminLog::log(
                Auth::id(),
                AdminLog::ACTION_RESET_PASSWORD_NISN,
                'alumni',
                $alumni->id,
                "Reset password alumni (by NISN): {$alumni->nama_lengkap} ({$request->nisn})"
            );

            DB::commit();
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
            return redirect()->route('admin.alumni.index')
                ->with('success', 'Data alumni berhasil dihapus permanen!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
