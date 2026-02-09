<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan utama
     */
    public function index(Request $request)
    {
        // 1. Statistik Umum
        $stats = [
            'total_alumni'         => Alumni::count(),
            'alumni_verified'      => Alumni::where('status_verifikasi', 'verified')->count(),
            'alumni_pending'       => Alumni::where('status_verifikasi', 'pending')->count(),
            'alumni_rejected'      => Alumni::where('status_verifikasi', 'rejected')->count(),
            'profil_lengkap'       => Alumni::where('is_profile_complete', true)->count(),
            'profil_belum_lengkap' => Alumni::where('is_profile_complete', false)->count(),
        ];

        // 2. Statistik per Angkatan
        $angkatanStats = Angkatan::withCount([
            'alumni',
            'alumni as verified_count' => function ($query) {
                $query->where('status_verifikasi', 'verified');
            },
            'alumni as pending_count' => function ($query) {
                $query->where('status_verifikasi', 'pending');
            },
            'alumni as complete_count' => function ($query) {
                $query->where('is_profile_complete', true);
            }
        ])
        ->orderBy('id', 'asc')
        ->get();

        // 3. Alumni berdasarkan Instansi Pendidikan Terpopuler
        $pendidikanStats = DB::table('alumni_pendidikan')
            ->select('nama_instansi as pendidikan_lanjutan', DB::raw('count(*) as total'))
            ->whereNotNull('nama_instansi')
            ->where('nama_instansi', '!=', '')
            ->groupBy('nama_instansi')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        // 4. Alumni berdasarkan Perusahaan/Pekerjaan Terpopuler
        $pekerjaanStats = DB::table('alumni_pekerjaan')
            ->select('nama_perusahaan as pekerjaan', DB::raw('count(*) as total'))
            ->whereNotNull('nama_perusahaan')
            ->where('nama_perusahaan', '!=', '')
            ->groupBy('nama_perusahaan')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        return view('admin.laporan.index', compact(
            'stats',
            'angkatanStats',
            'pendidikanStats',
            'pekerjaanStats'
        ));
    }

    /**
     * Laporan mendetail per angkatan tertentu
     */
    public function angkatan(Request $request, $angkatanId)
    {
        $angkatan = Angkatan::findOrFail($angkatanId);

        $alumniQuery = Alumni::with(['user', 'pendidikan', 'pekerjaan'])
            ->where('angkatan_id', $angkatanId);

        // Filter status jika ada
        if ($request->filled('status')) {
            $alumniQuery->where('status_verifikasi', $request->status);
        }

        $alumni = $alumniQuery->orderBy('nama_lengkap')->get();

        // Format alumni dengan data pendidikan & pekerjaan yang readable
        $alumniFormatted = $alumni->map(function ($item) {
            return [
                'id' => $item->id,
                'nisn' => $item->nisn,
                'nama_lengkap' => $item->nama_lengkap,
                'username' => $item->user->username ?? '-',
                'no_hp' => $item->no_hp ?? '-',
                'pendidikan_terakhir' => $this->formatPendidikan($item->pendidikan),
                'pekerjaan_terkini' => $this->formatPekerjaan($item->pekerjaan),
                'status_verifikasi' => $item->status_verifikasi,
                'is_profile_complete' => $item->is_profile_complete,
            ];
        });

        // Statistik angkatan
        $stats = [
            'total'    => $alumni->count(),
            'verified' => $alumni->where('status_verifikasi', 'verified')->count(),
            'pending'  => $alumni->where('status_verifikasi', 'pending')->count(),
            'rejected' => $alumni->where('status_verifikasi', 'rejected')->count(),
            'lengkap'  => $alumni->where('is_profile_complete', true)->count(),
            'belum_lengkap' => $alumni->where('is_profile_complete', false)->count(),
        ];

        return view('admin.laporan.angkatan', compact('angkatan', 'alumniFormatted', 'stats'));
    }

    /**
     * Format pendidikan menjadi readable string
     */
    private function formatPendidikan($pendidikanCollection)
    {
        if ($pendidikanCollection->isEmpty()) {
            return '-';
        }

        // Ambil pendidikan terakhir (dengan tahun lulus paling baru atau tahun masuk terbaru)
        $pendidikan = $pendidikanCollection->sortByDesc('tahun_lulus')->first();

        if (!$pendidikan) {
            return '-';
        }

        $text = $pendidikan->jenjang;
        if ($pendidikan->nama_instansi) {
            $text .= ' - ' . $pendidikan->nama_instansi;
        }
        if ($pendidikan->program_studi && $pendidikan->jenjang === 'Perguruan Tinggi') {
            $text .= ' (' . $pendidikan->program_studi . ')';
        }

        return $text;
    }

    /**
     * Format pekerjaan menjadi readable string
     */
    private function formatPekerjaan($pekerjaanCollection)
    {
        if ($pekerjaanCollection->isEmpty()) {
            return '-';
        }

        // Ambil pekerjaan terkini (is_current = true)
        $pekerjaan = $pekerjaanCollection->where('is_current', true)->first();

        // Jika tidak ada is_current, ambil pekerjaan terakhir
        if (!$pekerjaan) {
            $pekerjaan = $pekerjaanCollection->last();
        }

        if (!$pekerjaan) {
            return '-';
        }

        $text = $pekerjaan->jabatan;
        if ($pekerjaan->nama_perusahaan) {
            $text .= ' di ' . $pekerjaan->nama_perusahaan;
        }

        return $text;
    }
}
