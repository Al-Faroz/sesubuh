# DOKUMEN ACUAN KERJA (DAK) FINAL
# PENGEMBANGAN APLIKASI KEUANGAN SEDEKAH SUBUH KELAS - MIN 6 JEMBER

## 1. IDENTITAS & RUANG LINGKUP (KODE: DAK-01-SCOPE)
* **Tujuan:** Media transparansi publik pengelolaan dana Sedekah Subuh secara online (Hosting-ready).
* **Pola Render:** Server-Side Rendered (MPA) berbasis CodeIgniter 4.
* **Landing Page (Public):** Menampilkan visualisasi data berupa grafik tren harian (Senin-Jumat) dan tabel rincian akumulasi per kelas tanpa login.
* **Input System (Admin):** Multi-row inputting dalam satu halaman untuk efisiensi operator.

## 2. ARSITEKTUR DATA (KODE: DAK-02-DATA)
* **Master Data Kelas:** Kelas didefinisikan sebagai "Ruang" fisik (Statis). Tidak ada pemisahan berdasarkan Tahun Ajaran.
* **Logika Saldo:** Single-pool (Saldo Global) namun tetap mencatat ID Kelas pada setiap transaksi (Traceable).
* **Input Debet (Logika Koreksi):** Menggunakan fungsi *Upsert* (Update or Insert).
    * *Aturan:* Jika User menginput ulang untuk Kelas X pada Tanggal Y, maka data lama akan **ditimpa** (dianggap sebagai revisi/koreksi nominal).
* **Input Kredit:** Pengeluaran dengan keterangan teks bebas.
* **Layering:** Menggunakan *Service Layer* untuk kalkulasi logika keuangan sebelum ditampilkan ke View.

## 3. ANTARMUKA & FITUR (KODE: DAK-03-UIX)
* **Automasi Input:** Sistem secara otomatis memunculkan seluruh kelas aktif dalam form input harian.
* **Visualisasi:**
    * Multi-Graph (Bar Chart untuk perbandingan kelas, Line Chart untuk tren).
    * Tabel Matriks Mingguan (Baris: Kelas, Kolom: Hari/Tanggal).
* **Reporting:** * Mencakup: Laporan Harian, Rekap Bulanan, dan Rincian Penggunaan Dana.
    * *Catatan:* Detail layout dan format cetak (PDF/Excel) akan difinalisasi saat **Fase 4 (Development)** berjalan.
* **UI Framework:** AdminLTE 3.2 (Back-end) dan Bootstrap 4 (Front-end).

## 4. KEAMANAN & PEMELIHARAAN (KODE: DAK-04-SECURITY)
* **Access Control:** Menerapkan **Route Filters (Middleware)**. Halaman Admin tidak bisa diakses tanpa sesi login aktif.
* **User Management:** Admin utama mengelola user (Add Operator), tidak tersedia fitur reset password mandiri.
* **Audit Trail:** Pencatatan `user_id` dan `timestamp` pada setiap perubahan data.
* **Maintenance:** Tersedia fitur Backup Database manual (SQL Export) di dalam menu Admin.
* **Environment:** PHP >= 8.1, Apache/XAMPP, MySQL/MariaDB.

---
**Status Dokumen:** FINAL & TERKUNCI (REVISI V.4)
**Tanggal Disepakati:** 21 Januari 2026

# STRUKTUR FOLDER PROYEK (CI4 + ADMINLTE 3.2)
## PROYEK: SEDEKAH SUBUH KELAS MIN 6 JEMBER
## Menerapkan Namespace Modules

app/
├── Database/
│   └── Seeds/
│       └── UserSeeder.php     <-- DATA AWAL (Akun Admin Pertama)
├── Filters/
│   └── AuthFilter.php         <-- PENJAGA KEAMANAN (Cek Login)
├── Modules/
│   └── Infaq/                 <-- RUANG KERJA UTAMA
│       ├── Controllers/
│       │   ├── Home.php       (Landing Page Publik/Wali Murid)
│       │   ├── Auth.php       (Login Admin/Operator)
│       │   ├── Dashboard.php  (Statistik Internal Admin)
│       │   ├── Debet.php      (Input Infaq Masuk per Kelas)
│       │   └── Kredit.php     (Input Pengeluaran)
│       ├── Models/
│       │   ├── InfaqModel.php
│       │   └── KelasModel.php
│       ├── Services/          (Logika Kalkulasi Saldo Global)
│       │   └── FinanceService.php
│       └── Views/             (File HTML/AdminLTE)
│           ├── layout/        (Wrapper AdminLTE)
│           ├── v_home.php     (View Publik)
│           ├── v_dashboard.php
│           ├── v_debet.php
│           └── v_kredit.php
├── Config/
│   ├── Autoload.php           <-- Daftarkan Namespace di sini
│   ├── Filters.php            <-- Daftarkan Alias Filter
│   └── Routes.php             <-- Arahkan rute ke Modules

