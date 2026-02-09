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
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager Loading untuk efisiensi N+1 query
        $query = Alumni::with(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);

        // Filter status verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Filter berdasarkan angkatan_id
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->angkatan_id);
        }

        // Filter kelengkapan profil (1 = lengkap, 0 = belum)
        if ($request->filled('complete')) {
            $complete = $request->complete === '1';
            $query->where('is_profile_complete', $complete);
        }

        // Search Nama atau NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $alumnis = $query->latest()->paginate(20)->withQueryString();
        $angkatans = Angkatan::orderByRaw('CAST(SUBSTRING(nama_angkatan, 10) AS UNSIGNED) ASC')->get();

        return view('admin.alumni.index', compact('alumnis', 'angkatans'));
    }

    /**
     * Tampilkan detail alumni lengkap dengan foto
     *
     * @param Alumni $alumni
     * @return \Illuminate\View\View
     */
    public function show(Alumni $alumni)
    {
        $alumni->load(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);

        return view('admin.alumni.show', compact('alumni'));
    }

    /**
     * Form edit alumni
     *
     * @param Alumni $alumni
     * @return \Illuminate\View\View
     */
    public function edit(Alumni $alumni)
    {
        $alumni->load(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos']);
        $angkatans = Angkatan::orderByRaw('CAST(SUBSTRING(nama_angkatan, 10) AS UNSIGNED) ASC')->get();

        return view('admin.alumni.edit', compact('alumni', 'angkatans'));
    }

    /**
     * Update data alumni
     *
     * @param Request $request
     * @param Alumni $alumni
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:alumni,nisn,' . $alumni->id,
            'angkatan_id' => 'required|exists:angkatan,id',
            'tahun_lulus' => 'required|numeric|digits:4',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|numeric|digits_between:10,14',
            'email' => 'nullable|email|max:255',
            'harapan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $alumni->update($request->only([
                'nama_lengkap',
                'nisn',
                'angkatan_id',
                'tahun_lulus',
                'alamat',
                'no_hp',
                'email',
                'harapan',
            ]));

            AdminLog::log(
                Auth::id(),
                'update_alumni',
                'alumni',
                $alumni->id,
                "Mengupdate data alumni: {$alumni->nama_lengkap}"
            );

            DB::commit();

            return redirect()->route('admin.alumni.show', $alumni)->with('success', 'Data alumni berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Verifikasi status alumni
     *
     * @param Request $request
     * @param Alumni $alumni
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, Alumni $alumni)
    {
        $request->validate([
            'status' => 'required|in:verified,pending,rejected',
        ]);

        $status = $request->input('status', 'verified');

        DB::beginTransaction();
        try {
            $alumni->update(['status_verifikasi' => $status]);

            if ($alumni->user) {
                $alumni->user->update([
                    'is_active' => ($status === 'verified') ? 1 : 0,
                ]);
            }

            AdminLog::log(
                Auth::id(),
                'verify_alumni',
                'alumni',
                $alumni->id,
                "Mengubah status verifikasi {$alumni->nama_lengkap} ke {$status}"
            );

            DB::commit();

            $message = match($status) {
                'verified' => 'Alumni berhasil diverifikasi dan akun diaktifkan.',
                'rejected' => 'Alumni berhasil ditolak.',
                default => 'Status verifikasi berhasil diperbarui.',
            };

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Reset password alumni kembali ke NISN
     *
     * @param Alumni $alumni
     * @return \Illuminate\Http\RedirectResponse
     */
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
                'reset_password',
                'alumni',
                $alumni->id,
                "Reset password alumni: {$alumni->nama_lengkap} menjadi NISN"
            );

            DB::commit();

            return back()->with('success', "Password {$alumni->nama_lengkap} berhasil direset ke NISN.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form reset password by NISN
     *
     * @return \Illuminate\View\View
     */
    public function resetPasswordForm()
    {
        return view('admin.alumni.reset-password');
    }

    /**
     * Reset password alumni by NISN (lookup)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordByNisn(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|exists:alumni,nisn',
            'password' => 'required|min:6|confirmed',
        ], [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.exists' => 'NISN tidak ditemukan dalam sistem',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
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
                'reset_password_nisn',
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

    /**
     * Hapus Alumni Secara Permanen
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Menggunakan withTrashed agar bisa menemukan data yang sudah soft-deleted sebelumnya jika ada
        $alumni = Alumni::withTrashed()->findOrFail($id);
        $namaAlumni = $alumni->nama_lengkap;

        DB::beginTransaction();
        try {
            // Hapus relasi data (Force Delete jika ingin benar-benar bersih dari DB)
            $alumni->pendidikan()->delete();
            $alumni->pekerjaan()->delete();
            $alumni->fotos()->delete();

            // Hapus user account jika ada secara permanen
            if ($alumni->user_id) {
                User::where('id', $alumni->user_id)->forceDelete();
            }

            // Hapus alumni secara permanen
            $alumni->forceDelete();

            AdminLog::log(
                Auth::id(),
                'delete_alumni',
                'alumni',
                null,
                "Menghapus permanen: {$namaAlumni}"
            );

            DB::commit();

            return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dihapus permanen!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
