<?php

namespace App\Services;

use App\Models\Berita;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class BeritaService
{
    /**
     * Membuat data Berita baru.
     * Termasuk memproses upload gambar jika ada.
     */
    public function createBerita(array $data, ?UploadedFile $imageFile): Berita
    {
        $uploadedPath = null;

        if ($imageFile) {
            $uploadedPath = $this->uploadImage($imageFile);
            $data['image'] = $uploadedPath;
        }

        DB::beginTransaction();
        try {
            $berita = Berita::create($data);
            DB::commit();
            
            Cache::forget('landing_beritas');
            return $berita;
        } catch (Exception $e) {
            DB::rollBack();
            // Membersihkan file yatim (orphan file) jika database gagal menyimpan
            $this->deleteUploadedFile($uploadedPath);
            Log::error('BeritaService@createBerita failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui data Berita yang sudah ada.
     * Mengganti gambar lama jika ada gambar baru yang diunggah.
     */
    public function updateBerita(Berita $berita, array $data, ?UploadedFile $imageFile): Berita
    {
        $uploadedPath = null;
        $oldImage     = $berita->image;

        if ($imageFile) {
            $uploadedPath = $this->uploadImage($imageFile);
            $data['image'] = $uploadedPath;
        }

        DB::beginTransaction();
        try {
            $berita->update($data);
            DB::commit();

            // Menghapus gambar lama hanya jika update database berhasil
            if ($uploadedPath && $oldImage) {
                $this->deleteUploadedFile($oldImage);
            }

            Cache::forget('landing_beritas');
            return $berita;
        } catch (Exception $e) {
            DB::rollBack();
            // Membersihkan file gambar baru yang terlanjur diunggah jika gagal
            $this->deleteUploadedFile($uploadedPath);
            Log::error('BeritaService@updateBerita failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus data Berita beserta file gambarnya.
     */
    public function deleteBerita(Berita $berita): void
    {
        $imagePath = $berita->image;

        DB::beginTransaction();
        try {
            $berita->delete();
            DB::commit();

            // Menghapus gambar fisik setelah data berhasil dihapus dari database
            $this->deleteUploadedFile($imagePath);
            Cache::forget('landing_beritas');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('BeritaService@deleteBerita failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengubah status unggulan (featured) dari sebuah Berita.
     */
    public function toggleFeatured(Berita $berita): bool
    {
        $berita->is_featured = !$berita->is_featured;
        $berita->save();

        Cache::forget('landing_beritas');

        return $berita->is_featured;
    }

    /**
     * Mengunggah dan mengompresi gambar Berita menjadi format WebP.
     */
    private function uploadImage(UploadedFile $file): string
    {
        if (!Storage::disk('public')->exists('berita')) {
            Storage::disk('public')->makeDirectory('berita');
        }

        $filename = 'berita_' . time() . '_' . uniqid() . '.webp';
        $path     = 'berita/' . $filename;

        $manager = new ImageManager(new Driver());
        $image   = $manager->decode($file->getPathname());
        $image->scale(width: 1000);
        $image->encode(new \Intervention\Image\Encoders\WebpEncoder(80))
              ->save(Storage::disk('public')->path($path));

        return $path;
    }

    /**
     * Menghapus file fisik dari storage public jika file tersebut ada.
     */
    private function deleteUploadedFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
