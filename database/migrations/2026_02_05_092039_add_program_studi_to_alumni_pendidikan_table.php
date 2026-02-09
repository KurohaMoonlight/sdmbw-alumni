<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan kolom program_studi ke tabel alumni_pendidikan
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('alumni_pendidikan', function (Blueprint $table) {
            // Cek apakah kolom sudah ada, jika belum maka tambahkan
            if (!Schema::hasColumn('alumni_pendidikan', 'program_studi')) {
                $table->string('program_studi', 255)
                    ->nullable()
                    ->after('jenjang')
                    ->comment('Program studi / Jurusan (khusus Perguruan Tinggi)');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('alumni_pendidikan', function (Blueprint $table) {
            // Hapus kolom program_studi jika ada
            if (Schema::hasColumn('alumni_pendidikan', 'program_studi')) {
                $table->dropColumn('program_studi');
            }
        });
    }
};