**Asset AdminLTE**
public/                        # FOLDER ASSET (Akses Publik)
├── assets/
│   ├── plugins/               # Chart.js, DataTables, Select2
│   ├── dist/                  # CSS & JS asli AdminLTE
│   └── custom/                # CSS/JS buatan sendiri
└── index.php                  # Entry Point Utama

# SKEMA DATABASE APLIKASI (Tabel Terpisah & Rapih)
## PROYEK: SEDEKAH SUBUH KELAS MIN 6 JEMBER

### 1. RINGKASAN ARSITEKTUR
* **Engine:** InnoDB (MariaDB/MySQL)
* **Logika:** Saldo Global = `SUM(sedekah_masuk) - SUM(sedekah_keluar)`.

---

### 2. TABEL: `users` (Manajemen Akun)
| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_user` | INT | PK, AI | Primary Key |
| `username` | VARCHAR(50) | Unique | Identitas Login |
| `password` | VARCHAR(255)| Not Null | Hash Password (Bcrypt) |
| `role` | ENUM | 'admin','operator' | Hak akses |

---

### 3. TABEL: `kelas` (Master Ruang)
| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_kelas` | INT | PK, AI | Primary Key |
| `nama_kelas` | VARCHAR(20) | Not Null | Contoh: 1A, 2B, dst |
| `status` | ENUM | 'aktif','nonaktif' | Filter input harian |

---

### 4. TABEL: `sedekah_masuk` (DEBET)
Mencatat uang masuk spesifik per kelas.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_debet` | INT | PK, AI | Primary Key |
| `id_kelas` | INT | FK | Wajib diisi (Relasi Kelas) |
| `nominal` | DECIMAL(15,2)| Not Null | Jumlah uang |
| `tanggal` | DATE | Not Null | Tanggal setor |
| `created_by` | INT | FK | Audit Trail (Operator) |
| `created_at` | TIMESTAMP | DEFAULT | Waktu input/update |

---

### 5. TABEL: `sedekah_keluar` (KREDIT)
Mencatat pengeluaran umum instansi.

| Kolom | Tipe Data | Atribut | Keterangan |
| :--- | :--- | :--- | :--- |
| `id_kredit` | INT | PK, AI | Primary Key |
| `nominal` | DECIMAL(15,2)| Not Null | Jumlah uang keluar |
| `tanggal` | DATE | Not Null | Tanggal keluar |
| `keterangan` | TEXT | Not Null | Keperluan pengeluaran |
| `created_by` | INT | FK | Audit Trail (Operator) |
| `created_at` | TIMESTAMP | DEFAULT | Waktu input |

---

### 6. RELASI & INDEX
* **Foreign Key Masuk:** `sedekah_masuk.id_kelas` -> `kelas.id_kelas` (ON DELETE RESTRICT)
* **Foreign Key Audit:** `created_by` -> `users.id_user`
* **Unique Constraint (Logika Upsert):** Tambahkan UNIQUE INDEX pada `sedekah_masuk(id_kelas, tanggal)` agar fungsi *ON DUPLICATE KEY UPDATE* berjalan mulus.

# PANDUAN PENGEMBANGAN SISTEM (PHASE & ROUTES)
## PROYEK: SEDEKAH SUBUH KELAS MIN 6 JEMBER

### I. JADWAL PENGERJAAN (PHASE STEP-BY-STEP)

| Fase | Fokus Utama | Aktivitas Teknis | Target Output |
| :--- | :--- | :--- | :--- |
| **FASE 1** | **Foundation & Security** | Setup Modules, Migration, **User Seeder** & Auth Filter. | Login Admin Ready. |
| **FASE 2** | **Finance Core** | Form Debet (Upsert Logic) & Kredit. | Transaksi Harian Jalan. |
| **FASE 3** | **Logic & Services** | Implementasi `FinanceService` (Saldo Global). | Saldo Real-time. |
| **FASE 4** | **Reporting** | Chart.js & Finalisasi Layout Laporan (PDF). | Siap Rilis. |

---

### II. KONFIGURASI ROUTES (`app/Config/Routes.php`)

Pemisahan tegas antara Public Area dan Admin Area.

```php
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('Modules\Infaq\Controllers');

// --- 1. PUBLIC AREA (Bebas Akses) ---
// Landing Page Transparansi (Grafik & Tabel untuk Wali Murid)
$routes->get('/', 'Home::index'); 

// Login & Logout
$routes->get('login', 'Auth::index');
$routes->post('login/auth', 'Auth::loginAction');
$routes->get('logout', 'Auth::logout');

