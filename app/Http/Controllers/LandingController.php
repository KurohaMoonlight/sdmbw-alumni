<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Angkatan;
use App\Models\Faq;
use App\Models\Testimoni;
use App\Models\Comment; // TAMBAHKAN INI: Mengimpor model Comment
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing/home dengan statistik
     *
     * @return View
     */
    public function index(): View
    {
        $stats = \Illuminate\Support\Facades\Cache::remember('landing_stats', 60 * 60, function () {
            return [
                'total_alumni' => Alumni::verified()->count(),
                'total_angkatan' => Angkatan::count(),
                'profil_lengkap' => Alumni::where('is_profile_complete', true)->count(),
                'total_instansi' => \Illuminate\Support\Facades\DB::table('alumni_pendidikan')->distinct('nama_instansi')->count('nama_instansi') 
                                   + \Illuminate\Support\Facades\DB::table('alumni_pekerjaan')->distinct('nama_perusahaan')->count('nama_perusahaan'),
            ];
        });

        $faqs = \Illuminate\Support\Facades\Cache::remember('landing_faqs', 60 * 60, function () {
            return Faq::where('is_active', true)->orderBy('order_num', 'asc')->get();
        });
        
        $testimonis = \Illuminate\Support\Facades\Cache::remember('landing_testimonis', 60 * 60, function () {
            return Testimoni::with(['alumni.fotos', 'alumni.angkatan'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get();
        });

        $beritas = \Illuminate\Support\Facades\Cache::remember('landing_beritas', 60 * 60, function () {
            return Berita::where('is_active', true)->latest()->take(6)->get();
        });

        // TAMBAHKAN INI: Mengambil 10 komentar terbaru dengan cache
        $comments = \Illuminate\Support\Facades\Cache::remember('landing_comments', 60 * 60, function () {
            return Comment::latest()->take(10)->get();
        });

        // UBAH BARIS INI: Tambahkan 'comments' di dalam compact()
        return view('landing.index', compact('stats', 'faqs', 'testimonis', 'comments', 'beritas'));
    }
}