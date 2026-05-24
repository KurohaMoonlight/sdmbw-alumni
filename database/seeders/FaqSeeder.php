<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apa itu Portal Alumni SD Muhammadiyah Birrul Walidain (SDMBW) Kudus?',
                'answer'   => 'Portal Alumni SDMBW adalah platform digital resmi yang berfungsi sebagai wadah silaturahmi, berbagi informasi, dan mempererat jejaring antar alumni SD Muhammadiyah Birrul Walidain Kudus dari seluruh angkatan. Di sini Anda dapat memperbarui profil, berinteraksi lewat forum diskusi, dan tetap terhubung dengan almamater.',
            ],
            [
                'question' => 'Bagaimana cara mendaftar atau membuat akun?',
                'answer'   => 'Akun alumni tidak dibuat sendiri secara mandiri. Akun didaftarkan oleh pihak Admin Sekolah berdasarkan data alumni yang terverifikasi. Jika Anda alumni SDMBW dan belum memiliki akun, silakan hubungi Admin melalui tombol WhatsApp yang tersedia di halaman Login.',
            ],
            [
                'question' => 'Saya lupa password, bagaimana cara mengatasinya?',
                'answer'   => 'Karena sistem tidak menggunakan email untuk pemulihan password, silakan hubungi Admin Sekolah melalui WhatsApp. Admin akan melakukan reset password dan memberikan password sementara baru kepada Anda. Nomor WhatsApp Admin tersedia di halaman Login.',
            ],
            [
                'question' => 'Apakah data profil saya akan terlihat oleh publik?',
                'answer'   => 'Data yang tampil di Direktori Publik hanya terbatas pada nama, angkatan, dan kota domisili alumni yang telah diverifikasi. Data sensitif seperti nomor HP dan NISN hanya terlihat oleh Admin Sekolah dan tidak pernah dibagikan ke publik.',
            ],
            [
                'question' => 'Apa yang dimaksud dengan Status Verifikasi?',
                'answer'   => 'Status Verifikasi adalah proses pengesahan data alumni oleh Admin Sekolah untuk memastikan keaslian data. Terdapat 3 status: "Menunggu" (data baru, belum ditinjau), "Terverifikasi" (data telah sah dan profil aktif di direktori publik), dan "Ditolak" (data perlu diperbaiki karena ada ketidaksesuaian).',
            ],
            [
                'question' => 'Bagaimana cara memperbarui data pekerjaan dan pendidikan saya?',
                'answer'   => 'Setelah login, masuk ke menu "Edit Profil" di Dashboard Alumni Anda. Di sana terdapat tab khusus untuk menambah atau memperbarui riwayat Pendidikan dan Pekerjaan. Pastikan Anda menekan tombol Simpan setelah melakukan perubahan.',
            ],
            [
                'question' => 'Apa itu Forum Diskusi dan siapa yang bisa menggunakannya?',
                'answer'   => 'Forum Diskusi adalah ruang percakapan online khusus yang hanya dapat diakses oleh alumni yang telah login. Forum ini dibagi dalam beberapa kategori topik seperti Diskusi Umum, Karir & Pekerjaan, dan Reuni. Anda bisa membuat topik baru atau membalas diskusi yang sudah ada.',
            ],
            [
                'question' => 'Apakah portal ini bisa diakses dari smartphone?',
                'answer'   => 'Ya! Portal Alumni SDMBW sepenuhnya responsif dan dapat diakses dari browser di smartphone, tablet, maupun komputer. Lebih dari itu, website ini mendukung fitur Progressive Web App (PWA) yang memungkinkan Anda menginstalnya di layar utama smartphone seperti aplikasi biasa tanpa perlu mengunduhnya dari Play Store.',
            ],
            [
                'question' => 'Bagaimana cara menghubungi pihak sekolah atau admin?',
                'answer'   => 'Anda dapat menghubungi kami melalui WhatsApp yang tersedia di halaman Login. Atau kunjungi langsung sekolah kami di Jl. HOS Cokroaminoto, Ds. Mlatinorowito, Gg. 10, RT 03 RW 09, Kab. Kudus, Jawa Tengah. Telepon: (0812) 48076886 / (0291) 4248302.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
