# Sedekah Subuh - MIN 6 Jember

Aplikasi pencatatan dan pelaporan sedekah subuh per kelas, dibangun dengan CodeIgniter 4 (modul `Infaq`).

## Fitur
- Portal publik: ringkasan kas, tren harian, partisipasi per kelas, matrik.
- Panel admin/operator: input setoran, pengeluaran (tambah/ubah/hapus), laporan matrik dan buku kas (PDF).
- Panel admin: data kelas, setting pejabat, kelola akun, riwayat perubahan (audit), backup database.
- Mode gelap/terang, tampilan neomorfisme, responsif.

## Menjalankan secara lokal
1. `composer install`
2. Salin `env` menjadi `.env`, lalu atur `app.baseURL` dan koneksi database.
3. Impor dump SQL utama, lalu `database/migrasi_fase6.sql` (kolom updated_by/updated_at dan tabel audit_log).
4. Buka `http://localhost/sesubuh/`.

## Produksi
Aktifkan SSL, lalu di `.env` atur `app.baseURL` ke alamat `https://` dan `app.forceGlobalSecure = true`.

Jangan commit `.env`, berkas `*.sql` data, atau sertifikat/kunci ke repo.
