<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah semua status BELUM_LULUS menjadi AKTIF
        DB::table('angkatan')
            ->where('status', 'BELUM_LULUS')
            ->update(['status' => 'AKTIF']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika di-rollback, ubah kembali dari AKTIF ke BELUM_LULUS
        DB::table('angkatan')
            ->where('status', 'AKTIF')
            ->update(['status' => 'BELUM_LULUS']);
    }
};
