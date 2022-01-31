-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2022 at 03:28 PM
-- Server version: 10.3.32-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gaweyanc_adinda`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikels`
--

CREATE TABLE `artikels` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_artikel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_keanggotaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_artikel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_pembuka_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teks_pembuka_artikel` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `teks_isi_artikel` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_artikel` date DEFAULT NULL,
  `geolocation_artikel` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_tampilan_artikel` tinyint(1) DEFAULT NULL,
  `status_verifikasi_artikel` tinyint(1) DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verificated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penulis_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penyunting_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pengawas_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelompok_artikel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_artikel` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `divisis`
--

CREATE TABLE `divisis` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_organisasi` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_divisi` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_divisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_divisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_divisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_divisi` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisis`
--

INSERT INTO `divisis` (`id_entri`, `id_organisasi`, `id_divisi`, `nama_divisi`, `alamat_divisi`, `logo_divisi`, `keterangan_divisi`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '906d70d7-5771-42c5-ac46-95c24ec1e140', 'bd21122c-e98b-4672-b644-d76c7c7a5d0e', 'FUNGSI STATISTIK SOSIAL', NULL, '[bd21122c-e98b-4672-b644-d76c7c7a5d0e] FUNGSI STATISTIK SOSIAL.png', NULL, 'admin@adinda.com', 'admin@adinda.com', NULL, '2022-01-25 05:03:39', '2022-01-25 05:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `infografis`
--

CREATE TABLE `infografis` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inserted_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `index_infografis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags_infografis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tautan_infografis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tampilan_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inovations`
--

CREATE TABLE `inovations` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_inovation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_keanggotaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_inovation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ikon_inovation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_inovation` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satker_asal_inovation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak_hubung_inovation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_inovation` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konten_inovation` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tautan_materi_inovation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tautan_kode_inovation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_tampilan_inovation` tinyint(1) DEFAULT NULL,
  `status_verifikasi_inovation` tinyint(1) DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervised_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keanggotaans`
--

CREATE TABLE `keanggotaans` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_keanggotaan` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_keanggotaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_keanggotaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Member',
  `permissions_keanggotaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2021_08_21_060051_create_permission_tables', 1),
(7, '2021_08_24_083157_create_infografis_table', 1),
(9, '2022_01_13_142105_create_organisasis_table', 1),
(10, '2022_01_13_153251_create_divisis_table', 1),
(11, '2022_01_13_153338_create_tims_table', 1),
(12, '2022_01_19_034657_create_keanggotaans_table', 1),
(18, '2021_08_15_213341_create_artikels_table', 3),
(19, '2022_01_10_041941_create_inovations_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 22),
(1, 'App\\Models\\User', 44),
(2, 'App\\Models\\User', 22),
(2, 'App\\Models\\User', 44),
(3, 'App\\Models\\User', 22),
(3, 'App\\Models\\User', 44),
(4, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 44),
(5, 'App\\Models\\User', 22),
(5, 'App\\Models\\User', 44),
(6, 'App\\Models\\User', 22),
(6, 'App\\Models\\User', 44),
(7, 'App\\Models\\User', 22),
(7, 'App\\Models\\User', 44),
(8, 'App\\Models\\User', 22),
(8, 'App\\Models\\User', 44),
(9, 'App\\Models\\User', 44),
(10, 'App\\Models\\User', 44),
(11, 'App\\Models\\User', 44),
(12, 'App\\Models\\User', 44),
(13, 'App\\Models\\User', 44),
(14, 'App\\Models\\User', 44),
(15, 'App\\Models\\User', 44),
(16, 'App\\Models\\User', 44),
(17, 'App\\Models\\User', 44),
(18, 'App\\Models\\User', 44),
(19, 'App\\Models\\User', 44),
(20, 'App\\Models\\User', 44),
(21, 'App\\Models\\User', 44),
(22, 'App\\Models\\User', 44),
(23, 'App\\Models\\User', 44),
(24, 'App\\Models\\User', 44);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organisasis`
--

CREATE TABLE `organisasis` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_organisasi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_organisasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_organisasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_organisasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_organisasi` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organisasis`
--

INSERT INTO `organisasis` (`id_entri`, `id_organisasi`, `nama_organisasi`, `alamat_organisasi`, `logo_organisasi`, `keterangan_organisasi`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '906d70d7-5771-42c5-ac46-95c24ec1e140', 'BADAN PUSAT STATISTIK PROVINSI KALIMANTAN TENGAH', 'Jalan Kapten Piere Tenden No 6, Pahandut, Palangka Raya', '[906d70d7-5771-42c5-ac46-95c24ec1e140] BADAN PUSAT STATISTIK PROVINSI KALIMANTAN TENGAH.png', NULL, 'admin@adinda.com', 'admin@adinda.com', NULL, '2022-01-25 04:47:08', '2022-01-25 04:47:08'),
(2, 'ca303ef3-dab6-4ab2-ae14-8ffd0c9f55af', 'BADAN PUSAT STATISTIK KOTA PALANGKA RAYA', 'Pahandut, Palangka Raya, Kalimantan Tengah', '[ca303ef3-dab6-4ab2-ae14-8ffd0c9f55af] BADAN PUSAT STATISTIK KOTA PALANGKA RAYA.png', NULL, 'admin@adinda.com', 'admin@adinda.com', NULL, '2022-01-25 04:47:49', '2022-01-25 04:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'articles.create', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(2, 'articles.read', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(3, 'articles.update', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(4, 'articles.delete', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(5, 'articles.publish', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(6, 'articles.unpublish', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(7, 'articles.restore', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(8, 'articles.verification', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(9, 'users.create', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(10, 'users.read', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(11, 'users.update', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(12, 'users.delete', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(13, 'users.ban', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(14, 'users.unban', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(15, 'users.restore', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(16, 'users.verification', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(17, 'inovations.create', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(18, 'inovations.read', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(19, 'inovations.update', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(20, 'inovations.delete', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(21, 'inovations.publish', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(22, 'inovations.unpublish', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(23, 'inovations.restore', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(24, 'inovations.verification', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(1, 'articles.create', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(2, 'articles.read', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(3, 'articles.update', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(4, 'articles.delete', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(5, 'articles.publish', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(6, 'articles.unpublish', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(7, 'articles.restore', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(8, 'articles.verification', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(9, 'users.create', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(10, 'users.read', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(11, 'users.update', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(12, 'users.delete', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(13, 'users.ban', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(14, 'users.unban', 'web', '2022-01-20 19:41:50', '2022-01-20 19:41:50'),
(15, 'users.restore', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(16, 'users.verification', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(17, 'inovations.create', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(18, 'inovations.read', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(19, 'inovations.update', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(20, 'inovations.delete', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(21, 'inovations.publish', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(22, 'inovations.unpublish', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(23, 'inovations.restore', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51'),
(24, 'inovations.verification', 'web', '2022-01-20 19:41:51', '2022-01-20 19:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'browser', '1d13a4fd73bf8769aecac7887ea13cf90e1fdee72980efc83715993d7cbd6ba2', '[\"*\"]', '2022-01-25 04:41:50', '2022-01-25 04:03:31', '2022-01-25 04:41:50'),
(3, 'App\\Models\\User', 1, 'browser', '9aaf452b9f4460489a992c4b716937136773497c8af92641ed055fc3bafd2f93', '[\"*\"]', '2022-01-25 07:16:35', '2022-01-25 05:05:40', '2022-01-25 07:16:35'),
(4, 'App\\Models\\User', 22, 'browser', '0612ae9ca9b675a64c3bddcc14cb47d29aa738ffccf8a8465e83aca8e84071f0', '[\"*\"]', '2022-01-25 07:16:46', '2022-01-25 07:15:52', '2022-01-25 07:16:46'),
(5, 'App\\Models\\User', 1, 'browser', 'cc65a976e19225ff34a7705c6a7e9627f3cf3ea10d2abd6529e9bc5c43278e3a', '[\"*\"]', '2022-01-25 08:26:04', '2022-01-25 07:57:12', '2022-01-25 08:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(2, 'Admin', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(3, 'AdminOrganisasi', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(4, 'AdminDivisi', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(5, 'AdminTim', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(1, 'SuperAdmin', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(2, 'Admin', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(3, 'AdminOrganisasi', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(4, 'AdminDivisi', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49'),
(5, 'AdminTim', 'web', '2022-01-20 19:41:49', '2022-01-20 19:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(14, 5),
(15, 5),
(16, 5),
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(14, 5),
(15, 5),
(16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tims`
--

CREATE TABLE `tims` (
  `id_entri` bigint(20) UNSIGNED NOT NULL,
  `id_organisasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_divisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_tim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_tim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_tim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_tim` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tims`
--

INSERT INTO `tims` (`id_entri`, `id_organisasi`, `id_divisi`, `id_tim`, `nama_tim`, `logo_tim`, `alamat_tim`, `keterangan_tim`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '906d70d7-5771-42c5-ac46-95c24ec1e140', 'null', '2564653c-dc50-484b-aa64-c3e7209faf49', 'Tim Reformasi Birokrasi BPS', '[2564653c-dc50-484b-aa64-c3e7209faf49] Tim Reformasi Birokrasi BPS.png', NULL, NULL, 'admin@adinda.com', 'admin@adinda.com', NULL, '2022-01-25 05:04:05', '2022-01-25 05:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `status_account`, `email_verified_at`, `password`, `deleted_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Adinda', 'adminadinda', 'admin@adinda.com', 'active', NULL, '$2y$10$JhoUqEWmg/c/kR7x6xLpReB1PeM0gN3lTdHnUcGoT9nU3R0J7y1x.', NULL, NULL, '2022-01-20 12:41:49', '2022-01-20 12:41:49'),
(2, 'Dalimin Bakiman Habibi', 'purnawati.mahdi', 'ghandayani@yahoo.com', 'active', NULL, '$2y$10$21ZCrjwJPdP6/Bzj2rcZV.HKPlAT6gaMnOA.I/LljFaOdrrwUDyWC', NULL, NULL, NULL, NULL),
(3, 'Maya Agustina S.Kom', 'farah89', 'ramadan.chelsea@yahoo.co.id', 'active', NULL, '$2y$10$dQ/PlXo6S70DKDP8ZNT9Re1FeTM3ironBKYeUZfRusttoHGT9bnS2', NULL, NULL, NULL, NULL),
(4, 'Gina Ida Hartati S.Ked', 'paris.mayasari', 'galar.laksmiwati@gunawan.com', 'active', NULL, '$2y$10$9z6gPxBTlKavpcXoV0cSgO4WcVTTX/u9PKb09U1jTLiD878C7iZ6m', NULL, NULL, NULL, NULL),
(5, 'Gawati Astuti', 'nasyidah.kurnia', 'januar.sabrina@gmail.com', 'active', NULL, '$2y$10$5mxyNTLm9q4Mfhv8xSZSXOwY/lv.wXuM1ly7hu/b2fvqNldekBbIy', NULL, NULL, NULL, NULL),
(6, 'Hafshah Uyainah', 'rahimah.maya', 'qmaryadi@susanti.my.id', 'active', NULL, '$2y$10$vXC/AL7wXgOctudJbiqTPuOch1yvF6TOJ.HCrAdcftlEz/LEF0V9S', NULL, NULL, NULL, NULL),
(7, 'Maria Laksita S.I.Kom', 'bardianto', 'riyanti.rangga@yahoo.com', 'active', NULL, '$2y$10$yLf2OBoDZgpN0INt9oHe9OzBRSwj2AlnRW3KU.d4a2ZfRBq6J.YOS', NULL, NULL, NULL, NULL),
(8, 'Cahyo Saiful Winarno S.T.', 'iutami', 'ilyas78@gmail.com', 'active', NULL, '$2y$10$yMU98deK5j67efzhzZdXUOYlVm4A/skY88sQlGnleCKAz1/Sh7TTC', NULL, NULL, NULL, NULL),
(9, 'Elon Ramadan S.E.I', 'halimah.shakila', 'abudiyanto@nurdiyanti.tv', 'active', NULL, '$2y$10$2K3qO8c2RuduDX07bxeAD.YlaO6Qq/54qLuDiuSmrz8b.cS/HfjoO', NULL, NULL, NULL, NULL),
(10, 'Wage Lurhur Pangestu S.H.', 'cawisono98', 'jamal13@gunarto.go.id', 'active', NULL, '$2y$10$5cYXTmhzJmLX0ZqjXs.vbONHXH9htLl6skuKrP.Jseuycux.RJh8m', NULL, NULL, NULL, NULL),
(11, 'Rahayu Nuraini M.Ak', 'emin77', 'latupono.vanesa@manullang.ac.id', 'active', NULL, '$2y$10$Muf8BursYnCjQBsDRc6BU.dbHgJUouVYjwSw7sBkih/LQV9cp44V6', NULL, NULL, NULL, NULL),
(12, 'Prima Nainggolan', 'ayolanda', 'cengkir01@rahayu.co.id', 'active', NULL, '$2y$10$YMVcIHJ2VVrTu1Wme7VhRO6KvsHfgIQJEbC/H8zXG6B0iseBZKhYe', NULL, NULL, NULL, NULL),
(13, 'Hasna Faizah Wulandari', 'jessica25', 'qirawan@hasanah.sch.id', 'active', NULL, '$2y$10$aDnYIiw9VoOtO5b66Kgalu0AUkI0sdCZsRm45zuf6R/TTh74nm4Oa', NULL, NULL, NULL, NULL),
(14, 'Salman Harja Pradipta S.I.Kom', 'zprasetya', 'musamah@nuraini.co.id', 'active', NULL, '$2y$10$ae1asipfGm6aeNcfqyWbIuVlilIulV1NaZ4rMp3j4ufPGRJRHcjR2', NULL, NULL, NULL, NULL),
(15, 'Artawan Irsad Nababan', 'prayitna13', 'hafshah08@budiyanto.mil.id', 'active', NULL, '$2y$10$m7uOWCeRVqZviiCk5Jl3OumkycvWvhRXVYoDD1bi0HqX9KxDvYwoG', NULL, NULL, NULL, NULL),
(16, 'Cakrabirawa Nalar Prasetya M.Farm', 'uli.ardianto', 'sirait.cakrawala@gmail.com', 'active', NULL, '$2y$10$iLXFJAiG.tGtGXUTvLcDNOSYzX7kE2KA8PpU27RfyO4QxqzGYh4gy', NULL, NULL, NULL, NULL),
(17, 'Siska Hariyah', 'parman53', 'nabila47@gmail.co.id', 'active', NULL, '$2y$10$cMJi3BLuWDT6dJbyhrUvZu/i7HTd0mxiQGdrdLIbEmz4q0bu.vHTG', NULL, NULL, NULL, NULL),
(18, 'Cornelia Palastri', 'estiono.tamba', 'awasita@kuswoyo.sch.id', 'active', NULL, '$2y$10$/hgt1kzXFGubQ9YiGhKkeO4u/MlM17bG0KuNG0jD8M3vETrGVkZ/m', NULL, NULL, NULL, NULL),
(19, 'Chandra Wahyudin', 'ajeng81', 'irahimah@fujiati.mil.id', 'active', NULL, '$2y$10$iGVqIAayGUUezFfBrxNARuAsYR6CvUSN0PbrjV8RTCKyFqxBFFjJW', NULL, NULL, NULL, NULL),
(20, 'Zelaya Nasyiah', 'bsitompul', 'sakti77@yahoo.co.id', 'active', NULL, '$2y$10$dJH7lRP3VHVC647xrFmXYuJyYTokNUYDNMrtxxL7dl7vQYt64Yw92', NULL, NULL, NULL, NULL),
(21, 'Eli Winarsih', 'bakidin.wibisono', 'hkuswoyo@gmail.com', 'active', NULL, '$2y$10$ulbu0ZfB91s.eC0z0.NUmubglQNN5mMdqqygXRJQzumbvB74VRQJC', NULL, NULL, NULL, NULL),
(22, 'Bagus Setiawan', 'saptono.karimah', 'pudjiastuti.panji@yahoo.com', 'active', NULL, '$2y$10$0wcx.e4mVTniM23mgTKf4er4CjqqimO.iXBLiqiEOjXbDu8uO72gS', NULL, NULL, NULL, NULL),
(23, 'Wulan Sari Uyainah S.Psi', 'suartini.hafshah', 'umay01@agustina.org', 'active', NULL, '$2y$10$2mq8bUL6iTjPth5HZk2cdOvlM2N3Oa5f4K49v/soUbVnL/2ust0Z2', NULL, NULL, NULL, NULL),
(24, 'Samiah Kezia Safitri', 'hadi.pratama', 'wsetiawan@waskita.in', 'active', NULL, '$2y$10$afl6rP22uA.sUjlIUVki3.s3oiSA2pbYJkBmzKUel256UH8jcw5v.', NULL, NULL, NULL, NULL),
(25, 'Septi Rahimah S.Gz', 'cagak89', 'hhastuti@yahoo.com', 'active', NULL, '$2y$10$j8zXOaJk2aByQ4CIPkNsleoH9HrOHwJ2/bBNyE3eykNiUF8yk0yiO', NULL, NULL, NULL, NULL),
(26, 'Aditya Januar', 'hesti36', 'irnanto93@santoso.asia', 'active', NULL, '$2y$10$N1pA.2qvzKQz6gerMgt9juzi0AUaVDZ7h5kHUA/nSGoJrbew3MubS', NULL, NULL, NULL, NULL),
(27, 'Rusman Tomi Nashiruddin', 'virman32', 'ratih78@lazuardi.com', 'active', NULL, '$2y$10$geDRfVpEIvwiRw9jd4EG.eU6pexUT9SKKRIj6MpTPjJ1AGkPoqX6u', NULL, NULL, NULL, NULL),
(28, 'Sakura Permata', 'lnuraini', 'marbun.kania@gmail.co.id', 'active', NULL, '$2y$10$xText87HeiQqIajlWIDc8eg3X9egquL7z.RWshpleIU.dIfZORCOW', NULL, NULL, NULL, NULL),
(29, 'Irwan Firmansyah', 'wijayanti.diana', 'zwahyudin@yahoo.co.id', 'active', NULL, '$2y$10$8z10gYAFAHgZ5tPr7R7BKOHDDtEnlWMErUmRM616RyIrzgYeaFoGC', NULL, NULL, NULL, NULL),
(30, 'Okto Jailani', 'maryati.argono', 'mfujiati@sudiati.go.id', 'active', NULL, '$2y$10$8W2IXRkV/WF2n2GHPT2QQ.csygs87NW3OWtvoAwKwDdeN7J/KbBwO', NULL, NULL, NULL, NULL),
(31, 'Ridwan Wibisono', 'amalia58', 'unggul.situmorang@yahoo.com', 'active', NULL, '$2y$10$R795hZK2TPkDX8Bl3bKDOenO.CmpGYk6o5OpWIGrovFmka5ivCOoC', NULL, NULL, NULL, NULL),
(32, 'Faizah Usada', 'wahyu.siregar', 'osuryatmi@hastuti.in', 'active', NULL, '$2y$10$C74iHqTa0AUTBNsfC6l1V..79YAwFKA5XRwzJ8e2PUU1cF/z4FNqu', NULL, NULL, NULL, NULL),
(33, 'Ellis Hasanah M.Ak', 'bwidiastuti', 'kamila93@mansur.in', 'active', NULL, '$2y$10$.84dpPd8a9lFyIHFLJsOQeX1MOVXTKJYH3YP0YBESp1GWV0tHPN9m', NULL, NULL, NULL, NULL),
(34, 'Humaira Yuliarti S.E.I', 'jailani.daryani', 'warta.zulkarnain@hassanah.asia', 'active', NULL, '$2y$10$Eljfz6PPx1zF3WyZ9AzbmOMnw1IJRUP7HP.g5tmEgjW9QGncmVRWq', NULL, NULL, NULL, NULL),
(35, 'Siti Tari Laksmiwati M.Farm', 'enasyiah', 'ifa56@prayoga.co.id', 'active', NULL, '$2y$10$SZZTI3NDGNoIVQnUwwVKsO6FU.tRyNcXPUeWm0JBiHLsPfTZIn0LO', NULL, NULL, NULL, NULL),
(36, 'Jaya Muhammad Halim S.E.I', 'aryani.ida', 'vyulianti@marbun.asia', 'active', NULL, '$2y$10$EEUINFvFAVTDOdApNwuLC.c6kfUE6nIDqxqCh2HIiqEmncnMqrtpa', NULL, NULL, NULL, NULL),
(37, 'Upik Mahesa Dongoran S.Kom', 'harjasa70', 'syahrini.permata@suwarno.desa.id', 'active', NULL, '$2y$10$63jYToDANtRqE7lWoM4.9u/OvQCKhkbG437EI2WI0XXVvk4bt8ZFK', NULL, NULL, NULL, NULL),
(38, 'Karimah Widya Wahyuni', 'blaksmiwati', 'jaga.winarsih@yahoo.com', 'active', NULL, '$2y$10$.mshzMrbFyxMK9N.SBlQuetEXion3Hn7EFuzu43WEiImZdJL.3w9C', NULL, NULL, NULL, NULL),
(39, 'Mila Melani', 'gnasyiah', 'latupono.ibrani@yahoo.co.id', 'active', NULL, '$2y$10$siUHmHmh2uYS/Tc2.CWWEe1Bv/bC5ZQvbGdNLpLW4SNt9RMxAWIlG', NULL, NULL, NULL, NULL),
(40, 'Ira Irma Nuraini', 'uwijayanti', 'nharyanto@yahoo.com', 'active', NULL, '$2y$10$5VziUHLUIDcBzZ6I5DWbpuR0sjHdRLLpIFv7RQaKv1QAf9dA6Hmtu', NULL, NULL, NULL, NULL),
(41, 'Lala Silvia Mardhiyah', 'elisa.hasanah', 'rmanullang@yahoo.co.id', 'active', NULL, '$2y$10$5BLofysuF3ZDatEkcTdc8OaxmYcyi4j4.NaOMOn72h8viE2VxOHgO', NULL, NULL, NULL, NULL),
(42, 'Raisa Hariyah', 'qzulkarnain', 'sari79@wulandari.asia', 'active', NULL, '$2y$10$qpIIpqoIs4jyxnYUVSN2guH15NC6FP35fkAC5TdIjFTmrfNI2poJ2', NULL, NULL, NULL, NULL),
(43, 'Nrima Abyasa Hidayanto S.T.', 'cinta.salahudin', 'dkuswoyo@rahayu.biz.id', 'active', NULL, '$2y$10$z6O32hc.E7X330LmIKTc1uJnEosTF9hJ54KxlFYy/i3F814JMSe2S', NULL, NULL, NULL, NULL),
(44, 'Keisha Usamah', 'ilaksita', 'yulianti.yani@gmail.com', 'active', NULL, '$2y$10$Fw3ClThJq/jEJj2KZ510fOtqLcl4IQcQarXJiyWXlhnUCVqcMmBV2', NULL, NULL, NULL, NULL),
(45, 'Zelda Indah Astuti S.IP', 'kania08', 'cwahyuni@pradana.web.id', 'active', NULL, '$2y$10$2OSCY64j5djT5A4Xk9VqCeQGnG54zpXVxZXC4tqdJwDlp/UAX4zEy', NULL, NULL, NULL, NULL),
(46, 'Yani Sari Riyanti S.Pt', 'ssirait', 'bakidin62@gmail.com', 'active', NULL, '$2y$10$EHs6Fvifi572rwUjvG1uHeUUToDei5kbHS/8U6TDSq00wmMKVcVVq', NULL, NULL, NULL, NULL),
(47, 'Drajat Sitorus S.Kom', 'vastuti', 'shania.nasyidah@yahoo.co.id', 'active', NULL, '$2y$10$ZP9w.224C37FMHXi8gqHg.d8qakJH1n7qAGW51bcGQqJASFzHLGLO', NULL, NULL, NULL, NULL),
(48, 'Jati Daliman Januar', 'wibisono.galih', 'sirait.luluh@mahendra.in', 'active', NULL, '$2y$10$wJF7sKd3JLheK7JKmm5s7OjYDpmPpSa5YK0vZxEDBzMTu4e.SXEou', NULL, NULL, NULL, NULL),
(49, 'Bancar Rusman Sihombing', 'ana.firmansyah', 'yosef.purnawati@gmail.co.id', 'active', NULL, '$2y$10$m549XXt7JXOD4Eys502aF.ImOuBwR6K/UQ/mvifAvRfx8jqVMjgly', NULL, NULL, NULL, NULL),
(50, 'Dwi Saputra', 'almira.irawan', 'qmaulana@prastuti.org', 'active', NULL, '$2y$10$SnZ3JNLy/nZ03m1IGC5WeO.UZNb8IFEhq23NnHjNsh8W63UE7BmT.', NULL, NULL, NULL, NULL),
(51, 'Purwadi Hutasoit', 'widiastuti.lukman', 'nuraini.icha@yahoo.co.id', 'active', NULL, '$2y$10$P8KogeVeRF6nztXrnZ2v8utHM/ZFMf1sm9.s.HwUnfOiCjFy/wXFO', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikels`
--
ALTER TABLE `artikels`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `divisis`
--
ALTER TABLE `divisis`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `infografis`
--
ALTER TABLE `infografis`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `inovations`
--
ALTER TABLE `inovations`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `keanggotaans`
--
ALTER TABLE `keanggotaans`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `organisasis`
--
ALTER TABLE `organisasis`
  ADD PRIMARY KEY (`id_entri`),
  ADD UNIQUE KEY `organisasis_id_organisasi_unique` (`id_organisasi`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tims`
--
ALTER TABLE `tims`
  ADD PRIMARY KEY (`id_entri`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artikels`
--
ALTER TABLE `artikels`
  MODIFY `id_entri` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisis`
--
ALTER TABLE `divisis`
  MODIFY `id_entri` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inovations`
--
ALTER TABLE `inovations`
  MODIFY `id_entri` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organisasis`
--
ALTER TABLE `organisasis`
  MODIFY `id_entri` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tims`
--
ALTER TABLE `tims`
  MODIFY `id_entri` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
