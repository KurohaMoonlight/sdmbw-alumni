<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Services\BeritaService;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    private BeritaService $beritaService;

    public function __construct(BeritaService $beritaService)
    {
        $this->beritaService = $beritaService;
    }

    /**
     * Menampilkan daftar berita untuk halaman admin.
     * Mendukung pencarian teks dan filter berdasarkan status aktif/non-aktif.
     */
    public function index(Request $request)
    {
        $query = Berita::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $beritas = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Menyimpan data berita baru ke dalam sistem.
     * Menangani request dari form HTML biasa maupun request AJAX/JSON (misalnya dari modal).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'excerpt'     => 'nullable|string|max:300',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        try {
            $this->beritaService->createBerita($validated, $request->file('image'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan berita.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan berita.')->withInput();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil ditambahkan!',
                'redirect' => route('admin.beritas.index'),
            ]);
        }

        return back()->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Memperbarui data berita yang sudah ada.
     * Mendukung pembaruan file gambar jika file baru diunggah oleh pengguna.
     */
    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'excerpt'     => 'nullable|string|max:300',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        try {
            $this->beritaService->updateBerita($berita, $validated, $request->file('image'));
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui berita.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat memperbarui berita.')->withInput();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diperbarui!',
                'redirect' => route('admin.beritas.index'),
            ]);
        }

        return back()->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Mengubah status unggulan (featured) pada sebuah berita.
     * Berita unggulan akan mendapatkan penanda khusus saat ditampilkan di halaman utama.
     */
    public function toggleFeatured(Request $request, Berita $berita)
    {
        $isFeatured = $this->beritaService->toggleFeatured($berita);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'is_featured' => $isFeatured,
                'message'     => $isFeatured
                    ? 'Berita berhasil disematkan sebagai unggulan!'
                    : 'Berita dihapus dari unggulan.',
            ]);
        }

        return back()->with('success', 'Status unggulan berhasil diperbarui');
    }

    /**
     * Menghapus berita secara permanen dari sistem.
     * File gambar terkait akan otomatis dibersihkan oleh BeritaService.
     */
    public function destroy(Berita $berita)
    {
        try {
            $this->beritaService->deleteBerita($berita);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus berita.');
        }

        return back()->with('success', 'Berita berhasil dihapus');
    }
}
