<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Angkatan;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class KepalaSekolahController extends Controller
{
    /**
     * Tampilkan dashboard Kepala Sekolah
     *
     * @return View
     */
    public function dashboard(): View
    {
        $stats = Cache::remember('kepala_sekolah_dashboard_stats', 300, function () {
            return [
                'total_alumni'       => Alumni::count(),
                'total_terverifikasi'=> Alumni::where('status_verifikasi', 'verified')->count(),
                'total_angkatan'     => Angkatan::whereIn('status', ['AKTIF', 'LULUS'])->count(),
                'tracer_study'       => Alumni::select('jenjang_pendidikan_saat_ini', DB::raw('count(*) as total'))
                                            ->whereNotNull('jenjang_pendidikan_saat_ini')
                                            ->groupBy('jenjang_pendidikan_saat_ini')
                                            ->pluck('total', 'jenjang_pendidikan_saat_ini')
                                            ->toArray(),
            ];
        });

        $angkatanStats = Cache::remember('kepala_sekolah_angkatan_stats', 600, function () {
            return Angkatan::whereIn('status', ['AKTIF', 'LULUS'])
                ->withCount('alumni')
                ->orderBy('id', 'asc')
                ->get();
        });

        $recentAlumni = Cache::remember('kepala_sekolah_recent_alumni', 300, function () {
            return Alumni::with(['user', 'angkatan'])
                ->latest()
                ->take(10)
                ->get();
        });

        return view('kepala_sekolah.dashboard', compact('stats', 'angkatanStats', 'recentAlumni'));
    }
}
