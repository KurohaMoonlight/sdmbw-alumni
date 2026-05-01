# SDMBW Alumni Dashboard 🎓

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**SDMBW Alumni Dashboard** adalah Sistem Informasi Manajemen Alumni berbasis web yang dikembangkan untuk **SD Muhammadiyah Birrul Walidain Kudus**. Sistem ini dirancang untuk mendigitalisasi pendataan alumni, memantau *tracer study*, serta memfasilitasi komunikasi antara sekolah dan lulusannya.

---

## 🏛️ Arsitektur Sistem
Sistem ini dibangun menggunakan **Hybrid Architecture**:
- **Server Side:** Laravel 12 (Blade Engine untuk struktur & routing).
- **Client Side:** Vue 3 (Composition API) untuk komponen interaktif dan reaktif.
- **Styling:** Tailwind CSS 4 (Modern Utility-First) dikombinasikan dengan Bootstrap 5 untuk komponen admin tertentu.

---

## ✨ Fitur Unggulan

### 👤 Modul Alumni
- **Registrasi & Onboarding:** Pendaftaran mandiri menggunakan NISN.
- **Manajemen Profil Terpadu:** Update data pendidikan lanjutan, riwayat pekerjaan, dan kontak secara mandiri.
- **Testimoni Alumni:** Memberikan feedback/kesan pesan yang akan ditampilkan di halaman publik setelah diverifikasi.
- **Direktori Unified:** Melihat daftar alumni lain dengan fitur pencarian dan filter angkatan.

### 🛡️ Modul Administrator
- **Dashboard Statistik:** Visualisasi data alumni per angkatan menggunakan grafik interaktif (Chart.js).
- **Manajemen Alumni:**
  - **Verifikasi Akun:** Memvalidasi pendaftar baru sebelum masuk ke direktori.
  - **Import/Export Excel:** Migrasi data massal menggunakan template Excel.
  - **Reset Password:** Mengelola keamanan akun alumni via NISN.
- **Tracer Study & Laporan:** Pembuatan laporan berkala berdasarkan data angkatan.
- **CMS (Content Management System):** Mengelola FAQ dan testimoni yang tampil di halaman depan.
- **Activity Logs:** Audit trail untuk mencatat setiap aktivitas penting di sistem (Keamanan & Monitoring).

---

## 🛠️ Tech Stack

| Layer | Teknologi |
| :--- | :--- |
| **Backend** | Laravel 12, PHP 8.2+ |
| **Frontend** | Vue 3, Blade, Tailwind CSS 4, Bootstrap 5 |
| **Database** | MySQL / MariaDB |
| **Tools** | Vite, Composer, NPM |
| **Libraries** | Chart.js, Axios, Laravel Excel |

---

## 🚀 Panduan Instalasi

### Prasyarat
- PHP >= 8.2
- Composer & Node.js (LTS)
- MySQL Server

### Langkah-langkah
1. **Clone & Install Dependencies**
   ```bash
   git clone https://github.com/Rakawiratama/sdmbw-alumni.git
   cd sdmbw-alumni
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Konfigurasi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.*

3. **Database Migration**
   ```bash
   php artisan migrate --seed
   ```
   *Perintah ini akan membuat tabel dan akun admin default.*

4. **Running the App**
   Jalankan server Laravel dan compiler Vite secara bersamaan:
   ```bash
   # Terminal 1
   php artisan serve
   
   # Terminal 2
   npm run dev
   ```

---

## 🔒 Keamanan Sistem
- **RBAC (Role Based Access Control):** Pemisahan akses ketat antara admin dan alumni.
- **Throttle Login:** Pembatasan percobaan login untuk mencegah serangan *brute-force*.
- **CSRF & XSS Protection:** Proteksi bawaan Laravel terhadap serangan web umum.
- **Secure Hashing:** Menggunakan algoritma Argon2/Bcrypt untuk perlindungan password.

---

## 🗺️ Roadmap Pengembangan
- [x] Integrasi Vue 3 & Tailwind 4
- [x] Sistem Import/Export Excel
- [x] Log Aktivitas & Audit
- [ ] Integrasi Notifikasi WhatsApp (Fonnte/Wablas)
- [ ] Export Laporan ke format PDF (DomPDF)
- [ ] Dashboard Alumni yang lebih interaktif

---

## 📝 Lisensi & Kontribusi
Proyek ini bersifat *open-source* di bawah lisensi MIT. Kontribusi berupa *bug report* atau *pull request* sangat kami hargai.

**Maintainer:** [Rakawiratama](https://github.com/Rakawiratama)
