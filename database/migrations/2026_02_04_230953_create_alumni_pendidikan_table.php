<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('jenjang')->comment('SMP/MTS, SMA/MA/SMK, Perguruan Tinggi');
            $table->string('nama_instansi')->comment('Nama sekolah/kampus');
            $table->string('program_studi')->nullable()->comment('Program studi / Jurusan (khusus Perguruan Tinggi)');
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->tinyInteger('is_ongoing')->default(0)->comment('1 = masih belajar, 0 = sudah lulus');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_pendidikan');
    }
};
