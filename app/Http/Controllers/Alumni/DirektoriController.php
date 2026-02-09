<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirektoriController extends Controller
{
    /**
     * Tampilkan daftar direktori alumni dengan filter dan pagination
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Eager load untuk menghindari N+1 query
        $query = Alumni::with(['angkatan', 'fotos']);

        // Filter pencarian nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%");
        }

        // Filter angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan_id', $request->angkatan);
        }

        // Pagination dengan query string
        $alumni = $query->latest()->paginate(12)->withQueryString();

        // Ambil semua angkatan untuk dropdown filter
        $angkatan = Angkatan::orderBy('tahun_ajaran', 'asc')->get();

        return view('alumni.direktori.index', compact('alumni', 'angkatan'));
    }

    /**
     * Tampilkan detail profil alumni
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        // Load semua relasi untuk menampilkan data lengkap
        $alumni = Alumni::with([
            'angkatan',
            'pendidikan',
            'pekerjaan',
            'fotos',
        ])->findOrFail($id);

        return view('alumni.direktori.show', compact('alumni'));
    }
}
