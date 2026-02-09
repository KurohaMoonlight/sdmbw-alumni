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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nisn', 10)->unique();
            $table->string('nama_lengkap');
            $table->foreignId('angkatan_id')->constrained('angkatan')->onDelete('restrict');
            $table->year('tahun_lulus'); // Tahun lulus sekolah asal
            $table->text('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email')->nullable(); // Email alternatif selain login
            $table->text('harapan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->boolean('is_profile_complete')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
