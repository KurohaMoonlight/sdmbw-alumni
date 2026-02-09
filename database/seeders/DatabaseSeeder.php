<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder
        $this->call([
            AngkatanSeeder::class,
            AdminSeeder::class,
        ]);

        $this->command->info('🎉 Semua seeder berhasil dijalankan!');
    }
}
