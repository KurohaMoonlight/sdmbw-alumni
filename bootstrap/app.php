<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware agar bisa dipakai di routes
        // ForceChangePassword HANYA diterapkan pada grup alumni (di web.php),
        // tidak pada seluruh web group agar tidak mengganggu request publik.
        $middleware->alias([
            'admin'              => \App\Http\Middleware\AdminMiddleware::class,
            'admin_only'         => \App\Http\Middleware\AdminOnlyMiddleware::class,
            'kepala_sekolah'     => \App\Http\Middleware\KepalaSekolahMiddleware::class,
            'alumni'             => \App\Http\Middleware\AlumniMiddleware::class,
            'alumni.onboarding'  => \App\Http\Middleware\EnsureAlumniOnboarding::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
