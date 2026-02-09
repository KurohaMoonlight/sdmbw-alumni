<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek berdasarkan role admin agar tidak duplikat
        $adminExists = User::where('role', 'admin')->exists();

        if (!$adminExists) {
            User::create([
                'username'  => 'adminalumnibw',
                // Password diisi sesuai yang kamu infokan di info command
                'password'  => Hash::make('7rD7N1£Rx,Am@!u{lol_56s{6k)$|#Swh0;H!KV&'),
                'role'      => 'admin',
                // PENTING: Admin harus otomatis aktif agar bisa login
                'is_active' => true,
                'nisn'      => '0000000000', // Formalitas untuk akun admin
            ]);

            $this->command->info('✅ Akun admin berhasil dibuat!');
            $this->command->info('   Username: adminalumnibw');
            $this->command->info('   Password: 7rD7N1£Rx,Am@!u{lol_56s{6k)$|#Swh0;H!KV&');
            $this->command->info('   Status  : Aktif (Bypass Verifikasi)');
        } else {
            $this->command->warn('⚠️ Akun admin sudah ada, skip...');
        }
    }
}
