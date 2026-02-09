<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\AngkatanController;
use App\Http\Controllers\Admin\AlumniController as AdminAlumni;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboard;
use App\Http\Controllers\Alumni\ProfileController;
use App\Http\Controllers\Alumni\DirektoriController;
use App\Http\Controllers\Alumni\RiwayatController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing.index');

/*
|--------------------------------------------------------------------------
| Guest Routes (Login & Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Cek NISN (AJAX / Realtime)
    Route::post('/cek-nisn', [RegisterController::class, 'cekNisn'])->name('cek.nisn');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua User yang Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/direktori', [LandingController::class, 'direktori'])->name('landing.direktori');
    Route::get('/profil-alumni/{alumni}', [LandingController::class, 'profilAlumni'])->name('landing.profil');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Kelola Angkatan
        Route::resource('angkatan', AngkatanController::class);

        // Kelola Alumni
        Route::prefix('alumni')->name('alumni.')->controller(AdminAlumni::class)->group(function () {
            // List & Detail Alumni
            Route::get('/', 'index')->name('index');
            Route::get('/{alumni}', 'show')->name('show');

            // Edit Alumni
            Route::get('/{alumni}/edit', 'edit')->name('edit');
            Route::put('/{alumni}', 'update')->name('update');

            // Verifikasi Alumni
            Route::put('/{alumni}/verify', 'verify')->name('verify');

            // Reset Password Alumni
            Route::put('/{alumni}/reset-password', 'resetPassword')->name('resetPassword'); // Reset ke NISN
            Route::get('/reset-password-form', 'resetPasswordForm')->name('resetPasswordForm'); // Form reset by NISN
            Route::post('/reset-password-nisn', 'resetPasswordByNisn')->name('resetPasswordByNisn'); // Reset by NISN lookup

            // Export Alumni
            Route::get('/export-form', 'exportForm')->name('exportForm'); // Form export
            Route::get('/export', 'export')->name('export'); // Download Excel

            // Hapus Alumni Permanen
            Route::delete('/{alumni}', 'destroy')->name('destroy');
        });

        // Laporan & Tracer Study
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/angkatan/{angkatan}', [LaporanController::class, 'angkatan'])->name('laporan.angkatan');

        // Activity Logs
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
            Route::delete('/{log}', [ActivityLogController::class, 'destroy'])->name('destroy');
            Route::delete('/', [ActivityLogController::class, 'clearAll'])->name('clearAll');
        });
    });

/*
|--------------------------------------------------------------------------
| Alumni Routes (Role: alumni)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'alumni'])
    ->prefix('alumni')
    ->name('alumni.')
    ->group(function () {
        // Dashboard Alumni
        Route::get('/dashboard', [AlumniDashboard::class, 'index'])->name('dashboard');

        // Profile Management
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

        // Riwayat Pendidikan
        Route::post('/riwayat-pendidikan', [RiwayatController::class, 'storePendidikan'])->name('pendidikan.store');
        Route::delete('/riwayat-pendidikan/{pendidikan}', [RiwayatController::class, 'destroyPendidikan'])->name('pendidikan.destroy');

        // Riwayat Pekerjaan
        Route::post('/riwayat-pekerjaan', [RiwayatController::class, 'storePekerjaan'])->name('pekerjaan.store');
        Route::delete('/riwayat-pekerjaan/{pekerjaan}', [ProfileController::class, 'destroyPekerjaan'])->name('pekerjaan.destroy');

        // Direktori Alumni
        Route::prefix('direktori')->name('direktori.')->group(function () {
            Route::get('/', [DirektoriController::class, 'index'])->name('index');
            Route::get('/{alumni}', [DirektoriController::class, 'show'])->name('show');
        });
    });
