<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class RiwayatController extends Controller
{
    /**
     * Simpan riwayat pendidikan alumni baru
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storePendidikan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_institusi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:50',
            'jurusan' => 'required|string|max:255',
            'tahun_masuk' => 'required|digits:4|numeric',
            'is_ongoing' => 'required|boolean',
            'tahun_lulus' => 'required_if:is_ongoing,0|nullable|digits:4|numeric',
        ], [
            'nama_institusi.required' => 'Nama institusi wajib diisi',
            'jenjang.required' => 'Jenjang pendidikan wajib dipilih',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'tahun_masuk.digits' => 'Tahun masuk harus 4 digit',
            'tahun_lulus.required_if' => 'Tahun lulus wajib diisi jika sudah lulus',
            'tahun_lulus.digits' => 'Tahun lulus harus 4 digit',
        ]);

        $alumniId = Auth::user()->alumni->id;

        Pendidikan::create([
            'alumni_id' => $alumniId,
            'nama_institusi' => $validated['nama_institusi'],
            'jenjang' => $validated['jenjang'],
            'jurusan' => $validated['jurusan'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'tahun_lulus' => $validated['is_ongoing'] ? null : $validated['tahun_lulus'],
            'is_ongoing' => $validated['is_ongoing'],
        ]);

        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan!');
    }

    /**
     * Simpan riwayat pekerjaan alumni baru
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storePekerjaan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4|numeric',
            'tahun_selesai' => 'nullable|digits:4|numeric',
            'alamat_perusahaan' => 'nullable|string|max:500',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'tahun_mulai.required' => 'Tahun mulai wajib diisi',
            'tahun_mulai.digits' => 'Tahun mulai harus 4 digit',
            'tahun_selesai.digits' => 'Tahun selesai harus 4 digit',
        ]);

        $alumniId = Auth::user()->alumni->id;

        Pekerjaan::create(array_merge($validated, ['alumni_id' => $alumniId]));

        return back()->with('success', 'Riwayat pekerjaan berhasil ditambahkan!');
    }

    /**
     * Hapus riwayat pendidikan alumni
     *
     * @param Pendidikan $pendidikan
     * @return RedirectResponse
     */
    public function destroyPendidikan(Pendidikan $pendidikan): RedirectResponse
    {
        $this->authorizeOwner($pendidikan->alumni_id);

        $pendidikan->delete();

        return back()->with('success', 'Data pendidikan berhasil dihapus.');
    }

    /**
     * Hapus riwayat pekerjaan alumni
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroyPekerjaan($id): RedirectResponse
    {
        // Cari data pekerjaan berdasarkan ID
        $pekerjaan = Pekerjaan::findOrFail($id);

        // Validasi ownership - pastikan yang menghapus adalah pemilik datanya
        $this->authorizeOwner($pekerjaan->alumni_id);

        $pekerjaan->delete();

        return back()->with('success', 'Data pekerjaan berhasil dihapus.');
    }

    /**
     * Validasi bahwa user yang login adalah pemilik data alumni
     *
     * @param int $alumniId
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function authorizeOwner($alumniId): void
    {
        if (Auth::user()->alumni->id !== $alumniId) {
            abort(403, 'Aksi tidak diizinkan.');
        }
    }
}
