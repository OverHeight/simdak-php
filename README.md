# SIMDAK — Sistem Informasi Manajemen Data Karyawan

### Tugas Praktikum Pengembangan Teknologi Internet (PTI) — Pertemuan 2 & 3

**Universitas Komputer Indonesia (UNIKOM)**

---

## 📋 Identitas Tugas & Mahasiswa

| Komponen              | Keterangan                                                  |
| :-------------------- | :---------------------------------------------------------- |
| **Mata Kuliah**       | Pengembangan Teknologi Internet (PTI)                       |
| **Topik Tugas**       | Pengelolaan Data & Keamanan Web Berbasis PHP Native & MySQL |
| **Nama Mahasiswa**    | Sultan Fadhila Al-Haq                                       |
| **NIM**               | 10124173                                                    |
| **Program Studi**     | Teknik Informatika                                          |
| **Dosen Pengampu**    | Donny Reza, M.Kom                                           |
| **Repository GitHub** | https://github.com/OverHeight/simdak-php                    |

---

## 🎯 Ringkasan Proyek

Aplikasi **SIMDAK** dibangun secara murni menggunakan **PHP Native** dan basis data relasional **MySQL/MariaDB** tanpa mengandalkan framework pihak ketiga (tanpa Laravel/CI/Bootstrap/Tailwind).

Proyek ini dirancang untuk memenuhi seluruh kriteria tugas pertemuan 2 dan 3, dengan fokus pada:

1. **Arsitektur Bersih & Modular:** Pemisahan _Business Logic_, _Database Layer_, dan _Reusable UI Components_ yang terinspirasi dari standar modern.
2. **Kepatuhan Spesifikasi Dosen:** Disediakan berkas _bridge_ di root (`config.php`, `auth.php`, `language.php`, `style.css`, `database.sql`) untuk memastikan kepatuhan penuh terhadap struktur konvensional tugas sekaligus mendukung struktur modular di folder `src/`.
3. **Keamanan Data Berlapis:** Mengimplementasikan mitigasi terhadap ancaman standar web (SQL Injection, XSS, CSRF, dan Session Fixation).
4. **Konvensi Basis Data:** Menerapkan penamaan kolom _camelCase_ (`createdAt`, `updatedAt`, `employmentType`) sesuai arahan teknis.

---

## 📊 Pemetaan Rubrik Evaluasi Tugas

Tabel berikut disiapkan untuk mempermudah Bapak/Ibu Dosen dalam mengevaluasi pemenuhan kriteria teknis pada kode sumber:

| Kriteria / Fitur                  | Berkas Sumber Implementasi                                      | Deskripsi Teknis                                                                                                                                                                                                                                                                                              |
| :-------------------------------- | :-------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **1. Autentikasi Pengguna**       | `login.php`, `register.php`, `logout.php`, `src/lib/auth.php`   | • Login dengan verifikasi hash `password_verify()`.<br>• Registrasi akun baru dengan validasi panjang dan konfirmasi password.<br>• Mitigasi Session Fixation via `session_regenerate_id(true)`.<br>• Route guard (`requireAuth()` & `requireGuest()`).                                                       |
| **2. Operasi CRUD Karyawan**      | `create.php`, `index.php`, `edit.php`, `delete.php`             | • **Create:** Form tambah data dengan validasi server-side lengkap.<br>• **Read:** Tabel direktori data dengan pagination counter & badge.<br>• **Update:** Edit data berdasarkan ID valid, penanganan tabrakan email unik.<br>• **Delete:** Wajib metode **POST** dengan modal konfirmasi dan proteksi CSRF. |
| **3. Keamanan SQL Injection**     | `src/config/database.php`, seluruh file query                   | • 100% menggunakan **PDO Prepared Statements** dengan parameter binding.<br>• `PDO::ATTR_EMULATE_PREPARES` diset `false` untuk keamanan native database engine.                                                                                                                                               |
| **4. Keamanan XSS**               | `src/lib/helpers.php` (`e()`), seluruh view                     | • Sanitasi output menggunakan `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.                                                                                                                                                                                                                                  |
| **5. Keamanan CSRF**              | `src/lib/csrf.php`                                              | • Pembangkitan token kriptografis acak berbasis session.<br>• Validasi ketat token pada seluruh form perubahan data (POST).                                                                                                                                                                                   |
| **6. Dukungan Dua Bahasa (i18n)** | `src/locales/` (`id.php`, `en.php`, `i18n.php`), `language.php` | • Mendukung Bahasa Indonesia & English.<br>• State bahasa persisten dalam `$_SESSION`.<br>• Tombol toggle bahasa di sidebar & form auth.                                                                                                                                                                      |
| **7. Nilai Tambah (Usability)**   | `index.php`, `assets/js/app.js`                                 | • Fitur pencarian instan (Nama, Email, Posisi).<br>• Multi-filter direktori berdasarkan Departemen dan Status.<br>• Shortcut keyboard `/` untuk fokus cepat ke search bar.<br>• Avatar dinamis berbasis inisial nama.                                                                                         |

---

## 🔑 Kredensial Pengujian (Demo Akun)

Untuk keperluan pemeriksaan dan penilaian, akun default administrator telah disiapkan di dalam basis data:

| Username | Password   | Hak Akses     | Status                |
| :------- | :--------- | :------------ | :-------------------- |
| `admin`  | `admin123` | Administrator | Aktif (Bcrypt Hashed) |

_(Dosen juga dapat menguji fitur registrasi akun baru melalui tautan pada halaman login)._

---

## ⚙️ Petunjuk Menjalankan Aplikasi (Setup Dosen)

### 1. Persyaratan Sistem

- PHP versi 7.4+ (Direkomendasikan PHP 8.x)
- Ekstensi PHP aktif: `pdo`, `pdo_mysql`, `mbstring`
- Server Web & Database: ServBay, XAMPP, Laragon, MAMP, atau PHP Built-in Server + MySQL

### 2. Import Basis Data

Import file DDL skema basis data ke server MySQL/MariaDB:

- Berkas: `database/database.sql` (atau `database.sql` di direktori utama).
- Nama Database: `employee_management`

```bash
# Opsi via command-line MySQL:
mysql -u root -p < database/database.sql
```

_Skema otomatis membuat tabel `users` dan `employees`, menyertakan field camelCase (`createdAt`, `updatedAt`, `employmentType`), serta menyuntikkan data awal (seed data)._

### 3. Konfigurasi Koneksi Database

Sesuaikan parameter koneksi basis data pada berkas `src/config/database.php` (atau `config.php`):

```php
$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'employee_management';
$dbUser = 'root';
$dbPass = ''; // Sesuaikan password MySQL
```

### 4. Menjalankan Server Lokal

- **Via Web Server (XAMPP / ServBay / Laragon):**
  Pindahkan folder proyek ke `htdocs` / `www` / host domain lokal, lalu buka di browser:
  `http://localhost/simdak`
