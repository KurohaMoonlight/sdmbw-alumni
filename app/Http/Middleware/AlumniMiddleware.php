<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlumniMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek apakah user adalah alumni
        if (Auth::user()->role !== 'alumni') {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Alumni.');
        }

        // 3. Pertahanan Berlapis: Cek apakah akun aktif (is_active)
        // Ini memastikan jika admin menonaktifkan akun saat user masih login,
        // user tersebut akan otomatis terpental saat pindah halaman.
        if (!Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan atau belum diverifikasi oleh Admin.');
        }

        return $next($request);
    }
}
