<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Angkatan;
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
        $stats = [
            'total_alumni' => Alumni::verified()->count(),
            'total_angkatan' => Angkatan::count(),
        ];

        return view('landing.index', compact('stats'));
    }

    /**
     * Tampilkan direktori alumni publik dengan filter dan pagination
     *
     * @param Request $request
     * @return View
     */
    public function direktori(Request $request): View
    {
        // Query alumni yang terverifikasi dengan eager loading
        $query = Alumni::with(['angkatan', 'user'])->verified();

        // Filter angkatan
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->angkatan_id);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Pagination dengan query string
        $alumnis = $query->latest()->paginate(12)->withQueryString();

        // Ambil angkatan untuk filter dropdown
        $angkatans = Angkatan::orderBy('tahun_ajaran', 'desc')->get();

        return view('landing.direktori', compact('alumnis', 'angkatans'));
    }

    /**
     * Tampilkan profil alumni detail (publik)
     *
     * @param Alumni $alumni
     * @return View
     */
    public function profilAlumni(Alumni $alumni): View
    {
        // Load relasi jika belum di-load
        $alumni->loadMissing(['angkatan', 'user', 'pendidikan', 'pekerjaan', 'fotos']);

        // Pastikan hanya alumni yang verified yang bisa diakses
        if ($alumni->status_verifikasi !== 'verified') {
            abort(404);
        }

        return view('landing.profil', compact('alumni'));
    }
}
