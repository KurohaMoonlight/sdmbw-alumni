<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('angkatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_angkatan'); // Contoh: "Angkatan 1"
            $table->string('tahun_ajaran'); // Contoh: "2016-2017"
            $table->enum('status', ['LULUS', 'BELUM_LULUS'])->default('BELUM_LULUS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatan');
    }
};