// --- 2. PROTECTED AREA (ADMIN & OPERATOR) ---
// Wajib Login untuk akses ke grup 'admin'
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    
    // Dashboard Internal (Statistik Admin)
    $routes->get('/', 'Dashboard::index'); 

    // Modul Infaq Masuk (Debet)
    $routes->group('debet', function($routes) {
        $routes->get('/', 'Debet::index');
        $routes->post('save', 'Debet::save');     // Logic Upsert disini
        $routes->get('delete/(:num)', 'Debet::delete/$1');
    });

    // Modul Pengeluaran (Kredit)
    $routes->group('kredit', function($routes) {
        $routes->get('/', 'Kredit::index');
        $routes->post('save', 'Kredit::save');
        $routes->get('delete/(:num)', 'Kredit::delete/$1');
    });

    // Modul Master Data
    $routes->group('master', function($routes) {
        $routes->resource('kelas');
        $routes->resource('users');
    });
});

#--------------------------------------------------------------------

ENVIRONMENT SETTINGS

#--------------------------------------------------------------------

CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/infaq-min6/'
database.default.hostname = localhost
database.default.database = db_infaq_min6
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

---

### FILE 2: `Kontrak_Kerja_Sedekah_Subuh.md`
*(Berisi Aturan Main & Protokol Konsistensi)*

```markdown
# KONTRAK KERJA PENGEMBANGAN SISTEM (AI PARTNER)
## PROYEK: APLIKASI SEDEKAH SUBUH KELAS MIN 6 JEMBER

Dokumen ini mengatur tata cara kolaborasi antara **AI (Gemini)** sebagai Arsitek Sistem & Lead Developer dengan **User** sebagai Project Owner & Implementor.

### PASAL 1: PRINSIP PENGEMBANGAN & AKURASI
1. **Integritas Keuangan:** Mengingat ini adalah aplikasi keuangan publik, AI wajib menyediakan logika perhitungan yang presisi (terutama Saldo Global).
2. **Kepatuhan DAK:** Semua pengembangan harus merujuk pada **DAK-SEDEKAH-MIN6** yang telah dikunci.
3. **Pola Kerja Modular:** Pengerjaan dilakukan mengikuti urutan **FASE 1 s/d FASE 4**.

### PASAL 2: ALUR KERJA (WORKFLOW)
1. **Iterasi Per Modul:** AI memberikan instruksi penempatan file dan potongan kode (Controller, Model, Service, View).
2. **Implementasi:** User menyalin kode ke project lokal.
3. **Verifikasi & Debug:** Jika terdapat error, User memberikan feedback dan AI melakukan perbaikan sesuai Pasal 6.

### PASAL 3: DISTRIBUSI TUGAS KHUSUS
* **Tanggung Jawab AI:**
    - Menjamin query **Upsert (ON DUPLICATE KEY UPDATE)** berjalan benar di `InfaqModel` agar tidak ada data ganda.
    - Menyusun logic `FinanceService` untuk kalkulasi saldo real-time.
    - Menyiapkan script Chart.js untuk visualisasi di Landing Page.
    - Menyiapkan **AuthFilter** untuk keamanan Admin.
* **Tanggung Jawab User:**
    - Mengelola aset **AdminLTE 3.2** di folder public.
    - Menyiapkan server lokal (XAMPP).
    - Melakukan validasi kesesuaian nominal input dengan yang tampil di layar.

### PASAL 4: ATURAN KHUSUS "TRANSPARANSI & KOREKSI"
1. **Public Access:** Halaman depan wajib bisa diakses tanpa login (Transparansi Wali Murid).
2. **Logika Timpa:** Jika user menginput ulang Kelas X pada Tanggal Y, sistem **WAJIB** menimpa data lama (bukan menjumlahkan), sesuai prinsip "Koreksi Data".

### PASAL 5: SERAH TERIMA & FINALISASI
1. Proyek dianggap selesai jika Laporan Grafik tampil di halaman depan dan Saldo Global akurat.
2. Dokumen teknis menjadi panduan utama jika terjadi pengembangan fitur di masa depan.

### PASAL 6: PROTOKOL KONSISTENSI & ANTI-ASUMSI (CRITICAL)
1. **Larangan Asumsi Liar:** AI dilarang keras menambahkan kolom database, variabel, atau logika baru saat debugging (seperti mengubah logika `Upsert` menjadi `Insert Biasa`) tanpa merujuk kembali ke DAK ini.
2. **Wajib Rujuk DAK:** Sebelum memberikan solusi *fix* untuk error, AI wajib memindai ulang DAK V.4 untuk memastikan solusi tidak bertentangan dengan struktur yang sudah disepakati.
3. **Stop & Ask:** Jika sebuah error tidak bisa diselesaikan tanpa mengubah struktur database/alur logika DAK, AI wajib **berhenti** dan meminta izin User ("Solusi ini membutuhkan perubahan DAK, apakah diizinkan?"), bukan langsung memberikan kode yang melenceng.

---
**Status:** DISEPAKATI FINAL (V.4)
**Mitra Kerja:** Gemini AI Partner & Project Owner MIN 6 Jember