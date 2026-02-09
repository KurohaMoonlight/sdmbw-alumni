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
        Schema::table('alumni', function (Blueprint $table) {
            // Tambah kolom show_no_hp setelah no_hp (jika belum ada)
            if (!Schema::hasColumn('alumni', 'show_no_hp')) {
                $table->tinyInteger('show_no_hp')->default(0)->after('no_hp');
            }

            // Tambah kolom no_wa setelah show_no_hp (jika belum ada)
            if (!Schema::hasColumn('alumni', 'no_wa')) {
                $table->string('no_wa')->nullable()->after('show_no_hp');
            }

            // Tambah kolom show_no_wa setelah no_wa (jika belum ada)
            if (!Schema::hasColumn('alumni', 'show_no_wa')) {
                $table->tinyInteger('show_no_wa')->default(0)->after('no_wa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            if (Schema::hasColumn('alumni', 'show_no_hp')) {
                $table->dropColumn('show_no_hp');
            }

            if (Schema::hasColumn('alumni', 'no_wa')) {
                $table->dropColumn('no_wa');
            }

            if (Schema::hasColumn('alumni', 'show_no_wa')) {
                $table->dropColumn('show_no_wa');
            }
        });
    }
};
