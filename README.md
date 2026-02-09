SDMBW Alumni Dashboard 🎓

SDMBW Alumni Dashboard adalah aplikasi manajemen alumni berbasis web yang dirancang khusus untuk SD Muhammadiyah Birrul Walidain Kudus. Sistem ini berfungsi sebagai pusat pendataan terintegrasi guna mendukung administrasi sekolah, pelaporan, serta menjalin komunikasi jangka panjang dengan para alumni.

📌 Deskripsi Singkat

Aplikasi ini dikembangkan untuk menggantikan sistem pendataan alumni konvensional (manual). Dengan sistem ini, alumni dapat mengelola data pribadi mereka secara mandiri, sementara pihak sekolah memiliki kendali penuh untuk memverifikasi, memantau, dan menyusun laporan data secara akurat dan aman.

🎯 Tujuan Sistem

Database Terstruktur: Menyediakan basis data alumni yang rapi dan berkelanjutan.

Validitas Data: Memastikan data yang masuk valid melalui proses verifikasi admin.

Efisiensi Administrasi: Mempermudah pengelolaan dan pembuatan laporan berkala.

Jembatan Komunikasi: Menjadi media penghubung antara pihak sekolah dan alumni.

🛠️ Tech Stack

Framework: Laravel 11 / 12

Bahasa Pemrograman: PHP 8.2 / 8.3

Database: MySQL / MariaDB

Frontend: Bootstrap 5 (Responsive UI)

Authentication: Laravel Auth (Session based)

Dependency Manager: Composer

✨ Fitur Utama

🔐 Autentikasi: Registrasi dan login alumni berbasis NISN.

📊 Dashboard Alumni: Informasi status akun (Menunggu Verifikasi/Terverifikasi) dan kelengkapan profil.

👤 Manajemen Profil: Alumni dapat memperbarui data pendidikan lanjutan dan kontak secara mandiri.

📂 Direktori Alumni: Daftar alumni yang telah terverifikasi untuk akses internal maupun publik.

🛡️ Admin Panel: Verifikasi akun baru, manajemen data angkatan/kelas, dan pengelolaan user.

📈 Statistik & Laporan: Visualisasi data alumni dan ringkasan statistik per angkatan.

📝 Activity Logging: Pencatatan aktivitas sistem untuk kebutuhan audit dan keamanan.

📱 Responsive Design: Tampilan modern yang optimal di perangkat desktop maupun smartphone.

🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

Clone Repository

git clone [https://github.com/Rakawiratama/sdmbw-alumni.git](https://github.com/Rakawiratama/sdmbw-alumni.git)
cd sdmbw-alumni


Install Dependencies

composer install


Konfigurasi Environment
Salin file .env.example menjadi .env dan generate application key.

cp .env.example .env
php artisan key:generate


Pengaturan Database
Buka file .env dan sesuaikan kredensial database Anda:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sdmbw_alumni
DB_USERNAME=root
DB_PASSWORD=


Migrasi dan Seeding
Jalankan migrasi untuk membuat struktur tabel beserta data awal (admin default).

php artisan migrate --seed


Jalankan Server

php artisan serve


Akses aplikasi di: http://127.0.0.1:8000

🔄 Alur Penggunaan

Registrasi: Alumni mendaftar menggunakan NISN dan email.

Lengkapi Data: Alumni mengisi profil detail (pendidikan, pekerjaan, dll).

Verifikasi: Admin memeriksa validitas data alumni di panel admin.

Akses Penuh: Setelah diverifikasi, alumni masuk ke direktori dan fitur lainnya.

🔒 Keamanan & Sistem

Role-Based Access Control (RBAC): Pemisahan hak akses antara Admin dan Alumni.

Password Hashing: Keamanan password menggunakan algoritma Bcrypt/Argon2.

CSRF Protection: Perlindungan terhadap serangan Cross-Site Request Forgery.

Input Validation: Validasi ketat pada sisi server untuk semua input pengguna.

🗺️ Roadmap Pengembangan

[ ] Export data alumni ke format PDF dan Excel.

[ ] Integrasi Notifikasi via Email dan WhatsApp.

[ ] Blast pengumuman sekolah untuk alumni.

[ ] Dashboard statistik interaktif (Chart.js).

[ ] API Endpoints untuk integrasi sistem eksternal.

📝 Catatan: Project ini dikembangkan untuk kepentingan pendidikan dan administrasi sekolah. Kontribusi dalam bentuk bug report atau pull request sangat diapresiasi.
