-- Migrasi Fase 6 - Sedekah Subuh
-- WAJIB: backup database dulu (phpMyAdmin > Export), lalu impor file ini SEKALI.
-- Aman dijalankan sebelum memasang kode fase 6.

-- 1. Jejak ubah pada setoran masuk: created_at tidak lagi berubah saat diedit
ALTER TABLE `sedekah_masuk`
  MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  ADD COLUMN `updated_by` int(11) DEFAULT NULL AFTER `created_by`,
  ADD COLUMN `updated_at` timestamp NULL DEFAULT NULL AFTER `created_at`;

-- 2. Jejak ubah pada pengeluaran
ALTER TABLE `sedekah_keluar`
  ADD COLUMN `updated_by` int(11) DEFAULT NULL AFTER `created_by`,
  ADD COLUMN `updated_at` timestamp NULL DEFAULT NULL AFTER `created_at`;

-- 3. Riwayat perubahan (audit log)
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `aksi` varchar(30) NOT NULL,
  `tabel` varchar(40) NOT NULL,
  `id_data` int(11) DEFAULT NULL,
  `data_lama` longtext DEFAULT NULL,
  `data_baru` longtext DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_waktu` (`created_at`),
  KEY `idx_audit_tabel` (`tabel`,`id_data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
