<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        // Proses login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Validasi status aktif untuk alumni
            if ($user->role === 'alumni' && !$user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Akun Anda belum diverifikasi oleh Admin SD. Harap tunggu atau hubungi Admin via WA.',
                ])->onlyInput('username');
            }

            // Regenerasi session setelah login
            $request->session()->regenerate();

            // Update waktu login terakhir
            $user->update(['last_login_at' => now()]);

            return $this->redirectBasedOnRole();
        }

        // Jika username atau password salah
        return back()->withErrors([
            'username' => 'Username atau password salah.',
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
