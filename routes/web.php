<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Public\AlumniPublicController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\AngkatanController;
use App\Http\Controllers\Admin\AlumniController as AdminAlumni;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboard;
use App\Http\Controllers\Alumni\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ForumThreadController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\Admin\ForumController as AdminForumController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/direktori-alumni', [AlumniPublicController::class, 'direktori'])->name('public.direktori');
Route::get('/direktori-alumni/{alumni}', [AlumniPublicController::class, 'show'])->name('public.profil');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

Route::prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/{forum:slug}', [ForumController::class, 'show'])->name('show');
    Route::get('/thread/{thread:slug}', [ForumThreadController::class, 'show'])->name('thread.show');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1'); // max 5 percobaan login per menit per IP
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua User yang Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['forum.auth'])->group(function () {
    Route::get('/forum-create-thread', [ForumThreadController::class, 'create'])->name('forum.thread.create');
    Route::post('/forum-thread', [ForumThreadController::class, 'store'])->name('forum.thread.store');
    Route::delete('/forum-thread/{thread}', [ForumThreadController::class, 'destroy'])->name('forum.thread.destroy');
    Route::post('/forum-thread/{thread}/reply', [ForumReplyController::class, 'store'])->name('forum.reply.store');
    Route::delete('/forum-reply/{reply}', [ForumReplyController::class, 'destroy'])->name('forum.reply.destroy');
});

/*
|--------------------------------------------------------------------------
| Kepala Sekolah Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'kepala_sekolah'])
    ->prefix('kepala-sekolah')
    ->name('kepala_sekolah.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KepalaSekolah\KepalaSekolahController::class, 'dashboard'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin_only'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Kelola Pengguna (Admin & Kepala Sekolah)
        Route::resource('users', UserController::class)->except(['show']);

        // Kelola Angkatan
        Route::resource('angkatan', AngkatanController::class);

        // Kelola Alumni (Actions & Static Routes)
        Route::prefix('alumni')->name('alumni.')->controller(AdminAlumni::class)->group(function () {
            // Form & Action: Reset password by NISN
            Route::get('/reset-password-form', 'resetPasswordForm')->name('resetPasswordForm');
            Route::post('/reset-password-nisn', 'resetPasswordByNisn')->name('resetPasswordByNisn');

            // Form & Action: Export Excel
            Route::get('/export-form', 'exportForm')->name('exportForm');
            Route::post('/export', 'export')->name('export');

            // Form & Action: Import Excel
            Route::get('/import-form', 'importForm')->name('importForm');
            Route::get('/import-template', 'downloadTemplate')->name('downloadTemplate');
            Route::post('/import', 'import')->name('import');

            // Bulk Action: Delete All
            Route::post('/delete-all', 'deleteAll')->name('deleteAll');

            // Wildcard action routes
            Route::get('/{alumni}/edit', 'edit')->name('edit');
            Route::put('/{alumni}', 'update')->name('update');
            Route::put('/{alumni}/verify', 'verify')->name('verify');
            Route::post('/{alumni}/reset-password', 'resetPassword')->name('reset-password');
            Route::delete('/{alumni}', 'destroy')->name('destroy');
        });

        // FAQ & Testimoni (CMS Publik)
        Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show']);
        Route::resource('testimonis', \App\Http\Controllers\Admin\TestimoniController::class)->except(['show']);
        Route::resource('beritas', \App\Http\Controllers\Admin\BeritaController::class)->except(['show']);

        // Activity Logs (Actions)
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::delete('/{log}', [ActivityLogController::class, 'destroy'])->name('destroy');
            Route::delete('/', [ActivityLogController::class, 'clearAll'])->name('clearAll');
        });

        // Laporan Export PDF (Admin Only)
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // Forum Moderation & Categories
        Route::resource('forum-categories', \App\Http\Controllers\Admin\ForumCategoryController::class)
            ->parameters(['forum-categories' => 'forum'])
            ->except(['show', 'create', 'edit']);

        Route::prefix('forum')->name('forum.')->group(function () {
            Route::get('/', [AdminForumController::class, 'index'])->name('index');
            Route::post('/thread/{thread}/pin', [AdminForumController::class, 'pin'])->name('pin');
            Route::post('/thread/{thread}/lock', [AdminForumController::class, 'lock'])->name('lock');
            Route::delete('/thread/{thread}', [AdminForumController::class, 'destroyThread'])->name('destroyThread');
            Route::delete('/reply/{reply}', [AdminForumController::class, 'destroyReply'])->name('destroyReply');
        });
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Shared with Kepala Sekolah)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'kepala_sekolah'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Kelola Alumni (Read-Only)
        // Didefinisikan SETELAH route statis admin_only untuk mencegah konflik wildcard
        Route::prefix('alumni')->name('alumni.')->controller(AdminAlumni::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{alumni}', 'show')->name('show');
        });

        // Laporan & Tracer Study
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/angkatan/{angkatan}', [LaporanController::class, 'angkatan'])->name('laporan.angkatan');

        // Activity Logs (Read-Only)
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
        });
    });

/*
|--------------------------------------------------------------------------
| Alumni Routes (Role: alumni)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'alumni', 'alumni.onboarding'])
    ->prefix('alumni')
    ->name('alumni.')
    ->group(function () {
        // Dashboard Alumni
        Route::get('/dashboard', [AlumniDashboard::class, 'index'])->name('dashboard');

        // Profile Management
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

        // Testimonial — Wajib diisi setelah profil lengkap
        Route::get('/testimonial', [ProfileController::class, 'testimonialForm'])->name('testimonial.form');
        Route::post('/testimonial', [ProfileController::class, 'storeTestimonial'])->name('testimonial.store');

        // Direktori Alumni (Unified with Public Controller)
        Route::prefix('direktori')->name('direktori.')->group(function () {
            Route::get('/', [AlumniPublicController::class, 'direktori'])->name('index');
            Route::get('/{alumni}', [AlumniPublicController::class, 'show'])->name('show');
        });
    });


