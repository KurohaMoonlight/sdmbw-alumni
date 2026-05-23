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
                'username' => 'Username tidak terdaftar. Silakan hubungi Admin jika Anda adalah Alumni.',
            ])->onlyInput('username');
        }

        // Proses login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Regenerasi session setelah login berhasil
            $request->session()->regenerate();

            // Update waktu login terakhir
            $user->update(['last_login_at' => now()]);

            if ($user->role === 'admin') {
                \App\Models\AdminLog::log(
                    $user->id,
                    'admin_login',
                    'users',
                    $user->id,
                    "Admin login ke sistem."
                );
            }

            return $this->redirectBasedOnRole();
        }

        // Jika password salah
        return back()->withErrors([
            'username' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
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

        if ($user->role === 'kepala_sekolah') {
            return redirect()->route('kepala_sekolah.dashboard');
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
