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
CREATE DATABASE IF NOT EXISTS `pengarsipan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pengarsipan`;

-- Dumping structure for table pengarsipan.hasil_laporan
CREATE TABLE IF NOT EXISTS `hasil_laporan` (
  `id_laporan` int NOT NULL AUTO_INCREMENT,
  `id_kawasan` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `status_layak` enum('Layak','Tidak Layak') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rekomendasi` text COLLATE utf8mb4_general_ci,
  `tanggal_dibuat` date DEFAULT NULL,
  PRIMARY KEY (`id_laporan`),
  KEY `id_kawasan` (`id_kawasan`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `hasil_laporan_ibfk_1` FOREIGN KEY (`id_kawasan`) REFERENCES `kawasan` (`id_kawasan`),
  CONSTRAINT `hasil_laporan_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode_penilaian` (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.indikator_penilaian
CREATE TABLE IF NOT EXISTS `indikator_penilaian` (
  `id_indikator` int NOT NULL AUTO_INCREMENT,
  `nama_indikator` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_indikator`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.kawasan
CREATE TABLE IF NOT EXISTS `kawasan` (
  `id_kawasan` int NOT NULL AUTO_INCREMENT,
  `nama_kawasan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luas_ha` decimal(10,2) DEFAULT NULL,
  `jumlah_penduduk` int DEFAULT NULL,
  `status_layak` enum('Layak','Tidak Layak') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_kawasan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.penilaian_kawasan
CREATE TABLE IF NOT EXISTS `penilaian_kawasan` (
  `id_penilaian` int NOT NULL AUTO_INCREMENT,
  `id_kawasan` int DEFAULT NULL,
  `id_indikator` int DEFAULT NULL,
  `id_periode` int DEFAULT NULL,
  `nilai` decimal(4,2) DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `tanggal_penilaian` date DEFAULT NULL,
  PRIMARY KEY (`id_penilaian`),
  KEY `id_kawasan` (`id_kawasan`),
  KEY `id_indikator` (`id_indikator`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `penilaian_kawasan_ibfk_1` FOREIGN KEY (`id_kawasan`) REFERENCES `kawasan` (`id_kawasan`),
  CONSTRAINT `penilaian_kawasan_ibfk_2` FOREIGN KEY (`id_indikator`) REFERENCES `indikator_penilaian` (`id_indikator`),
  CONSTRAINT `penilaian_kawasan_ibfk_3` FOREIGN KEY (`id_periode`) REFERENCES `periode_penilaian` (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.periode_penilaian
CREATE TABLE IF NOT EXISTS `periode_penilaian` (
  `id_periode` int NOT NULL AUTO_INCREMENT,
  `bulan` int DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_bidang
CREATE TABLE IF NOT EXISTS `tb_bidang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_bidang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_cuti
CREATE TABLE IF NOT EXISTS `tb_cuti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `alasan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_document_tags
CREATE TABLE IF NOT EXISTS `tb_document_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_id` int NOT NULL,
  `document_type` enum('masuk','keluar','undangan','surat_cuti','surat_gaji') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `document_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tag_document` (`tag_id`,`document_type`,`document_id`),
  CONSTRAINT `tb_document_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tb_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_gaji
CREATE TABLE IF NOT EXISTS `tb_gaji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `gaji_awal` double DEFAULT NULL,
  `gaji_akhir` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_log_activity
CREATE TABLE IF NOT EXISTS `tb_log_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login_time` datetime NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tb_log_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_pegawai
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_surat
CREATE TABLE IF NOT EXISTS `tb_surat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis_surat` enum('surat_masuk','surat_keluar','surat_undangan','surat_cuti','surat_gaji') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_surat_cuti
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

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_surat_gaji
CREATE TABLE IF NOT EXISTS `tb_surat_gaji` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `id_surat` int DEFAULT NULL,
  `id_gaji` int DEFAULT NULL,
  `id_bidang` int DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_surat_keluar
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_surat_masuk
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pengarsipan.tb_tags
CREATE TABLE IF NOT EXISTS `tb_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
