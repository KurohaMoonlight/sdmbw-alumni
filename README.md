# SDMBW Alumni Dashboard 🎓

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Status: Private](https://img.shields.io/badge/Status-Private_Project-red?style=flat-square)](https://github.com/Rakawiratama/sdmbw-alumni)

**SDMBW Alumni Dashboard** adalah Sistem Informasi Manajemen Alumni & *Tracer Study* terpadu yang dikembangkan khusus untuk **SD Muhammadiyah Birrul Walidain Kudus**. Sistem ini bersifat **Private/Proprietary** dan hanya ditujukan untuk penggunaan internal sekolah.

---

## 🏛️ Arsitektur & Teknologi

Sistem ini menggunakan pendekatan **Hybrid Modern Architecture**:
- **Core Engine:** Laravel 12 dengan PHP 8.2+.
- **Frontend Hybrid:** Blade Engine (Layout & SEO) + Vue.js 3 (Interaktivitas).
- **Styling:** Tailwind CSS 4 (Utility-First).
- **Bundler:** Vite.

---

## ✨ Fitur Utama Sistem

- **Tracer Study:** Pelacakan riwayat pendidikan lanjutan dan pekerjaan alumni.
- **Audit Logs:** Pencatatan setiap aksi admin untuk keamanan data.
- **Smart Management:** Import/Export data alumni massal menggunakan Excel.
- **Verification System:** Alur pendaftaran alumni melalui validasi admin.
- **CMS Admin:** Pengelolaan FAQ dan Testimoni landing page.

---

## 🚀 Panduan Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan pengembangan (*local development*):

### 1. Prasyarat Sistem
- **PHP** >= 8.2
- **Composer** (Dependency Manager PHP)
- **Node.js** (LTS) & **NPM**
- **MySQL/MariaDB Server**
- **Web Server** (Apache/Nginx) atau menggunakan `php artisan serve`

### 2. Langkah-langkah Instalasi

#### A. Clone Repository
```bash
git clone https://github.com/Rakawiratama/sdmbw-alumni.git
cd sdmbw-alumni
```

#### B. Instalasi Dependency
Instal pustaka yang dibutuhkan oleh Laravel (Backend) dan Vue (Frontend):
```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install
```

#### C. Konfigurasi Environment
1. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
2. Generate Application Key:
   ```bash
   php artisan key:generate
   ```
3. Buka file `.env` dan sesuaikan pengaturan database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sdmbw_alumni
   DB_USERNAME=root
   DB_PASSWORD=
   ```

#### D. Setup Database & Storage
1. **Buat Database:** Buat database baru di MySQL dengan nama `sdmbw_alumni` (atau sesuai yang Anda tulis di `.env`).
2. **Migrasi & Seed:** Jalankan migrasi untuk membuat tabel dan mengisi data awal (admin):
   ```bash
   php artisan migrate --seed
   ```
3. **Symbolic Link:** Hubungkan folder storage ke folder public agar foto alumni bisa diakses:
   ```bash
   php artisan storage:link
   ```

#### E. Kompilasi Aset & Menjalankan Server
Jalankan compiler Vite untuk memproses CSS/JS dan jalankan server Laravel:
```bash
# Terminal 1: Kompilasi Aset (Dev Mode)
npm run dev

# Terminal 2: Server Laravel
php artisan serve
```

---

## 🏗️ Struktur Proyek Penting

| Path | Keterangan |
| :--- | :--- |
| `app/Models/` | Definisi data (Alumni, Pendidikan, Pekerjaan, AdminLog). |
| `resources/views/` | File tampilan Blade (Hybrid Shell). |
| `resources/js/` | Komponen Vue 3 dan logika frontend. |
| `routes/web.php` | Definisi rute aplikasi (Public, Admin, Alumni). |
| `storage/app/public/` | Lokasi penyimpanan foto alumni dan file unggahan. |

---

## 🔒 Status & Lisensi

**PROYEK INI ADALAH PRIVATE.**
Seluruh kode sumber dan aset di dalam repositori ini adalah milik **SD Muhammadiyah Birrul Walidain Kudus** dan pengembangnya. Dilarang mendistribusikan, menyalin, atau menggunakan kode ini tanpa izin tertulis dari pihak terkait.

- **Status:** Proprietary / Private
- **Developer:** [Rakawiratama](https://github.com/Rakawiratama)
- **Copyright:** © 2026 SD Muhammadiyah Birrul Walidain Kudus. All rights reserved.

---

## 🛡️ Keamanan Data
Sistem ini dilengkapi dengan perlindungan data tingkat tinggi, termasuk:
- Enkripsi password menggunakan Argon2.
- Proteksi terhadap SQL Injection, CSRF, dan XSS.
- Audit Trail melalui `AdminLog` untuk memantau integritas data.
