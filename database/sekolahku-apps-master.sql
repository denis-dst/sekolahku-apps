/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 8.0.30 : Database - sekolahku_apps
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sekolahku_apps` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `sekolahku_apps`;

/*Table structure for table `activity_log` */

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activity_log` */

/*Table structure for table `anekdot_lampirans` */

DROP TABLE IF EXISTS `anekdot_lampirans`;

CREATE TABLE `anekdot_lampirans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anekdot_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anekdot_lampirans_anekdot_id_foreign` (`anekdot_id`),
  CONSTRAINT `anekdot_lampirans_anekdot_id_foreign` FOREIGN KEY (`anekdot_id`) REFERENCES `anekdots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `anekdot_lampirans` */

/*Table structure for table `anekdots` */

DROP TABLE IF EXISTS `anekdots`;

CREATE TABLE `anekdots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `peristiwa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `analisis_capaian` text COLLATE utf8mb4_unicode_ci,
  `umpan_balik` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anekdots_school_id_foreign` (`school_id`),
  KEY `anekdots_siswa_id_foreign` (`siswa_id`),
  KEY `anekdots_guru_id_foreign` (`guru_id`),
  CONSTRAINT `anekdots_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE SET NULL,
  CONSTRAINT `anekdots_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anekdots_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `anekdots` */

/*Table structure for table `aset_riwayats` */

DROP TABLE IF EXISTS `aset_riwayats`;

