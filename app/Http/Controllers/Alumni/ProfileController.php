<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $alumni = Alumni::with(['user', 'angkatan', 'pendidikan', 'pekerjaan', 'fotos'])
            ->where('user_id', $user->id)
            ->first();

        if (!$alumni) {
            return redirect()->route('login')->with('error', 'Data alumni tidak ditemukan.');
        }

        return view('alumni.profile.edit', compact('alumni'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('login')->with('error', 'Data alumni tidak ditemukan.');
        }

        // VALIDATION
        // Menggunakan mimetypes (bukan mimes) agar tidak butuh extension finfo
        $request->validate([
            'alamat'                      => 'required|string',
            'no_hp'                       => 'required|numeric|digits_between:10,14',
            'show_no_hp'                  => 'nullable|in:0,1',
            'email'                       => 'nullable|email|max:255',
            'harapan'                     => 'nullable|string|max:500',
            'foto'                        => 'nullable|max:2048|mimetypes:image/jpeg,image/png,image/webp,image/gif',
            'pendidikan.*.jenjang'        => 'nullable|string|max:50',
            'pendidikan.*.nama_instansi'  => 'nullable|string|max:255',
            'pendidikan.*.program_studi'  => 'nullable|string|max:255',
            'pendidikan.*.tahun_masuk'    => 'nullable|numeric|digits:4',
            'pendidikan.*.tahun_lulus'    => 'nullable|numeric|digits:4',
            'pendidikan.*.is_ongoing'     => 'nullable|in:0,1',
            'pekerjaan.*.nama_perusahaan' => 'nullable|string|max:255',
            'pekerjaan.*.jabatan'         => 'nullable|string|max:255',
            'pekerjaan.*.is_current'      => 'nullable|in:0,1',
        ], [
            'alamat.required'         => 'Alamat wajib diisi',
            'no_hp.required'          => 'Nomor HP wajib diisi',
            'no_hp.numeric'           => 'Nomor HP harus berupa angka',
            'no_hp.digits_between'    => 'Nomor HP harus 10-14 digit',
            'harapan.max'             => 'Pesan & Harapan maksimal 500 karakter',
            'foto.mimetypes'          => 'Format gambar harus JPG, PNG, atau WEBP',
            'foto.max'                => 'Ukuran foto maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Data Alumni Dasar
            $alumni->update([
                'alamat'             => $request->alamat,
                'no_hp'              => $request->no_hp,
                'show_no_hp'         => $request->has('show_no_hp') ? 1 : 0,
                'email'              => $request->email,
                'harapan'            => $request->harapan,
            ]);

            // 2. Update Email User
            if ($request->filled('email')) {
                $user->update(['email' => $request->email]);
            }

            // 3. Handle Foto Profil
            // Menggunakan getClientOriginalExtension() + move()
            // Tidak membutuhkan extension finfo sama sekali
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                $fotoLama = $alumni->fotos()->where('is_main', true)->first();
                if ($fotoLama) {
                    $oldPath = public_path('storage/' . $fotoLama->path_file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                    $fotoLama->delete();
                }

                // Generate filename - tidak butuh finfo
                $extension       = strtolower($request->foto->getClientOriginalExtension());
                $filename        = 'alumni_' . $alumni->id . '_' . time() . '.' . $extension;
                $destinationPath = public_path('storage/foto_alumni');

                // Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Pindahkan file - tidak butuh finfo
                $request->foto->move($destinationPath, $filename);
                $path = 'foto_alumni/' . $filename;

                // Simpan ke database
                $alumni->fotos()->create([
                    'path_file' => $path,
                    'kategori'  => 'profil',
                    'is_main'   => true,
                ]);
            }

            // 4. Update Riwayat Pendidikan (Sync Logic)
            if ($request->has('pendidikan')) {
                $hasValidEducation = false;
                foreach ($request->pendidikan as $edu) {
                    if (!empty($edu['nama_instansi'])) {
                        $hasValidEducation = true;
                        break;
                    }
                }

                if ($hasValidEducation) {
                    $alumni->pendidikan()->delete();
                    foreach ($request->pendidikan as $edu) {
                        if (!empty($edu['nama_instansi'])) {
                            $isOngoing = (isset($edu['is_ongoing']) && $edu['is_ongoing'] == 1) ? 1 : 0;

                            $alumni->pendidikan()->create([
                                'jenjang'       => $edu['jenjang'] ?? null,
                                'nama_instansi' => $edu['nama_instansi'],
                                'program_studi' => $edu['program_studi'] ?? null,
                                'tahun_masuk'   => $edu['tahun_masuk'] ?? null,
                                'tahun_lulus'   => $isOngoing ? null : ($edu['tahun_lulus'] ?? null),
                                'is_ongoing'    => $isOngoing,
                            ]);
                        }
                    }
                }
            }

            // 5. Update Riwayat Pekerjaan
            if ($request->has('pekerjaan')) {
                $alumni->pekerjaan()->delete();
                foreach ($request->pekerjaan as $job) {
                    if (!empty($job['nama_perusahaan'])) {
                        $isCurrent = (isset($job['is_current']) && $job['is_current'] == 1) ? 1 : 0;

                        $alumni->pekerjaan()->create([
                            'nama_perusahaan' => $job['nama_perusahaan'],
                            'jabatan'         => $job['jabatan'] ?? null,
                            'is_current'      => $isCurrent,
                        ]);
                    }
                }
            }

            // 6. Update status kelengkapan profil
            $alumni->update(['is_profile_complete' => $alumni->isDataComplete()]);

            DB::commit();

            return redirect()->route('alumni.dashboard')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required'         => 'Password baru wajib diisi',
            'password.min'              => 'Password minimal 6 karakter',
            'password.confirmed'        => 'Konfirmasi password tidak sesuai',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function destroyPekerjaan($id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $pekerjaan = $alumni->pekerjaan()->find($id);

        if ($pekerjaan) {
            $pekerjaan->delete();
            return back()->with('success', 'Riwayat pekerjaan berhasil dihapus.');
        }

        return back()->with('error', 'Data pekerjaan tidak ditemukan atau akses ditolak.');
    }
}
