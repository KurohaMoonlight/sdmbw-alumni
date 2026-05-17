<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru ke database secara anonim.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string' => 'Format pesan tidak valid.',
            'message.max' => 'Pesan terlalu panjang (maksimal 2000 karakter).'
        ]);

        // 2. Generate alias anonim (Contoh hasil: Anonymous#8492)
        // mt_rand(1000, 9999) digunakan untuk membuat 4 digit angka acak
        $randomAlias = 'Anonymous#' . mt_rand(1000, 9999);

        // 3. Simpan data ke database melalui Model
        Comment::create([
            'alias'   => $randomAlias,
            'message' => $request->message,
        ]);

        // 4. Redirect kembali ke halaman sebelumnya dengan session flash 'success'
        // Session ini akan memicu script Toast Notification yang ada di file HTML/Blade Anda
        return redirect()->back()->with('success', 'Pesan anonim Anda berhasil dikirim!');
    }
}