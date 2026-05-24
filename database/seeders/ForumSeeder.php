<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forums = [
            [
                'name' => 'Diskusi Umum',
                'slug' => 'diskusi-umum',
                'description' => 'Tempat berkumpul dan berdiskusi seputar hal-hal umum bagi seluruh alumni.',
                'icon' => '💬',
                'order' => 1,
            ],
            [
                'name' => 'Berbagi Pengalaman',
                'slug' => 'berbagi-pengalaman',
                'description' => 'Bagikan kisah sukses, inspirasi, perjalanan hidup, atau pengalaman berharga Anda kepada sesama alumni.',
                'icon' => '🌟',
                'order' => 2,
            ],
            [
                'name' => 'Karir & Pekerjaan',
                'slug' => 'karir-dan-pekerjaan',
                'description' => 'Informasi lowongan kerja, tips wawancara, pengembangan karir, dan peluang bisnis dari sesama alumni.',
                'icon' => '💼',
                'order' => 3,
            ],
            [
                'name' => 'Pendidikan & Beasiswa',
                'slug' => 'pendidikan-dan-beasiswa',
                'description' => 'Informasi seputar jenjang pendidikan lanjut, tips masuk perguruan tinggi, dan peluang beasiswa.',
                'icon' => '🎓',
                'order' => 4,
            ],
            [
                'name' => 'Reuni & Nostalgia',
                'slug' => 'reuni-dan-nostalgia',
                'description' => 'Mengenang masa-masa indah di sekolah, berbagi foto lama, dan merencanakan acara temu kangen.',
                'icon' => '📸',
                'order' => 5,
            ],
            [
                'name' => 'Tanya Jawab & Bantuan',
                'slug' => 'tanya-jawab-dan-bantuan',
                'description' => 'Ajukan pertanyaan atau minta bantuan dari sesama alumni terkait berbagai hal.',
                'icon' => '🙋',
                'order' => 6,
            ],
        ];

        foreach ($forums as $forum) {
            \App\Models\Forum::create($forum);
        }
    }
}
