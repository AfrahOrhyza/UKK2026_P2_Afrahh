-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2026 at 07:33 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_parkir`
--

-- --------------------------------------------------------

--
-- Table structure for table `area_parkir`
--

CREATE TABLE `area_parkir` (
  `id_area` int NOT NULL,
  `nama_area` varchar(100) DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `terisi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `area_parkir`
--

INSERT INTO `area_parkir` (`id_area`, `nama_area`, `kapasitas`, `terisi`) VALUES
(3, 'Area A', 6, 2),
(4, 'Area B', 4, 3),
(5, 'Area C', 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int NOT NULL,
  `plat_nomor` varchar(20) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `status` enum('parkir','keluar','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_tarif` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `plat_nomor`, `warna`, `status`, `id_tarif`, `id_user`, `created_at`) VALUES
(17, 'Z 74343 GH', 'Merah', 'keluar', 8, 36, '2026-04-15 07:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `aktivitas` text,
  `waktu_aktivitas` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`) VALUES
(1, NULL, 'Mengedit user: orhy edit', '2026-04-13 04:03:18'),
(2, NULL, 'Menghapus user: oii', '2026-04-13 04:29:02'),
(3, NULL, 'Menambahkan user: orhy', '2026-04-13 04:31:24'),
(4, NULL, 'Menghapus user: orhy', '2026-04-13 04:31:40'),
(5, NULL, 'Mengubah status user: admin menjadi nonaktif', '2026-04-13 04:54:16'),
(6, NULL, 'Mengubah status user: admin menjadi aktif', '2026-04-13 04:54:19'),
(7, NULL, 'Menambahkan user: ohyy', '2026-04-13 05:04:30'),
(8, NULL, 'Menghapus user: ohyy', '2026-04-13 05:04:35'),
(9, NULL, 'Menambahkan user: ohy', '2026-04-13 05:05:13'),
(10, NULL, 'Menghapus user: ohy', '2026-04-13 05:56:48'),
(11, NULL, 'Menambahkan user: petugas', '2026-04-13 05:57:25'),
(12, NULL, 'Menambahkan user: owner', '2026-04-13 05:58:36'),
(13, NULL, 'Menambahkan user: owner', '2026-04-13 06:32:22'),
(14, NULL, 'Mengedit user: owner edit', '2026-04-13 06:33:06'),
(15, NULL, 'Mengedit user: petugas', '2026-04-13 06:38:27'),
(16, NULL, 'Menambahkan user: petugas 2', '2026-04-13 06:58:28'),
(17, NULL, 'Menambahkan user: frahh', '2026-04-13 07:29:21'),
(18, NULL, 'Menambahkan user: mai', '2026-04-13 07:33:23'),
(19, NULL, 'Menghapus user: mai', '2026-04-13 07:41:41'),
(20, NULL, 'Menghapus user: frahh', '2026-04-13 07:41:47'),
(21, NULL, 'Menghapus user: petugas 2', '2026-04-13 07:41:51'),
(22, NULL, 'Menambahkan user: hahaha', '2026-04-13 07:42:19'),
(23, NULL, 'Menambahkan user: mek', '2026-04-13 07:43:26'),
(24, NULL, 'Menambahkan user: dsjdjs', '2026-04-13 07:45:48'),
(25, NULL, 'Menghapus user: dsjdjs', '2026-04-13 07:45:55'),
(26, NULL, 'Menghapus user: mek', '2026-04-13 07:45:58'),
(27, NULL, 'Menghapus user: hahaha', '2026-04-13 07:46:01'),
(28, NULL, 'Menambahkan user: memeg', '2026-04-13 07:53:10'),
(29, NULL, 'Menghapus user: memeg', '2026-04-13 07:58:35'),
(30, NULL, 'Menambahkan user: afrah', '2026-04-13 08:00:09'),
(31, NULL, 'Menambahkan user: mei', '2026-04-13 08:00:36'),
(32, NULL, 'Menambahkan user: orr', '2026-04-13 08:01:45'),
(33, NULL, 'Menambahkan user: orhy', '2026-04-13 08:47:14'),
(34, NULL, 'Menghapus user: petugas', '2026-04-14 00:41:16'),
(35, NULL, 'Mengedit user: mei', '2026-04-14 00:41:24'),
(36, NULL, 'Menambahkan user: vik', '2026-04-14 00:43:25'),
(37, NULL, 'Mengedit user: mei', '2026-04-14 00:45:54'),
(38, NULL, 'Mengedit user: mei', '2026-04-14 00:56:50'),
(39, NULL, 'Mengedit user: orhy', '2026-04-14 00:57:59'),
(40, NULL, 'Mengedit user: orhy', '2026-04-14 01:00:56'),
(41, NULL, 'Mengedit user: mei', '2026-04-14 01:05:43'),
(42, NULL, 'Mengedit user: mei', '2026-04-14 01:09:04'),
(43, NULL, 'Mengedit user: orhy', '2026-04-14 01:10:49'),
(44, NULL, 'Mengedit user: orhy', '2026-04-14 01:11:22'),
(45, NULL, 'Mengedit user: orhy', '2026-04-14 01:17:54'),
(46, NULL, 'Mengedit user: vik', '2026-04-14 01:26:44'),
(47, NULL, 'Menambahkan user: na', '2026-04-14 01:31:24'),
(48, NULL, 'Mengedit user: mei', '2026-04-14 01:31:31'),
(49, NULL, 'Mengedit user: owner edit', '2026-04-14 01:31:42'),
(50, NULL, 'Menambahkan user: ena', '2026-04-14 01:32:36'),
(51, NULL, 'Mengedit user: mei', '2026-04-14 01:41:53'),
(52, NULL, 'Menghapus user: mei', '2026-04-14 02:41:37'),
(53, NULL, 'Menambahkan user: petugas', '2026-04-14 02:42:49'),
(54, NULL, 'Mengedit user: vik', '2026-04-14 02:42:59'),
(55, NULL, 'Mengedit user: afrah', '2026-04-14 02:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_15_034116_add_pembayaran_to_transaksi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id_riwayat` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_transaksi` int DEFAULT NULL,
  `plat_kendaraan` varchar(20) DEFAULT NULL,
  `jenis_kendaraan` varchar(50) DEFAULT NULL,
  `nama_area` varchar(100) DEFAULT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `durasi` int DEFAULT NULL,
  `biaya_total` decimal(10,2) DEFAULT NULL,
  `uang_dibayar` decimal(10,2) DEFAULT NULL,
  `kembalian` decimal(10,2) DEFAULT NULL,
  `status_pembayaran` enum('lunas','pending') DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tarif`
--

CREATE TABLE `tarif` (
  `id_tarif` int NOT NULL,
  `jenis_kendaraan` varchar(50) DEFAULT NULL,
  `tarif_per_jam` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`) VALUES
(7, 'Mobil', 5000.00),
(8, 'Bis', 10000.00),
(9, 'Truk', 10000.00),
(10, 'Motor', 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_kendaraan` int DEFAULT NULL,
  `id_tarif` int DEFAULT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `durasi_jam` int DEFAULT NULL,
  `durasi_menit` int DEFAULT NULL,
  `durasi` int DEFAULT NULL,
  `biaya_total` decimal(10,2) NOT NULL,
  `status` enum('aktif','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `id_area` int DEFAULT NULL,
  `status_pembayaran` enum('lunas','pending') DEFAULT NULL,
  `metode_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_kendaraan`, `id_tarif`, `waktu_masuk`, `waktu_keluar`, `durasi_jam`, `durasi_menit`, `durasi`, `biaya_total`, `status`, `id_user`, `id_area`, `status_pembayaran`, `metode_pembayaran`) VALUES
(18, 17, 8, '2026-04-15 07:18:29', '2026-04-15 07:18:34', 0, 0, 0, 10000.00, 'selesai', 36, 3, 'lunas', 'cash'),
(19, 17, 8, '2026-04-15 07:20:08', '2026-04-15 07:23:45', 0, 0, 4, 10000.00, 'selesai', 36, 3, 'lunas', 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','petugas','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT NULL,
  `shift` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `name`, `email`, `password`, `role`, `status`, `shift`, `created_at`, `updated_at`) VALUES
(13, 'admin', 'admin@ukk2026.com', '$2y$12$3N2t/VXftPW02l7pW0PBdejcN9gHA9c4obxXHxCrN5NWkE5ZHio/.', 'admin', 'aktif', 'pagi', '2026-04-10 03:52:25', '2026-04-12 21:54:19'),
(21, 'owner', 'owner@ukk2026.com', '$2y$12$A/IVQeSu0tNAEEi7D1Z7VenxLoZ3yHeckzus0ugRXjKn37ZdQEkTq', 'owner', 'aktif', NULL, '2026-04-12 23:32:22', '2026-04-13 18:31:42'),
(29, 'afrah edit', 'afrahorhyza@gmail.com', '$2y$12$.VHhoIoHgP6RpIZlbwUU2ORj.ToUoZPG6fKCx/lZRtSy.q/hyHJQG', 'admin', 'aktif', NULL, '2026-04-13 01:00:09', '2026-04-13 19:43:16'),
(31, 'orr', 'orr@gmail.com', '$2y$12$VfP39m483sxPmpQDjMUtU.M9enZR351XWQI86mPkkzUZX37WN9/iy', 'owner', 'aktif', 'pagi', '2026-04-13 01:01:45', '2026-04-13 01:01:45'),
(32, 'orhy', 'orhy@gmail.com', '$2y$12$tVAs1lUn5duzYDTl4j7/eucEV3ovVDHG2zMc7EGlGSV/eytI2yA5G', 'petugas', 'aktif', 'siang', '2026-04-13 01:47:14', '2026-04-13 18:17:54'),
(33, 'vik', 'viku@gmail.com', '$2y$12$oXynphoDNPCeNcPsxtobKeNvPuZ2uWBLuoc5HFQECBDHtMxc7Aciq', 'petugas', 'aktif', 'pagi', '2026-04-13 17:43:25', '2026-04-13 19:42:59'),
(35, 'ena', 'na@gmail.com', '$2y$12$qlB3TFUw6GLr4gw4LkdifO5h6BcoWcsNMEPNVJBevrUGFmj2c0UuW', 'owner', 'aktif', NULL, '2026-04-13 18:32:36', '2026-04-13 18:32:36'),
(36, 'petugas', 'petugas@ukk2026.com', '$2y$12$wYO6SZ2yQLIgTM5noX/NXeswXMtZQqOus4QGcbNPOUMxl016F5.ZC', 'petugas', 'aktif', 'pagi', '2026-04-13 19:42:49', '2026-04-13 19:42:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area_parkir`
--
ALTER TABLE `area_parkir`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD KEY `id_tarif` (`id_tarif`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_kendaraan` (`id_kendaraan`),
  ADD KEY `id_tarif` (`id_tarif`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_area` (`id_area`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area_parkir`
--
ALTER TABLE `area_parkir`
  MODIFY `id_area` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id_tarif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`),
  ADD CONSTRAINT `kendaraan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD CONSTRAINT `riwayat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `riwayat_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tarif` (`id_tarif`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`id_area`) REFERENCES `area_parkir` (`id_area`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
