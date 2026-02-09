<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            // Hapus kolom no_wa
            if (Schema::hasColumn('alumni', 'no_wa')) {
                $table->dropColumn('no_wa');
            }

            // Hapus kolom show_no_wa
            if (Schema::hasColumn('alumni', 'show_no_wa')) {
                $table->dropColumn('show_no_wa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('no_wa')->nullable()->after('show_no_hp');
            $table->tinyInteger('show_no_wa')->default(0)->after('no_wa');
        });
    }
};
