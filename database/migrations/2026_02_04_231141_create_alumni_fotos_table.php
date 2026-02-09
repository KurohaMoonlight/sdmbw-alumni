<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('alumni_fotos', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel alumni
            $table->foreignId('alumni_id')
                ->constrained('alumni')
                ->onDelete('cascade');

            // Kolom data foto
            $table->string('path_file', 500);
            $table->string('kategori', 50)->default('profil');
            $table->string('deskripsi', 255)->nullable();
            $table->boolean('is_main')->default(false);

            // Timestamps
            $table->timestamps();

            // Index untuk performa query
            $table->index('alumni_id');
            $table->index('is_main');
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_fotos');
    }
};