- **Via PHP Built-in Server:**
  Buka terminal pada direktori proyek dan jalankan:
  ```bash
  php -S localhost:8000
  ```
  Lalu akses melalui browser: `http://localhost:8000`

---

## 🧪 Skenario Pengujian yang Disarankan untuk Dosen

Bapak/Ibu Dosen dapat memverifikasi ketahanan sistem melalui skenario berikut:

1. **Pengujian Route Protection:**
   - Coba akses langsung `http://localhost:8000/index.php` atau `create.php` tanpa login. Sistem akan otomatis me-redirect ke `login.php`.
2. **Pengujian Validasi & Anti-Duplikasi:**
   - Masuk ke menu Tambah Karyawan (`create.php`). Masukkan email yang sudah terdaftar (`budi.santoso@perusahaan.com`). Sistem akan menolak dan menampilkan pesan error duplikasi email.
3. **Pengujian Keamanan Penghapusan Data:**
   - Coba lakukan request GET manual ke `delete.php?id=1`. Sistem akan menolak dengan pesan _"Metode request tidak valid"_, karena penghapusan hanya diizinkan melalui form POST dengan token CSRF.
4. **Pengujian Input Sanitasi (Anti-XSS):**
   - Masukkan input nama dengan tag HTML/JS: `<script>alert('XSS')</script>`. Sistem akan me-render input sebagai teks murni tanpa mengeksekusi skrip.
5. **Pengujian Dual-Language:**
   - Klik tombol **EN** di pojok bawah sidebar atau header login. Seluruh teks antarmuka, status label, dan pesan notifikasi akan berubah ke Bahasa Inggris secara konsisten.

---

## 📁 Struktur Berkas Proyek

```text
simdak/
├── assets/
│   ├── css/style.css          # Desain antarmuka modern (Clean Slate & Dark Sidebar)
│   └── js/app.js              # Interaktivitas modal hapus & keyboard shortcut '/'
├── database/
│   └── database.sql           # Skema DDL tabel & data benih (seed)
├── docs/                      # Dokumentasi & Laporan Praktikum (.md & .docx)
│   ├── Laporan_Tugas_2_SIMDAK.docx
│   └── Laporan_Tugas_2_SIMDAK.md
├── src/                       # Modul Arsitektur Inti (Clean Code)
│   ├── config/database.php    # Konfigurasi koneksi PDO
│   ├── lib/
│   │   ├── auth.php           # Middleware autentikasi & session guard
│   │   ├── csrf.php           # Token generator & verifikasi CSRF
│   │   ├── flash.php          # Session flash message helper
│   │   ├── helpers.php        # Sanitizer e(), redirect, avatar generator
│   │   └── session.php        # Inisialisasi lifecycle session aman
│   ├── locales/
│   │   ├── id.php             # Kamus bahasa Indonesia
│   │   ├── en.php             # Kamus bahasa Inggris
│   │   └── i18n.php           # Engine lokalisasi bahasa
│   └── components/
│       ├── layout-header.php  # Wrapper HTML head & navigasi
│       ├── layout-footer.php  # Wrapper HTML footer & scripts
│       ├── sidebar.php        # Dark sidebar navigasi & switcher bahasa
│       ├── alert.php          # Komponen flash notification toast
│       └── auth-art.php       # Komponen visual login split-card
├── config.php                 # Bridge root config (kepatuhan spesifikasi flat)
├── auth.php                   # Bridge root auth
├── language.php               # Bridge root language
├── database.sql               # Bridge root database DDL
├── style.css                  # Bridge root stylesheet
├── login.php                  # Controller & View login
├── register.php               # Controller & View registrasi akun
├── logout.php                 # Handler logout & session cleanup
├── index.php                  # Dashboard direktori data karyawan & live filter
├── create.php                 # Formulir tambah karyawan baru
├── edit.php                   # Formulir peremajaan data karyawan
├── delete.php                 # Endpoint POST penghapusan karyawan
└── README.md                  # Panduan evaluasi & dokumentasi akademis
```

---

_Dibuat untuk memenuhi tugas mata kuliah Pengembangan Teknologi Internet (PTI) UNIKOM._
