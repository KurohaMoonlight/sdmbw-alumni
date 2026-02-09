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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate dan reset auto increment
        DB::table('angkatan')->truncate();

        // Insert ulang data dengan ID yang urut
        $data = [
            ['nama_angkatan' => 'Angkatan 1', 'tahun_ajaran' => '2016-2017', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 2', 'tahun_ajaran' => '2017-2018', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 3', 'tahun_ajaran' => '2018-2019', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 4', 'tahun_ajaran' => '2019-2020', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 5', 'tahun_ajaran' => '2020-2021', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 6', 'tahun_ajaran' => '2021-2022', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 7', 'tahun_ajaran' => '2022-2023', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 8', 'tahun_ajaran' => '2023-2024', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 9', 'tahun_ajaran' => '2024-2025', 'status' => 'LULUS'],
            ['nama_angkatan' => 'Angkatan 10', 'tahun_ajaran' => '2025-2026', 'status' => 'AKTIF'],
        ];

        foreach ($data as $item) {
            DB::table('angkatan')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('angkatan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
