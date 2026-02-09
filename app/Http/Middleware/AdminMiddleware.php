<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        // 2. Cek apakah role user adalah 'admin'
        // Kita langsung cek kolom 'role' agar tidak bergantung pada fungsi tambahan
        if (Auth::user()->role !== 'admin') {
            // Kita gunakan abort 403 (Forbidden) atau lempar ke login
            abort(403, 'Akses ditolak. Halaman ini khusus untuk administrator.');
        }

        return $next($request);
    }
}
