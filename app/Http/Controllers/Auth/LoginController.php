<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan form login
     *
     * @return View|RedirectResponse
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    /**
     * Proses login user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        // Cek apakah username ada di database
        $user = User::where('username', $credentials['username'])->first();

        // Jika username tidak ditemukan
        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak terdaftar. Silahkan daftar terlebih dahulu sebagai Alumni.',
            ])->onlyInput('username');
        }

        // Proses login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Validasi status aktif untuk alumni
            if ($user->role === 'alumni' && !$user->is_active) {
                Auth::logout();

                // Load relasi alumni untuk cek status_verifikasi
                $alumni = $user->alumni;

                if ($alumni && $alumni->status_verifikasi === 'rejected') {
                    // ── Alumni DITOLAK ──
                    // Flash 'login_status' = 'rejected' agar blade tampilkan
                    // alert merah (danger) berbeda dari pending (warning)
                    return back()
                        ->withErrors([
                            'username' => 'Pendaftaran Anda telah ditolak oleh Admin. '
                                . 'Silahkan hubungi Admin SD Muhammadiyah Birrul Walidain '
                                . 'untuk informasi lebih lanjut.',
                        ])
                        ->onlyInput('username')
                        ->with('login_status', 'rejected');
                }

                // ── Alumni PENDING (belum diverifikasi) ──
                return back()
                    ->withErrors([
                        'username' => 'Akun Anda belum diverifikasi oleh Admin SD. '
                            . 'Harap tunggu atau hubungi Admin via WA.',
                    ])
                    ->onlyInput('username')
                    ->with('login_status', 'pending');
            }

            // Regenerasi session setelah login berhasil
            $request->session()->regenerate();

            // Update waktu login terakhir
            $user->update(['last_login_at' => now()]);

            return $this->redirectBasedOnRole();
        }

        // Jika password salah
        return back()->withErrors([
            'username' => 'Password yang Anda masukkan salah. Silahkan coba lagi.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('landing.index')
            ->with('success', 'Berhasil logout.');
    }

    /**
     * Redirect berdasarkan role user
     *
     * @return RedirectResponse
     */
    private function redirectBasedOnRole(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'alumni') {
            return redirect()->route('alumni.dashboard');
        }

        Auth::logout();

        return redirect()->route('login')->withErrors([
            'username' => 'Role akun tidak valid.',
        ]);
    }
}
