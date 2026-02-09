<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use App\Models\Alumni;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik lengkap
     *
     * @return View
     */
    public function index(): View
    {
        // 1. Statistik Utama
        $stats = [
            'total_alumni' => Alumni::count(),
            'alumni_verified' => Alumni::where('status_verifikasi', 'verified')->count(),
            'alumni_pending' => Alumni::where('status_verifikasi', 'pending')->count(),
            'total_angkatan' => Angkatan::whereIn('status', ['AKTIF', 'LULUS'])->count(),
            'angkatan_aktif' => Angkatan::where('status', 'AKTIF')->count(),
            'angkatan_lulus' => Angkatan::where('status', 'LULUS')->count(),
            'angkatan_belum_lulus' => Angkatan::where('status', 'BELUM_LULUS')->count(),
            'profil_lengkap' => Alumni::where('is_profile_complete', true)->count(),
        ];

        // 2. Alumni terbaru dengan eager loading
        $recentAlumni = Alumni::with(['user', 'angkatan'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Statistik per angkatan
        $angkatanStats = Angkatan::whereIn('status', ['AKTIF', 'LULUS'])
            ->withCount('alumni')
            ->orderBy('id', 'asc')
            ->get();

        // 4. Alumni dengan profil lengkap (update terkini)
        $recentUpdates = Alumni::with(['user', 'angkatan'])
            ->where('is_profile_complete', true)
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentAlumni',
            'angkatanStats',
            'recentUpdates'
        ));
    }
}
