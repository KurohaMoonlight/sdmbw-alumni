<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi
     *
     * @return View
     */
    public function showRegistrationForm(): View
    {
        // Ambil angkatan yang statusnya LULUS untuk pendaftaran
        $angkatans = Angkatan::where('status', 'LULUS')
            ->orderBy('id', 'asc')
            ->get();

        return view('auth.register', compact('angkatans'));
    }

    /**
     * Proses registrasi alumni baru
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // 1. Validasi input dengan pesan custom
        $validated = $request->validate([
            'nisn' => ['required', 'digits:10', 'unique:alumni,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'angkatan_id' => ['required', 'exists:angkatan,id'],
            'tahun_lulus' => ['required', 'integer', 'min:2010', 'max:' . (date('Y') + 1)],
            'username' => ['required', 'string', 'alpha_num', 'min:4', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus terdiri dari tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini sudah terdaftar sebelumnya.',
            'nama_lengkap.required' => 'Nama lengkap sesuai ijazah wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            'angkatan_id.required' => 'Angkatan wajib dipilih.',
            'angkatan_id.exists' => 'Angkatan yang dipilih tidak valid.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'tahun_lulus.min' => 'Tahun lulus tidak valid.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_num' => 'Username hanya boleh terdiri dari huruf dan angka, tanpa spasi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.unique' => 'Username sudah digunakan oleh orang lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Double check angkatan valid
        $angkatan = Angkatan::findOrFail($validated['angkatan_id']);
        if ($angkatan->status !== 'LULUS') {
            return back()->withErrors([
                'angkatan_id' => 'Angkatan yang Anda pilih belum diizinkan untuk mendaftar.',
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // 3. Buat data user (Login)
            // is_active = false agar akun terkunci sementara menunggu verifikasi admin
            $user = User::create([
                'username' => strtolower($validated['username']),
                'password' => Hash::make($validated['password']),
                'role' => 'alumni',
                'is_active' => false,
                'nisn' => $validated['nisn'],
            ]);

            // 4. Buat profil alumni
            Alumni::create([
                'user_id' => $user->id,
                'nisn' => $validated['nisn'],
                'nama_lengkap' => strip_tags($validated['nama_lengkap']),
                'angkatan_id' => $validated['angkatan_id'],
                'tahun_lulus' => $validated['tahun_lulus'],
                'status_verifikasi' => 'pending',
                'is_profile_complete' => false,
            ]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Registrasi Berhasil! Akun Anda sedang menunggu verifikasi admin SD. Mohon tunggu beberapa saat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Kesalahan Registrasi Alumni: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Gagal memproses pendaftaran. Silakan coba lagi.'])
                ->withInput();
        }
    }
}