CREATE TABLE `aset_riwayats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aset_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `tanggal_perbaikan` date NOT NULL,
  `deskripsi_kerusakan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `biaya` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('Proses','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aset_riwayats_aset_id_foreign` (`aset_id`),
  KEY `aset_riwayats_user_id_foreign` (`user_id`),
  CONSTRAINT `aset_riwayats_aset_id_foreign` FOREIGN KEY (`aset_id`) REFERENCES `asets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aset_riwayats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `aset_riwayats` */

/*Table structure for table `asets` */

DROP TABLE IF EXISTS `asets`;

CREATE TABLE `asets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `kode_aset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_aset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Elektronik',
  `sumber_dana` enum('BOSP','Yayasan','Hibah','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BOSP',
  `tanggal_pengadaan` date DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asets_school_id_foreign` (`school_id`),
  CONSTRAINT `asets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `asets` */

/*Table structure for table `assessments` */

DROP TABLE IF EXISTS `assessments`;

CREATE TABLE `assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `rombel_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned DEFAULT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Capaian Pembelajaran',
  `jenis_penilaian` enum('Formatif','Sumatif','P5') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sumatif',
  `nilai_angka` decimal(5,2) DEFAULT NULL,
  `capaian_narasi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessments_school_id_foreign` (`school_id`),
  KEY `assessments_rombel_id_foreign` (`rombel_id`),
  KEY `assessments_siswa_id_foreign` (`siswa_id`),
  KEY `assessments_guru_id_foreign` (`guru_id`),
  CONSTRAINT `assessments_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assessments_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessments_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `assessments` */

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `diplomas` */

DROP TABLE IF EXISTS `diplomas`;

CREATE TABLE `diplomas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `no_ijazah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lulus` date NOT NULL,
  `file_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `diplomas_no_ijazah_unique` (`no_ijazah`),
  KEY `diplomas_school_id_foreign` (`school_id`),
  KEY `diplomas_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `diplomas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `diplomas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `diplomas` */

/*Table structure for table `expense_categories` */

DROP TABLE IF EXISTS `expense_categories`;

CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_bosp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_categories_school_id_foreign` (`school_id`),
  CONSTRAINT `expense_categories_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `expense_categories` */

insert  into `expense_categories`(`id`,`school_id`,`nama_kategori`,`kode_bosp`,`keterangan`,`created_at`,`updated_at`) values 
(1,1,'Alat Tulis & Bahan Pembelajaran','BOSP-01','Pengadaan Kertas, Krayon, Buku Gambar','2026-08-12 12:35:46','2026-08-12 12:35:46'),
(2,1,'Konsumsi & Operasional Harian','BOSP-02','Konsumsi Rapat, Snack Anak','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `expense_receipts` */

DROP TABLE IF EXISTS `expense_receipts`;

CREATE TABLE `expense_receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_receipts_expense_id_foreign` (`expense_id`),
  CONSTRAINT `expense_receipts_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `expense_receipts` */

/*Table structure for table `expense_status_histories` */

DROP TABLE IF EXISTS `expense_status_histories`;

CREATE TABLE `expense_status_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `status_sebelum` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_sesudah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_status_histories_expense_id_foreign` (`expense_id`),
  KEY `expense_status_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `expense_status_histories_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expense_status_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `expense_status_histories` */

/*Table structure for table `expenses` */

DROP TABLE IF EXISTS `expenses`;

CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `expense_category_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `uraian` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `toko_vendor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Belum Diajukan','Diajukan','Disetujui','Dibayar','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Diajukan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_school_id_foreign` (`school_id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `expenses` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `gurus` */

DROP TABLE IF EXISTS `gurus`;

CREATE TABLE `gurus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nuptk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gelar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'L',
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gurus_school_id_foreign` (`school_id`),
  KEY `gurus_user_id_foreign` (`user_id`),
  CONSTRAINT `gurus_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `gurus` */

insert  into `gurus`(`id`,`school_id`,`user_id`,`nip`,`nuptk`,`nama_lengkap`,`gelar`,`jenis_kelamin`,`no_hp`,`alamat`,`created_at`,`updated_at`) values 
(1,1,5,'19880315 201502 2 003','889077665544','Nurhayati, S.Pd.','S.Pd.','P','081234567894','Jl. Mawar No. 12','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `kalender_akademiks` */

DROP TABLE IF EXISTS `kalender_akademiks`;

CREATE TABLE `kalender_akademiks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned DEFAULT NULL,
  `judul_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kegiatan Sekolah',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kalender_akademiks_school_id_foreign` (`school_id`),
  KEY `kalender_akademiks_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `kalender_akademiks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kalender_akademiks_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kalender_akademiks` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_08_12_000001_create_tenants_table',1),
(5,'2026_08_12_000002_create_schools_table',1),
(6,'2026_08_12_000003_create_tahun_ajarans_table',1),
(7,'2026_08_12_000004_create_rombels_table',1),
(8,'2026_08_12_000005_create_gurus_table',1),
(9,'2026_08_12_000006_create_siswas_table',1),
(10,'2026_08_12_000007_create_presensis_table',1),
(11,'2026_08_12_000008_create_presensi_logs_table',1),
(12,'2026_08_12_000009_create_anekdots_table',1),
(13,'2026_08_12_000010_create_anekdot_lampirans_table',1),
(14,'2026_08_12_000011_create_plannings_table',1),
(15,'2026_08_12_000012_create_kalender_akademiks_table',1),
(16,'2026_08_12_000013_create_assessments_table',1),
(17,'2026_08_12_000014_create_narrative_banks_table',1),
(18,'2026_08_12_000015_create_portfolios_table',1),
(19,'2026_08_12_000016_create_diplomas_table',1),
(20,'2026_08_12_000017_create_tagihan_spps_table',1),
(21,'2026_08_12_000018_create_pembayaran_spps_table',1),
(22,'2026_08_12_000019_create_expense_categories_table',1),
(23,'2026_08_12_000020_create_expenses_table',1),
(24,'2026_08_12_000021_create_expense_receipts_table',1),
(25,'2026_08_12_000022_create_reimbursements_table',1),
(26,'2026_08_12_000023_create_expense_status_histories_table',1),
(27,'2026_08_12_000024_create_supervisis_table',1),
(28,'2026_08_12_000025_create_supervisi_details_table',1),
(29,'2026_08_12_000026_create_asets_table',1),
(30,'2026_08_12_000027_create_aset_riwayats_table',1),
(31,'2026_08_12_000028_create_subscription_plans_table',1),
(32,'2026_08_12_120345_create_permission_tables',1),
(33,'2026_08_12_120347_create_activity_log_table',1),
(34,'2026_08_12_120348_add_event_column_to_activity_log_table',1),
(35,'2026_08_12_120348_create_personal_access_tokens_table',1),
(36,'2026_08_12_120349_add_batch_uuid_column_to_activity_log_table',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(1,'App\\Models\\User',1),
(2,'App\\Models\\User',2),
(3,'App\\Models\\User',3),
(4,'App\\Models\\User',4),
(5,'App\\Models\\User',5),
(6,'App\\Models\\User',6),
(7,'App\\Models\\User',7);

/*Table structure for table `narrative_banks` */

DROP TABLE IF EXISTS `narrative_banks`;

CREATE TABLE `narrative_banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `elemen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rentang_nilai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sangat Baik',
  `template_narasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `narrative_banks_school_id_foreign` (`school_id`),
  CONSTRAINT `narrative_banks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `narrative_banks` */

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `pembayaran_spps` */

DROP TABLE IF EXISTS `pembayaran_spps`;

CREATE TABLE `pembayaran_spps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tagihan_spp_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'User who uploaded or verified',
  `tanggal_bayar` date NOT NULL,
  `nominal_bayar` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('Manual QRIS','Transfer Bank','Cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Manual QRIS',
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `status_verifikasi` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_spps_tagihan_spp_id_foreign` (`tagihan_spp_id`),
  KEY `pembayaran_spps_school_id_foreign` (`school_id`),
  KEY `pembayaran_spps_siswa_id_foreign` (`siswa_id`),
  KEY `pembayaran_spps_user_id_foreign` (`user_id`),
  CONSTRAINT `pembayaran_spps_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_spps_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_spps_tagihan_spp_id_foreign` FOREIGN KEY (`tagihan_spp_id`) REFERENCES `tagihan_spps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_spps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pembayaran_spps` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'manage-saas','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(2,'manage-yayasan','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(3,'manage-school','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(4,'manage-users','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(5,'manage-master-data','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(6,'manage-presensi','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(7,'self-presensi','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(8,'manage-anekdot','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(9,'manage-planning','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(10,'manage-assessments','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(11,'manage-erapor','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(12,'manage-spp','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(13,'upload-spp-bukti','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(14,'verify-spp-bukti','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(15,'manage-expenses','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(16,'approve-expenses','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(17,'manage-reimbursements','web','2026-08-12 12:35:44','2026-08-12 12:35:44'),
(18,'manage-supervisi','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(19,'manage-assets','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(20,'manage-roles','web','2026-08-12 12:48:59','2026-08-12 12:48:59');

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `plannings` */

DROP TABLE IF EXISTS `plannings`;

CREATE TABLE `plannings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `rombel_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minggu_ke` int NOT NULL DEFAULT '1',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `capaian_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `tujuan_pembelajaran` text COLLATE utf8mb4_unicode_ci,
  `kegiatan` text COLLATE utf8mb4_unicode_ci,
  `evaluasi` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Draft','Submitted','Approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plannings_school_id_foreign` (`school_id`),
  KEY `plannings_guru_id_foreign` (`guru_id`),
  KEY `plannings_rombel_id_foreign` (`rombel_id`),
  CONSTRAINT `plannings_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plannings_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plannings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `plannings` */

/*Table structure for table `portfolios` */

DROP TABLE IF EXISTS `portfolios`;

CREATE TABLE `portfolios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `judul_karya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `portfolios_school_id_foreign` (`school_id`),
  KEY `portfolios_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `portfolios_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `portfolios_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `portfolios` */

/*Table structure for table `presensi_logs` */

DROP TABLE IF EXISTS `presensi_logs`;

CREATE TABLE `presensi_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `presensi_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presensi_logs_presensi_id_foreign` (`presensi_id`),
  KEY `presensi_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `presensi_logs_presensi_id_foreign` FOREIGN KEY (`presensi_id`) REFERENCES `presensis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `presensi_logs` */

/*Table structure for table `presensis` */

DROP TABLE IF EXISTS `presensis`;

CREATE TABLE `presensis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `rombel_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa','Terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `entry_type` enum('guru_manual','siswa_mandiri') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guru_manual',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presensis_siswa_id_tanggal_unique` (`siswa_id`,`tanggal`),
  KEY `presensis_school_id_foreign` (`school_id`),
  KEY `presensis_rombel_id_foreign` (`rombel_id`),
  CONSTRAINT `presensis_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensis_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `presensis` */

/*Table structure for table `reimbursements` */

DROP TABLE IF EXISTS `reimbursements`;

CREATE TABLE `reimbursements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `expense_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL COMMENT 'Treasurer who processed reimbursement',
  `nominal_reimburse` decimal(12,2) NOT NULL,
  `tanggal_pencairan` date NOT NULL,
  `metode_transfer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reimbursements_school_id_foreign` (`school_id`),
  KEY `reimbursements_expense_id_foreign` (`expense_id`),
  KEY `reimbursements_user_id_foreign` (`user_id`),
  CONSTRAINT `reimbursements_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reimbursements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reimbursements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `reimbursements` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values 
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(17,1),
(18,1),
(19,1),
(20,1),
(2,2),
(3,2),
(18,2),
(3,3),
(4,3),
(5,3),
(6,3),
(8,3),
(9,3),
(10,3),
(11,3),
(12,3),
(14,3),
(16,3),
(18,3),
(19,3),
(12,4),
(14,4),
(15,4),
(16,4),
(17,4),
(6,5),
(8,5),
(9,5),
(10,5),
(11,5),
(15,5),
(13,6),
(7,7);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'Superadmin','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(2,'Yayasan Admin','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(3,'School Admin','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(4,'Bendahara','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(5,'Guru','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(6,'Orang Tua','web','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(7,'Siswa','web','2026-08-12 12:35:45','2026-08-12 12:35:45');

/*Table structure for table `rombels` */

DROP TABLE IF EXISTS `rombels`;

CREATE TABLE `rombels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned DEFAULT NULL COMMENT 'Wali Kelas',
  `nama_rombel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rombels_school_id_foreign` (`school_id`),
  KEY `rombels_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `rombels_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rombels_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `rombels` */

insert  into `rombels`(`id`,`school_id`,`tahun_ajaran_id`,`guru_id`,`nama_rombel`,`tingkat`,`created_at`,`updated_at`) values 
(1,1,1,1,'TK-A1 (Bintang)','A','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `schools` */

DROP TABLE IF EXISTS `schools`;

CREATE TABLE `schools` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npsn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenjang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TK/PAUD',
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kop_header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qris_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_accounts` text COLLATE utf8mb4_unicode_ci,
  `fonnte_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_sekolah_nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_sekolah_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bendahara_nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bendahara_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schools_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `schools_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `schools` */

insert  into `schools`(`id`,`tenant_id`,`name`,`npsn`,`jenjang`,`address`,`phone`,`email`,`logo`,`kop_header`,`qris_image`,`bank_accounts`,`fonnte_token`,`kepala_sekolah_nama`,`kepala_sekolah_nip`,`bendahara_nama`,`bendahara_nip`,`status`,`created_at`,`updated_at`) values 
(1,1,'TK AR RIDHO MANDAH','69789408','PAUD/TK/RA','Jl. Raya Mandah No. 17, Dusun Mandah Induk Desa Mandah Kec. Natar Kab. Lampung Selatan, Lampung, 35362.','0815 1192 5114','admin@tknpembina.sch.id',NULL,NULL,'demo/qris_sample.png','[{\"bank\":\"Bank Lampung\",\"account_number\":\"123-00-9988776-5\",\"account_name\":\"TK AR RIDHO MANDAH\"}]','DEMO_FONNTE_TOKEN_123','Diah Anika Fahrani, S.Pd., Gr., M.Pd.','19990316 202121 2 0001','Rosmayani Erwin, S.Pd.','19820510 200801 1 004','active','2026-08-12 12:35:45','2026-08-12 12:57:33');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('fhwAQpRRfBsjz3AauTLWNR9gUaKoeHGpVnMAOGSp',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWRoZUtMOVRWa0RaVnpsVGlBSHo3NmF2anJUMkFKcWNsNVNZVnhoVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9zZWtvbGFoa3UtYXBwcy50ZXN0L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==',1786539551),
('O2w4IxvSWjEmjyAFJ1VYfLMEtAHqIDdC8bWBgJoT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFB3UGxHdEl2eFBjQU82d2g1bVFVeVl5cU5wcUxVTXdEVFBuRU1naSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9zZWtvbGFoa3UtYXBwcy50ZXN0IjtzOjU6InJvdXRlIjtzOjc6ImxhbmRpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1787052497);

/*Table structure for table `siswas` */

DROP TABLE IF EXISTS `siswas`;

CREATE TABLE `siswas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `rombel_id` bigint unsigned DEFAULT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_panggilan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'L',
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nama_ortu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp_ortu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Aktif','Lulus','Pindah','Drop Out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siswas_school_id_foreign` (`school_id`),
  KEY `siswas_user_id_foreign` (`user_id`),
  KEY `siswas_rombel_id_foreign` (`rombel_id`),
  KEY `siswas_nisn_index` (`nisn`),
  CONSTRAINT `siswas_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `siswas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `siswas` */

insert  into `siswas`(`id`,`school_id`,`user_id`,`rombel_id`,`nisn`,`nik`,`nama_lengkap`,`nama_panggilan`,`jenis_kelamin`,`tempat_lahir`,`tanggal_lahir`,`nama_ortu`,`no_hp_ortu`,`alamat`,`foto`,`status`,`created_at`,`updated_at`) values 
(1,1,7,1,'0011223344','320109988776655','Muhammad Bintang Ramadhan','Bintang','L','Jakarta','2020-05-15','Budi Santoso','081234567895','Jl. Melati No. 88 Sukajadi',NULL,'Aktif','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `subscription_plans` */

DROP TABLE IF EXISTS `subscription_plans`;

CREATE TABLE `subscription_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `billing_cycle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `max_siswas` int NOT NULL DEFAULT '50',
  `max_schools` int NOT NULL DEFAULT '1',
  `features` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `subscription_plans` */

insert  into `subscription_plans`(`id`,`name`,`code`,`price`,`billing_cycle`,`max_siswas`,`max_schools`,`features`,`description`,`is_active`,`created_at`,`updated_at`) values 
(1,'Free / Starter','free',0.00,'monthly',50,1,'[\"presensi\", \"erapor\", \"anekdot\"]','Paket dasar gratis untuk sekolah kecil / PAUD. Maksimal 50 siswa.',1,'2026-08-12 12:35:45','2026-08-12 12:35:45'),
(2,'Professional (Pro)','pro',199000.00,'monthly',0,1,'[\"presensi\", \"erapor\", \"anekdot\", \"spp_qris\", \"bendaharaku\", \"fonnte_wa\"]','Fitur lengkap untuk sekolah menengah (SPP QRIS, BendaharaKu, WA Fonnte, Unlimited Siswa).',1,'2026-08-12 12:35:45','2026-08-12 12:35:45'),
(3,'Enterprise (Yayasan)','enterprise',499000.00,'monthly',0,5,'[\"presensi\", \"erapor\", \"anekdot\", \"spp_qris\", \"bendaharaku\", \"fonnte_wa\", \"multi_school\", \"custom_branding\"]','Solusi lengkap untuk Yayasan dengan hingga 5 unit sekolah.',1,'2026-08-12 12:35:45','2026-08-12 12:35:45');

/*Table structure for table `supervisi_details` */

DROP TABLE IF EXISTS `supervisi_details`;

CREATE TABLE `supervisi_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supervisi_id` bigint unsigned NOT NULL,
  `aspek_penilaian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor` int NOT NULL DEFAULT '4',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supervisi_details_supervisi_id_foreign` (`supervisi_id`),
  CONSTRAINT `supervisi_details_supervisi_id_foreign` FOREIGN KEY (`supervisi_id`) REFERENCES `supervisis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `supervisi_details` */

/*Table structure for table `supervisis` */

DROP TABLE IF EXISTS `supervisis`;

CREATE TABLE `supervisis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `supervisor_id` bigint unsigned NOT NULL COMMENT 'KS or Yayasan User',
  `supervisee_id` bigint unsigned NOT NULL COMMENT 'Teacher or KS User',
  `tanggal` date NOT NULL,
  `jenis` enum('Akademik','Manajerial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Akademik',
  `total_skor` decimal(5,2) NOT NULL DEFAULT '0.00',
  `catatan_umpan_balik` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supervisis_school_id_foreign` (`school_id`),
  KEY `supervisis_supervisor_id_foreign` (`supervisor_id`),
  KEY `supervisis_supervisee_id_foreign` (`supervisee_id`),
  CONSTRAINT `supervisis_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supervisis_supervisee_id_foreign` FOREIGN KEY (`supervisee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `supervisis_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `supervisis` */

/*Table structure for table `tagihan_spps` */

DROP TABLE IF EXISTS `tagihan_spps`;

CREATE TABLE `tagihan_spps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `tahun_ajaran_id` bigint unsigned NOT NULL,
  `bulan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `nominal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `potongan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_tagihan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('Belum Lunas','Menunggu Verifikasi','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Lunas',
  `jatuh_tempo` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tagihan_spps_school_id_foreign` (`school_id`),
  KEY `tagihan_spps_siswa_id_foreign` (`siswa_id`),
  KEY `tagihan_spps_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  CONSTRAINT `tagihan_spps_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tagihan_spps_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tagihan_spps_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tagihan_spps` */

insert  into `tagihan_spps`(`id`,`school_id`,`siswa_id`,`tahun_ajaran_id`,`bulan`,`tahun`,`nominal`,`potongan`,`total_tagihan`,`status`,`jatuh_tempo`,`created_at`,`updated_at`) values 
(1,1,1,1,'Agustus',2026,150000.00,0.00,150000.00,'Belum Lunas','2026-08-10','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `tahun_ajarans` */

DROP TABLE IF EXISTS `tahun_ajarans`;

CREATE TABLE `tahun_ajarans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tahun_ajarans_school_id_foreign` (`school_id`),
  CONSTRAINT `tahun_ajarans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tahun_ajarans` */

insert  into `tahun_ajarans`(`id`,`school_id`,`name`,`semester`,`is_active`,`start_date`,`end_date`,`created_at`,`updated_at`) values 
(1,1,'2025/2026','1',1,'2025-07-15','2025-12-20','2026-08-12 12:35:46','2026-08-12 12:35:46');

/*Table structure for table `tenants` */

DROP TABLE IF EXISTS `tenants`;

CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_tier` enum('free','pro','yayasan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `subscription_status` enum('active','expired','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `subscription_expires_at` date DEFAULT NULL,
  `subscribed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subscription_plan_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_code_unique` (`code`),
  KEY `tenants_subscription_plan_id_foreign` (`subscription_plan_id`),
  CONSTRAINT `tenants_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tenants` */

insert  into `tenants`(`id`,`name`,`code`,`subscription_tier`,`status`,`subscription_status`,`subscription_expires_at`,`subscribed_at`,`notes`,`created_at`,`updated_at`,`subscription_plan_id`) values 
(1,'Yayasan Pendidikan Nusantara','YPNUSANTARA','pro','active','active','2027-08-12',NULL,NULL,'2026-08-12 12:35:45','2026-08-12 12:35:45',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_tenant_id_index` (`tenant_id`),
  KEY `users_school_id_index` (`school_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`tenant_id`,`school_id`,`name`,`email`,`phone`,`avatar`,`is_active`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,1,1,'Superadmin SaaS','admin@sekolahku.id','081234567890',NULL,1,NULL,'$2y$12$45.BEgNBEx.P8xiMYz8LE.Tv/PeIcrFwD84jHF29fD9Zk2rtMlxlK','BiSSRTbjX8j4rSn1S36zzLee1iianwzBE7BT51oBG3NHhkWeh7gyqZ4U013I','2026-08-12 12:35:45','2026-08-12 12:35:45'),
(2,1,1,'Drs. H. M. Ridho (Yayasan)','yayasan@sekolahku.id','081234567891',NULL,1,NULL,'$2y$12$DE7l6qRqFTLqOTpykuq/nuMT6HCJt8asn6UxGON1KBTOjbievew1a',NULL,'2026-08-12 12:35:45','2026-08-12 12:35:45'),
(3,1,1,'Dra. Hj. Siti Rahmah, M.Pd.','headmaster@tknpembina.sch.id','081234567892',NULL,1,NULL,'$2y$12$MeM0PYN91ZXUpj8ou0tRW.v8GN4lyLEFJkDfrYY/lBJMGZppbnGxa',NULL,'2026-08-12 12:35:45','2026-08-12 12:35:45'),
(4,1,1,'Ahmadi, S.E. (Bendahara)','bendahara@tknpembina.sch.id','081234567893',NULL,1,NULL,'$2y$12$6vL3D/TVEjhYpNAaCu5q0.iNPpRQHYyDfk9W3wZYAH0Yp8R5sQrT6',NULL,'2026-08-12 12:35:46','2026-08-12 12:35:46'),
(5,1,1,'Nurhayati, S.Pd. (Guru TK A)','guru@tknpembina.sch.id','081234567894',NULL,1,NULL,'$2y$12$UjjsO4k/kquplxon.3hmAOlH.KS25Gi3e5k/G2tSu803sN/fNZSO6',NULL,'2026-08-12 12:35:46','2026-08-12 12:35:46'),
(6,1,1,'Budi Santoso (Orang Tua)','ortu@tknpembina.sch.id','081234567895',NULL,1,NULL,'$2y$12$uqknqrYFIzrsHZNRSVKOAOesXWBWCQrFylpW7jnxu6Cync0aUbr86',NULL,'2026-08-12 12:35:46','2026-08-12 12:35:46'),
(7,1,1,'Muhammad Bintang Ramadhan','siswa@tknpembina.sch.id','081234567896',NULL,1,NULL,'$2y$12$Ga1dBq5X32QyCoBUCeExpuv9yFJyYXCwe6r/DiHs7R3QJ8AurhcbK',NULL,'2026-08-12 12:35:46','2026-08-12 12:35:46');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
