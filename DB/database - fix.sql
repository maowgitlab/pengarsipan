-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pengarsipan
DROP DATABASE IF EXISTS `pengarsipan`;
CREATE DATABASE IF NOT EXISTS `pengarsipan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pengarsipan`;

-- Dumping structure for table pengarsipan.hasil_laporan
DROP TABLE IF EXISTS `hasil_laporan`;
CREATE TABLE IF NOT EXISTS `hasil_laporan` (
  `id_laporan` int NOT NULL AUTO_INCREMENT,
  `id_kawasan` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `status_layak` enum('Layak','Tidak Layak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rekomendasi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tanggal_dibuat` date DEFAULT NULL,
  PRIMARY KEY (`id_laporan`),
  KEY `id_kawasan` (`id_kawasan`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `hasil_laporan_ibfk_1` FOREIGN KEY (`id_kawasan`) REFERENCES `kawasan` (`id_kawasan`),
  CONSTRAINT `hasil_laporan_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode_penilaian` (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.hasil_laporan: ~5 rows (approximately)
DELETE FROM `hasil_laporan`;
INSERT INTO `hasil_laporan` (`id_laporan`, `id_kawasan`, `id_periode`, `status_layak`, `rekomendasi`, `tanggal_dibuat`) VALUES
	(1, 1, 1, 'Layak', 'Pertahankan kebersihan dan infrastruktur', '2024-01-20'),
	(2, 2, 1, 'Tidak Layak', 'Perbaikan saluran dan jalan diperlukan', '2024-01-20'),
	(3, 3, 2, 'Layak', 'Lanjutkan pemeliharaan fasilitas', '2024-04-15'),
	(4, 4, 3, 'Layak', 'Tingkatkan pengelolaan sampah', '2024-07-15'),
	(5, 5, 4, 'Tidak Layak', 'Perbaikan saluran dan kebersihan mendesak', '2024-10-20');

-- Dumping structure for table pengarsipan.indikator_penilaian
DROP TABLE IF EXISTS `indikator_penilaian`;
CREATE TABLE IF NOT EXISTS `indikator_penilaian` (
  `id_indikator` int NOT NULL AUTO_INCREMENT,
  `nama_indikator` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_indikator`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.indikator_penilaian: ~5 rows (approximately)
DELETE FROM `indikator_penilaian`;
INSERT INTO `indikator_penilaian` (`id_indikator`, `nama_indikator`, `keterangan`) VALUES
	(1, 'Kebersihan', 'Kebersihan lingkungan kawasan'),
	(2, 'Infrastruktur', 'Kondisi jalan, saluran air, dan fasilitas umum'),
	(3, 'Kepadatan Penduduk', 'Tingkat kepadatan penduduk per hektar'),
	(4, 'Kualitas Bangunan', 'Kondisi fisik bangunan di kawasan'),
	(5, 'Akses Air Bersih', 'Ketersediaan air bersih untuk penduduk');

-- Dumping structure for table pengarsipan.kawasan
DROP TABLE IF EXISTS `kawasan`;
CREATE TABLE IF NOT EXISTS `kawasan` (
  `id_kawasan` int NOT NULL AUTO_INCREMENT,
  `nama_kawasan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_ha` decimal(10,2) DEFAULT NULL,
  `jumlah_penduduk` int DEFAULT NULL,
  `status_layak` enum('Layak','Tidak Layak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_kawasan`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.kawasan: ~10 rows (approximately)
DELETE FROM `kawasan`;
INSERT INTO `kawasan` (`id_kawasan`, `nama_kawasan`, `kecamatan`, `luas_ha`, `jumlah_penduduk`, `status_layak`) VALUES
	(1, 'Kawasan A', 'Banjarmasin Selatan', 50.25, 5000, 'Layak'),
	(2, 'Kawasan B', 'Banjarmasin Utara', 45.75, 4500, 'Tidak Layak'),
	(3, 'Kawasan C', 'Banjarmasin Timur', 60.00, 6000, 'Layak'),
	(4, 'Kawasan D', 'Banjarmasin Barat', 55.30, 5200, 'Layak'),
	(5, 'Kawasan E', 'Banjarmasin Tengah', 40.10, 4000, 'Tidak Layak'),
	(6, 'Kawasan F', 'Banjarbaru Selatan', 70.50, 7000, 'Layak'),
	(7, 'Kawasan G', 'Banjarbaru Utara', 65.20, 6500, 'Layak'),
	(8, 'Kawasan H', 'Martapura', 80.00, 8000, 'Tidak Layak'),
	(9, 'Kawasan I', 'Tanjung', 75.40, 7500, 'Layak'),
	(10, 'Kawasan J', 'Pelaihari', 85.60, 8200, 'Tidak Layak');

-- Dumping structure for table pengarsipan.penilaian_kawasan
DROP TABLE IF EXISTS `penilaian_kawasan`;
CREATE TABLE IF NOT EXISTS `penilaian_kawasan` (
  `id_penilaian` int NOT NULL AUTO_INCREMENT,
  `id_kawasan` int DEFAULT NULL,
  `id_indikator` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `nilai` decimal(4,2) DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tanggal_penilaian` date DEFAULT NULL,
  `bukti_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_penilaian`),
  KEY `id_kawasan` (`id_kawasan`),
  KEY `id_indikator` (`id_indikator`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `penilaian_kawasan_ibfk_1` FOREIGN KEY (`id_kawasan`) REFERENCES `kawasan` (`id_kawasan`),
  CONSTRAINT `penilaian_kawasan_ibfk_2` FOREIGN KEY (`id_indikator`) REFERENCES `indikator_penilaian` (`id_indikator`),
  CONSTRAINT `penilaian_kawasan_ibfk_3` FOREIGN KEY (`id_periode`) REFERENCES `periode_penilaian` (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.penilaian_kawasan: ~15 rows (approximately)
DELETE FROM `penilaian_kawasan`;
INSERT INTO `penilaian_kawasan` (`id_penilaian`, `id_kawasan`, `id_indikator`, `id_periode`, `nilai`, `keterangan`, `tanggal_penilaian`, `bukti_file`) VALUES
	(1, 1, 1, 1, 8.50, 'Lingkungan bersih', '2024-01-15', NULL),
	(2, 1, 2, 1, 7.80, 'Jalan baik, saluran memadai', '2024-01-15', NULL),
	(3, 2, 1, 1, 6.20, 'Banyak sampah di saluran', '2024-01-15', NULL),
	(4, 2, 2, 1, 5.50, 'Jalan rusak', '2024-01-15', NULL),
	(5, 3, 1, 2, 8.00, 'Kebersihan terjaga', '2024-04-10', NULL),
	(6, 3, 2, 2, 7.90, 'Infrastruktur baik', '2024-04-10', NULL),
	(7, 4, 1, 3, 7.50, 'Cukup bersih', '2024-07-12', NULL),
	(8, 4, 2, 3, 8.20, 'Fasilitas memadai', '2024-07-12', NULL),
	(9, 5, 1, 4, 6.80, 'Perlu perbaikan kebersihan', '2024-10-15', NULL),
	(10, 5, 2, 4, 6.50, 'Saluran tersumbat', '2024-10-15', NULL),
	(11, 6, 3, 1, 7.00, 'Kepadatan sedang', '2024-01-15', NULL),
	(12, 7, 4, 2, 8.50, 'Bangunan kokoh', '2024-04-10', NULL),
	(13, 8, 5, 3, 6.00, 'Akses air terbatas', '2024-07-12', NULL),
	(14, 9, 3, 4, 7.80, 'Kepadatan rendah', '2024-10-15', NULL),
	(15, 10, 4, 1, 6.50, 'Bangunan perlu renovasi', '2024-01-15', NULL);

-- Dumping structure for table pengarsipan.periode_penilaian
DROP TABLE IF EXISTS `periode_penilaian`;
CREATE TABLE IF NOT EXISTS `periode_penilaian` (
  `id_periode` int NOT NULL AUTO_INCREMENT,
  `bulan` int DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.periode_penilaian: ~5 rows (approximately)
DELETE FROM `periode_penilaian`;
INSERT INTO `periode_penilaian` (`id_periode`, `bulan`, `tahun`, `keterangan`) VALUES
	(1, 1, 2024, 'Penilaian Triwulan I 2024'),
	(2, 4, 2024, 'Penilaian Triwulan II 2024'),
	(3, 7, 2024, 'Penilaian Triwulan III 2024'),
	(4, 10, 2024, 'Penilaian Triwulan IV 2024'),
	(5, 1, 2025, 'Penilaian Triwulan I 2025');

-- Dumping structure for table pengarsipan.tb_bidang
DROP TABLE IF EXISTS `tb_bidang`;
CREATE TABLE IF NOT EXISTS `tb_bidang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_bidang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_bidang: ~5 rows (approximately)
DELETE FROM `tb_bidang`;
INSERT INTO `tb_bidang` (`id`, `nama_bidang`, `kegiatan`) VALUES
	(1, 'Administrasi', 'Pengelolaan data dan dokumen'),
	(2, 'Perencanaan', 'Perencanaan pembangunan permukiman'),
	(3, 'Teknis', 'Pelaksanaan proyek infrastruktur'),
	(4, 'Keuangan', 'Pengelolaan anggaran'),
	(5, 'Hukum', 'Penyusunan regulasi');

-- Dumping structure for table pengarsipan.tb_cuti
DROP TABLE IF EXISTS `tb_cuti`;
CREATE TABLE IF NOT EXISTS `tb_cuti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `alasan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_cuti: ~10 rows (approximately)
DELETE FROM `tb_cuti`;
INSERT INTO `tb_cuti` (`id`, `nip`, `id_bidang`, `alasan`, `tanggal_mulai`, `tanggal_selesai`) VALUES
	(1, '198001012021011001', 1, 'Cuti Tahunan', '2024-01-10', '2024-01-15'),
	(2, '198002022021011002', 2, 'Cuti Sakit', '2024-02-01', '2024-02-03'),
	(3, '198003032021011003', 3, 'Cuti Melahirkan', '2024-03-15', '2024-05-15'),
	(4, '198004042021011004', 4, 'Cuti Tahunan', '2024-04-20', '2024-04-25'),
	(5, '198005052021011005', 5, 'Cuti Penting', '2024-05-10', '2024-05-12'),
	(6, '198006062021011006', 3, 'Cuti Tahunan', '2024-06-01', '2024-06-05'),
	(7, '198007072021011007', 2, 'Cuti Sakit', '2024-07-10', '2024-07-12'),
	(8, '198008082021011008', 1, 'Cuti Tahunan', '2024-08-15', '2024-08-20'),
	(9, '198009092021011009', 4, 'Cuti Penting', '2024-09-05', '2024-09-07'),
	(10, '198010102021011010', 5, 'Cuti Tahunan', '2024-10-01', '2024-10-05');

-- Dumping structure for table pengarsipan.tb_document_tags
DROP TABLE IF EXISTS `tb_document_tags`;
CREATE TABLE IF NOT EXISTS `tb_document_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_id` int NOT NULL,
  `document_type` enum('masuk','keluar','undangan','surat_cuti','surat_gaji') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `document_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tag_document` (`tag_id`,`document_type`,`document_id`),
  CONSTRAINT `tb_document_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tb_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_document_tags: ~10 rows (approximately)
DELETE FROM `tb_document_tags`;
INSERT INTO `tb_document_tags` (`id`, `tag_id`, `document_type`, `document_id`) VALUES
	(1, 1, 'masuk', 1),
	(7, 1, 'keluar', 7),
	(2, 2, 'undangan', 3),
	(6, 2, 'undangan', 5),
	(9, 2, 'undangan', 8),
	(3, 3, 'surat_gaji', 1),
	(8, 3, 'surat_gaji', 2),
	(10, 4, 'masuk', 9),
	(4, 4, 'keluar', 4),
	(5, 5, 'masuk', 6);

-- Dumping structure for table pengarsipan.tb_gaji
DROP TABLE IF EXISTS `tb_gaji`;
CREATE TABLE IF NOT EXISTS `tb_gaji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `gaji_awal` double DEFAULT NULL,
  `gaji_akhir` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_gaji: ~10 rows (approximately)
DELETE FROM `tb_gaji`;
INSERT INTO `tb_gaji` (`id`, `nip`, `id_bidang`, `gaji_awal`, `gaji_akhir`) VALUES
	(1, '198001012021011001', 1, 5000000, 5500000),
	(2, '198002022021011002', 2, 8000000, 8500000),
	(3, '198003032021011003', 3, 4500000, 4800000),
	(4, '198004042021011004', 4, 4600000, 4900000),
	(5, '198005052021011005', 5, 4700000, 5000000),
	(6, '198006062021011006', 3, 4500000, 4800000),
	(7, '198007072021011007', 2, 4600000, 4900000),
	(8, '198008082021011008', 1, 4700000, 5000000),
	(9, '198009092021011009', 4, 4500000, 4800000),
	(10, '198010102021011010', 5, 4600000, 4900000);

-- Dumping structure for table pengarsipan.tb_log_activity
DROP TABLE IF EXISTS `tb_log_activity`;
CREATE TABLE IF NOT EXISTS `tb_log_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login_time` datetime NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tb_log_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_log_activity: ~15 rows (approximately)
DELETE FROM `tb_log_activity`;
INSERT INTO `tb_log_activity` (`id`, `user_id`, `username`, `login_time`, `ip_address`) VALUES
	(11, 1, 'admin', '2025-07-21 22:19:41', '::1'),
	(12, 2, 'kadisperkim', '2025-07-21 22:24:15', '::1'),
	(13, 1, 'admin', '2025-07-21 22:37:38', '::1'),
	(14, 2, 'kadisperkim', '2025-07-21 22:44:35', '::1'),
	(15, 5, 'eka_putri', '2025-07-22 00:09:39', '::1'),
	(16, 1, 'admin', '2025-07-22 00:10:19', '::1'),
	(17, 1, 'admin', '2025-07-22 00:35:39', '::1'),
	(18, 2, 'kadisperkim', '2025-07-22 08:32:47', '::1'),
	(19, 2, 'kadisperkim', '2025-07-22 08:35:29', '::1'),
	(20, 1, 'admin', '2025-07-22 08:53:40', '::1'),
	(21, 2, 'kadisperkim', '2025-07-22 08:55:32', '::1'),
	(22, 3, 'citra_dewi', '2025-07-22 08:55:47', '::1'),
	(23, 1, 'admin', '2025-07-24 08:27:21', '::1'),
	(24, 2, 'kadisperkim', '2025-07-24 08:54:31', '::1'),
	(25, 1, 'admin', '2025-07-24 08:54:42', '::1');

-- Dumping structure for table pengarsipan.tb_pegawai
DROP TABLE IF EXISTS `tb_pegawai`;
CREATE TABLE IF NOT EXISTS `tb_pegawai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `nama_pegawai` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_pegawai: ~10 rows (approximately)
DELETE FROM `tb_pegawai`;
INSERT INTO `tb_pegawai` (`id`, `nip`, `id_bidang`, `nama_pegawai`, `tempat_lahir`, `jabatan`, `no_telp`, `username`, `password`) VALUES
	(1, '198001012021011001', 1, 'Ahmad Yani', 'Banjarmasin', 'admin', '081234567801', 'admin', '827ccb0eea8a706c4c34a16891f84e7b'),
	(2, '198002022021011002', 2, 'Budi Santoso', 'Banjarbaru', 'kadisperkim', '081234567802', 'kadisperkim', '827ccb0eea8a706c4c34a16891f84e7b'),
	(3, '198003032021011003', 3, 'Citra Dewi', 'Martapura', 'Pegawai', '081234567803', 'citra_dewi', '827ccb0eea8a706c4c34a16891f84e7b'),
	(4, '198004042021011004', 4, 'Dedi Kurniawan', 'Pelaihari', 'Pegawai', '081234567804', 'dedi_kurniawan', '827ccb0eea8a706c4c34a16891f84e7b'),
	(5, '198005052021011005', 5, 'Eka Putri', 'Banjarmasin', 'Pegawai', '081234567805', 'eka_putri', '827ccb0eea8a706c4c34a16891f84e7b'),
	(6, '198006062021011006', 3, 'Fajar Nugroho', 'Tanjung', 'Pegawai', '081234567806', 'fajar_nugroho', '827ccb0eea8a706c4c34a16891f84e7b'),
	(7, '198007072021011007', 2, 'Gina Lestari', 'Banjarbaru', 'Pegawai', '081234567807', 'gina_lestari', '827ccb0eea8a706c4c34a16891f84e7b'),
	(8, '198008082021011008', 1, 'Hendra Wijaya', 'Martapura', 'Pegawai', '081234567808', 'hendra_wijaya', '827ccb0eea8a706c4c34a16891f84e7b'),
	(9, '198009092021011009', 4, 'Indah Sari', 'Banjarmasin', 'Pegawai', '081234567809', 'indah_sari', '827ccb0eea8a706c4c34a16891f84e7b'),
	(10, '198010102021011010', 5, 'Joko Susilo', 'Pelaihari', 'Pegawai', '081234567810', 'joko_susilo', '827ccb0eea8a706c4c34a16891f84e7b');

-- Dumping structure for table pengarsipan.tb_surat
DROP TABLE IF EXISTS `tb_surat`;
CREATE TABLE IF NOT EXISTS `tb_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_surat` enum('surat_masuk','surat_keluar','surat_undangan','surat_cuti','surat_gaji') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_surat: ~5 rows (approximately)
DELETE FROM `tb_surat`;
INSERT INTO `tb_surat` (`id`, `jenis_surat`) VALUES
	(1, 'surat_masuk'),
	(2, 'surat_keluar'),
	(3, 'surat_undangan'),
	(4, 'surat_cuti'),
	(5, 'surat_gaji');

-- Dumping structure for table pengarsipan.tb_surat_cuti
DROP TABLE IF EXISTS `tb_surat_cuti`;
CREATE TABLE IF NOT EXISTS `tb_surat_cuti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_surat` int DEFAULT NULL,
  `id_cuti` int DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `status` enum('diajukan','diterima','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_surat_cuti: ~10 rows (approximately)
DELETE FROM `tb_surat_cuti`;
INSERT INTO `tb_surat_cuti` (`id`, `no_surat`, `id_surat`, `id_cuti`, `tanggal_surat`, `status`, `file`) VALUES
	(1, 'SC/001/2024', 4, 1, '2024-01-05', 'diterima', 'sc001.pdf'),
	(2, 'SC/002/2024', 4, 2, '2024-01-30', 'diterima', 'sc002.pdf'),
	(3, 'SC/003/2024', 4, 3, '2024-03-10', 'diterima', 'sc003.pdf'),
	(4, 'SC/004/2024', 4, 4, '2024-04-15', 'diajukan', 'sc004.pdf'),
	(5, 'SC/005/2024', 4, 5, '2024-05-05', 'diterima', 'sc005.pdf'),
	(6, 'SC/006/2024', 4, 6, '2024-05-28', 'diterima', 'sc006.pdf'),
	(7, 'SC/007/2024', 4, 7, '2024-07-05', 'ditolak', 'sc007.pdf'),
	(8, 'SC/008/2024', 4, 8, '2024-08-10', 'diterima', 'sc008.pdf'),
	(9, 'SC/009/2024', 4, 9, '2024-09-01', 'diajukan', 'sc009.pdf'),
	(10, 'SC/010/2024', 4, 10, '2024-09-28', 'diterima', 'sc010.pdf');

-- Dumping structure for table pengarsipan.tb_surat_gaji
DROP TABLE IF EXISTS `tb_surat_gaji`;
CREATE TABLE IF NOT EXISTS `tb_surat_gaji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `id_surat` int DEFAULT NULL,
  `id_gaji` int DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_surat_gaji: ~10 rows (approximately)
DELETE FROM `tb_surat_gaji`;
INSERT INTO `tb_surat_gaji` (`id`, `no_surat`, `id_surat`, `id_gaji`, `id_bidang`, `tanggal_surat`) VALUES
	(1, 'SG/001/2024', 5, 1, 1, '2024-01-05'),
	(2, 'SG/002/2024', 5, 2, 2, '2024-02-05'),
	(3, 'SG/003/2024', 5, 3, 3, '2024-03-05'),
	(4, 'SG/004/2024', 5, 4, 4, '2024-04-05'),
	(5, 'SG/005/2024', 5, 5, 5, '2024-05-05'),
	(6, 'SG/006/2024', 5, 6, 3, '2024-06-05'),
	(7, 'SG/007/2024', 5, 7, 2, '2024-07-05'),
	(8, 'SG/008/2024', 5, 8, 1, '2024-08-05'),
	(9, 'SG/009/2024', 5, 9, 4, '2024-09-05'),
	(10, 'SG/010/2024', 5, 10, 5, '2024-10-05');

-- Dumping structure for table pengarsipan.tb_surat_keluar
DROP TABLE IF EXISTS `tb_surat_keluar`;
CREATE TABLE IF NOT EXISTS `tb_surat_keluar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_surat` int DEFAULT NULL,
  `no_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `penerima` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perihal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instansi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_surat_keluar: ~10 rows (approximately)
DELETE FROM `tb_surat_keluar`;
INSERT INTO `tb_surat_keluar` (`id`, `id_surat`, `no_surat`, `tanggal_kirim`, `penerima`, `perihal`, `file`, `instansi`) VALUES
	(1, 2, 'SK/001/2024', '2024-01-15', 'Dinas PUPR', 'Balasan Permintaan Data', 'sk001.pdf', 'Dinas PUPR Kalsel'),
	(2, 2, 'SK/002/2024', '2024-02-20', 'Pemkot Banjarmasin', 'Laporan Progres', 'sk002.pdf', 'Pemkot Banjarmasin'),
	(3, 3, 'SU/005/2024', '2024-03-05', 'Dinas Lingkungan Hidup', 'Undangan Koordinasi', 'su005.pdf', 'Dinas LH Kalsel'),
	(4, 2, 'SK/003/2024', '2024-04-10', 'Bappeda', 'Usulan Anggaran', 'sk003.pdf', 'Bappeda Kalsel'),
	(5, 3, 'SU/006/2024', '2024-05-15', 'Pemprov Kalsel', 'Undangan Konsultasi', 'su006.pdf', 'Pemprov Kalsel'),
	(6, 2, 'SK/004/2024', '2024-06-20', 'Dinas Kesehatan', 'Permintaan Data Kesehatan', 'sk004.pdf', 'Dinas Kesehatan'),
	(7, 2, 'SK/005/2024', '2024-07-25', 'Pemkot Banjarbaru', 'Laporan Tahunan', 'sk005.pdf', 'Pemkot Banjarbaru'),
	(8, 3, 'SU/007/2024', '2024-08-10', 'Dinas Perkim', 'Undangan Evaluasi', 'su007.pdf', 'Dinas Perkim'),
	(9, 2, 'SK/006/2024', '2024-09-15', 'BPN', 'Permohonan Data Tanah', 'sk006.pdf', 'BPN Kalsel'),
	(10, 3, 'SU/008/2024', '2024-10-10', 'Pemprov Kalsel', 'Undangan Rapat Tahunan', 'su008.pdf', 'Pemprov Kalsel');

-- Dumping structure for table pengarsipan.tb_surat_masuk
DROP TABLE IF EXISTS `tb_surat_masuk`;
CREATE TABLE IF NOT EXISTS `tb_surat_masuk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_surat` int DEFAULT NULL,
  `no_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_terima` date DEFAULT NULL,
  `pengirim` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perihal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instansi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_surat_masuk: ~10 rows (approximately)
DELETE FROM `tb_surat_masuk`;
INSERT INTO `tb_surat_masuk` (`id`, `id_surat`, `no_surat`, `tanggal_masuk`, `tanggal_terima`, `pengirim`, `perihal`, `file`, `instansi`) VALUES
	(1, 1, 'SM/001/2024', '2024-01-10', '2024-01-12', 'Dinas PUPR', 'Permintaan Data', 'sm001.pdf', 'Dinas PUPR Kalsel'),
	(2, 1, 'SM/002/2024', '2024-02-15', '2024-02-17', 'Pemkot Banjarmasin', 'Laporan Bulanan', 'sm002.pdf', 'Pemkot Banjarmasin'),
	(3, 3, 'SU/001/2024', '2024-03-01', '2024-03-03', 'Dinas Lingkungan Hidup', 'Undangan Rapat', 'su001.pdf', 'Dinas LH Kalsel'),
	(4, 1, 'SM/003/2024', '2024-04-05', '2024-04-07', 'Bappeda', 'Rencana Pembangunan', 'sm003.pdf', 'Bappeda Kalsel'),
	(5, 3, 'SU/002/2024', '2024-05-10', '2024-05-12', 'Pemprov Kalsel', 'Undangan Seminar', 'su002.pdf', 'Pemprov Kalsel'),
	(6, 1, 'SM/004/2024', '2024-06-15', '2024-06-17', 'Dinas Kesehatan', 'Laporan Kesehatan', 'sm004.pdf', 'Dinas Kesehatan'),
	(7, 1, 'SM/005/2024', '2024-07-20', '2024-07-22', 'Pemkot Banjarbaru', 'Permintaan Koordinasi', 'sm005.pdf', 'Pemkot Banjarbaru'),
	(8, 3, 'SU/003/2024', '2024-08-01', '2024-08-03', 'Dinas Perkim', 'Undangan Workshop', 'su003.pdf', 'Dinas Perkim'),
	(9, 1, 'SM/006/2024', '2024-09-10', '2024-09-12', 'BPN', 'Sertifikasi Tanah', 'sm006.pdf', 'BPN Kalsel'),
	(10, 3, 'SU/004/2024', '2024-10-05', '2024-10-07', 'Pemprov Kalsel', 'Undangan Pelatihan', 'su004.pdf', 'Pemprov Kalsell');

-- Dumping structure for table pengarsipan.tb_tags
DROP TABLE IF EXISTS `tb_tags`;
CREATE TABLE IF NOT EXISTS `tb_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pengarsipan.tb_tags: ~5 rows (approximately)
DELETE FROM `tb_tags`;
INSERT INTO `tb_tags` (`id`, `tag_name`) VALUES
	(4, 'Infrastruktur'),
	(5, 'Kebersihan'),
	(3, 'Keuangan'),
	(1, 'Penting'),
	(2, 'Rapat');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
