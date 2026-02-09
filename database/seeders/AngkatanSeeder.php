<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Angkatan;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data angkatan 1-10
        $angkatanData = [
            [
                'nama_angkatan' => 'Angkatan 1',
                'tahun_ajaran' => '2016-2017',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 2',
                'tahun_ajaran' => '2017-2018',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 3',
                'tahun_ajaran' => '2018-2019',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 4',
                'tahun_ajaran' => '2019-2020',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 5',
                'tahun_ajaran' => '2020-2021',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 6',
                'tahun_ajaran' => '2021-2022',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 7',
                'tahun_ajaran' => '2022-2023',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 8',
                'tahun_ajaran' => '2023-2024',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 9',
                'tahun_ajaran' => '2024-2025',
                'status' => 'LULUS',
            ],
            [
                'nama_angkatan' => 'Angkatan 10',
                'tahun_ajaran' => '2025-2026',
                'status' => 'AKTIF',
            ],
        ];

        // Insert / Update ke database (ANTI DOBEL)
        foreach ($angkatanData as $data) {
            Angkatan::updateOrCreate(
                ['nama_angkatan' => $data['nama_angkatan']],
                $data
            );
        }

        // Tampilkan pesan
        $this->command->info('✅ Data angkatan berhasil ditambahkan / diperbarui!');
    }
}
