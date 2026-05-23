<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            if (Auth::user()->role === 'kepala_sekolah') {
                return redirect()->route('kepala_sekolah.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
