<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AngkatanController extends Controller
{
    /**
     * Tampilkan daftar angkatan
     */
    public function index()
    {
        // Mengambil data angkatan dengan jumlah alumni, diurutkan dari yang terlama ke terbaru
        $angkatans = Angkatan::withCount('alumni')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.angkatan.index', compact('angkatans'));
    }

    /**
     * Form tambah angkatan
     */
    public function create()
    {
        // Hitung nomor angkatan berikutnya berdasarkan jumlah data yang ada
        $totalAngkatan = Angkatan::count();
        $nextNumber = $totalAngkatan + 1;

        return view('admin.angkatan.create', compact('nextNumber'));
    }

    /**
     * Simpan angkatan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255',
            'tahun_ajaran'  => 'required|string|max:255',
            'status'        => 'required|in:AKTIF,LULUS',
        ]);

        $angkatan = Angkatan::create($validated);

        // Log aktivitas admin
        AdminLog::log(
            Auth::id(),
            'create_angkatan',
            'angkatan',
            $angkatan->id,
            "Menambah angkatan: {$angkatan->nama_angkatan}"
        );

        return redirect()
            ->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil ditambahkan!');
    }

    /**
     * Form edit angkatan
     */
    public function edit(Angkatan $angkatan)
    {
        return view('admin.angkatan.edit', compact('angkatan'));
    }

    /**
     * Update angkatan
     */
    public function update(Request $request, Angkatan $angkatan)
    {
        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255',
            'tahun_ajaran'  => 'required|string|max:255',
            'status'        => 'required|in:AKTIF,LULUS',
        ]);

        $oldStatus = $angkatan->status;
        $angkatan->update($validated);

        // Susun deskripsi log untuk mencatat perubahan status jika ada
        $description = "Mengubah angkatan: {$angkatan->nama_angkatan}";
        if ($oldStatus != $validated['status']) {
            $description .= " (Status: {$oldStatus} → {$validated['status']})";
        }

        // Log aktivitas admin
        AdminLog::log(
            Auth::id(),
            'update_angkatan',
            'angkatan',
            $angkatan->id,
            $description
        );

        return redirect()
            ->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil diupdate!');
    }

    /**
     * Hapus angkatan
     */
    public function destroy(Angkatan $angkatan)
    {
        // Proteksi: Cek apakah ada alumni yang terhubung dengan angkatan ini
        if ($angkatan->alumni()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus angkatan yang sudah memiliki alumni!');
        }

        $namaAngkatan = $angkatan->nama_angkatan;
        $angkatan->delete();

        // Log aktivitas admin
        AdminLog::log(
            Auth::id(),
            'delete_angkatan',
            'angkatan',
            null,
            "Menghapus angkatan: {$namaAngkatan}"
        );

        return redirect()
            ->route('admin.angkatan.index')
            ->with('success', 'Angkatan berhasil dihapus!');
    }
}
