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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            // Kolom utama menggunakan admin_id
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // 'reset_password', 'verify_alumni', 'create_angkatan'
            $table->string('target_type')->nullable(); // 'alumni', 'angkatan'
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
