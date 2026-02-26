-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Feb 2026 pada 17.42
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hk_equipment_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `equipment`
--

CREATE TABLE `equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `price_per_hour` decimal(12,2) NOT NULL,
  `status` enum('available','rented','maintenance') DEFAULT 'available',
  `maintenance_end_at` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `equipment`
--

INSERT INTO `equipment` (`id`, `name`, `category`, `description`, `year`, `brand`, `price_per_hour`, `status`, `maintenance_end_at`, `image`, `created_at`, `updated_at`) VALUES
(1, 'EXCAVATOR ZX 2000', 'Excavator', 'Excavator ZX200 dalam kondisi sangat baik, cocok untuk pekerjaan konstruksi, penggalian tanah, dan pemindahan material. Unit terawat, performa hidrolik kuat, dan siap digunakan di berbagai medan. Servis rutin dan pengecekan berkala tersedia.', 2019, 'Hitachi', 350000.00, 'available', NULL, '1765446344_5.jpg', '2025-12-11 02:45:44', '2026-02-23 15:12:22'),
(2, 'EXCAVATOR BREAKER', 'Excavator Breaker', 'Excavator Breaker kapasitas 20 ton, dilengkapi hydraulic breaker untuk pekerjaan pembongkaran beton, batuan, dan konstruksi keras. Performa stabil, tenaga kuat, dan cocok untuk proyek tambang maupun konstruksi skala besar.', 2020, 'Komatsu', 480000.00, 'rented', NULL, '1765447408_6.jpg', '2025-12-11 03:03:28', '2026-02-23 14:18:27'),
(5, 'BREAKER COBA', 'Excavator Breaker', 'swwghvwshvwsh', 2019, 'Komatsu', 350000.00, 'maintenance', '2026-02-25', '1767107950_Screenshot 2025-10-19 134614.png', '2025-12-30 08:19:10', '2026-02-23 12:01:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `finances`
--

CREATE TABLE `finances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'expense',
  `category` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `finances`
--

INSERT INTO `finances` (`id`, `type`, `category`, `amount`, `description`, `transaction_date`, `reference_id`, `created_at`, `updated_at`) VALUES
(2, 'expense', 'Baut 20 pcs', 50000.00, 'beli baut di masaran 20 pcs', '2026-02-22', NULL, '2026-02-22 05:03:43', '2026-02-23 07:06:29'),
(3, 'expense', 'beli tang', 25000.00, NULL, '2026-02-22', NULL, '2026-02-22 05:24:03', '2026-02-22 05:24:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_26_103029_add_role_to_users_table', 1),
(5, '2025_11_27_110832_create_equipment_table', 2),
(6, '2025_11_27_122207_create_rentals_table', 3),
(8, '2025_12_11_130800_add_notes_to_users_table', 4),
(9, '2026_01_02_105854_update_equipment_status_enum', 5),
(10, '2026_01_02_113350_add_maintenance_fields_to_equipment_table', 6),
(11, '2026_01_02_125309_create_testimonis_table', 7),
(12, '2026_01_02_133711_remove_is_approved_from_testimonis_table', 8),
(16, '2026_01_07_110431_create_overtimes_table', 9),
(17, '2026_01_07_114735_add_payment_fields_to_overtimes', 9),
(18, '2026_02_10_055133_update_overtimes_table', 10),
(19, '2026_02_10_103222_fix_overtime_status_column', 11),
(20, '2026_02_20_094811_create_finances_table', 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `overtimes`
--

CREATE TABLE `overtimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `extra_hours` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `status` varchar(20) NOT NULL,
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` enum('cash','transfer') DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `price_per_hour` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `overtimes`
--

INSERT INTO `overtimes` (`id`, `rental_id`, `extra_hours`, `price`, `status`, `payment_status`, `created_at`, `updated_at`, `payment_method`, `proof`, `started_at`, `ended_at`, `price_per_hour`) VALUES
(20, 39, 0, 7638.89, 'completed', 'paid', '2026-02-21 12:45:20', '2026-02-21 12:47:34', 'transfer', 'overtime-proofs/OUgNa1SDr7XnUJq1wpSK0BYa07VHqro6iV44q5Fg.jpg', '2026-02-21 12:46:07', '2026-02-21 12:47:02', 500000.00),
(21, 40, 0, 173111.11, 'completed', 'paid', '2026-02-21 13:50:51', '2026-02-21 14:18:38', 'transfer', 'overtime-proofs/mF5lVjh84emzNeiB0cozFoTgU9H3UfFsLdLpuqfD.png', '2026-02-21 13:51:24', '2026-02-21 14:17:22', 400000.00),
(22, 41, 0, 2500.00, 'completed', 'paid', '2026-02-21 14:31:01', '2026-02-22 04:53:00', NULL, NULL, '2026-02-21 14:31:28', '2026-02-21 14:31:46', 500000.00),
(23, 42, 0, 7555.56, 'completed', 'paid', '2026-02-22 04:52:03', '2026-02-22 05:01:04', 'transfer', 'overtime-proofs/vjbwuUKscQRomsdr2xoJ2TBTQakhYk4lz98HNIK2.jpg', '2026-02-22 04:52:28', '2026-02-22 04:53:36', 400000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `rent_date` date NOT NULL,
  `start_time` time NOT NULL,
  `duration_hours` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(30) NOT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rentals`
--

INSERT INTO `rentals` (`id`, `user_id`, `equipment_id`, `rent_date`, `start_time`, `duration_hours`, `location`, `notes`, `status`, `payment_method`, `total_price`, `created_at`, `updated_at`, `payment_proof`) VALUES
(8, 2, 1, '2026-02-01', '13:10:00', 1, 'Jln Duwet', NULL, 'cancelled', NULL, 350000.00, '2026-01-31 23:02:26', '2026-01-31 23:03:08', NULL),
(22, 2, 2, '2026-02-10', '20:00:00', 2, 'cuma tes', 'sama', 'completed', 'transfer', 960000.00, '2026-02-10 05:27:14', '2026-02-10 09:58:23', NULL),
(28, 2, 5, '2026-02-21', '06:30:00', 4, 'tes', 'tes', 'completed', 'transfer', 1400000.00, '2026-02-12 04:52:20', '2026-02-21 00:04:55', 'payment-proofs/eE0toSp2AriTiQibFgswv7qzXNfM8uWJFh1mDLWa.png'),
(37, 2, 2, '2026-02-21', '14:00:00', 5, 'Jln. Rejosari', 'testing', 'completed', 'transfer', 2400000.00, '2026-02-20 23:57:05', '2026-02-21 00:04:48', 'payment-proofs/HiMhVGNtGDSApn55HmNoyRZOtdTotkF4Pk8GsTJA.jpg'),
(38, 2, 2, '2026-02-21', '14:07:00', 4, 'tes', NULL, 'completed', 'cash', 1920000.00, '2026-02-21 00:05:36', '2026-02-21 00:15:21', NULL),
(39, 2, 2, '2026-02-21', '19:45:00', 5, 'nyoba ya', 'cobaaa', 'completed', 'transfer', 2400000.00, '2026-02-21 12:44:10', '2026-02-23 06:28:21', 'payment-proofs/Nq21JTpGvAmZWfQW1Y4CJise6fsiR3T8kYvGThRD.jpg'),
(40, 2, 5, '2026-02-21', '20:52:00', 3, 'testing', NULL, 'completed', 'cash', 1050000.00, '2026-02-21 13:50:24', '2026-02-22 04:48:51', NULL),
(41, 2, 2, '2026-02-21', '21:32:00', 3, 'test', NULL, 'completed', 'cash', 1440000.00, '2026-02-21 14:30:29', '2026-02-22 04:48:45', NULL),
(42, 2, 1, '2026-02-22', '11:55:00', 5, 'coba', NULL, 'completed', 'transfer', 1750000.00, '2026-02-22 04:50:35', '2026-02-23 05:43:43', 'payment-proofs/HjTSst4A3wuEIfQUhK29IycRTRtwBYs9fbAlp9sH.jpg'),
(43, 2, 2, '2026-02-23', '20:34:00', 4, 'nyoba', NULL, 'cancelled', NULL, 1920000.00, '2026-02-23 13:32:38', '2026-02-23 13:32:47', NULL),
(44, 2, 1, '2026-02-23', '20:38:00', 4, 'tes', NULL, 'cancelled', NULL, 1400000.00, '2026-02-23 13:37:20', '2026-02-23 13:37:50', NULL),
(45, 2, 1, '2026-02-23', '20:40:00', 3, 'tes', NULL, 'cancelled', 'cash', 1050000.00, '2026-02-23 13:38:29', '2026-02-23 13:39:11', NULL),
(46, 2, 1, '2026-02-23', '20:42:00', 3, 'tes', NULL, 'cancelled', NULL, 1050000.00, '2026-02-23 13:39:49', '2026-02-23 13:39:58', NULL),
(47, 2, 1, '2026-02-23', '20:52:00', 2, 'tes', NULL, 'cancelled', 'cash', 700000.00, '2026-02-23 13:50:46', '2026-02-23 13:51:37', NULL),
(48, 2, 1, '2026-02-23', '20:58:00', 4, 'tes', NULL, 'cancelled', NULL, 1400000.00, '2026-02-23 13:57:06', '2026-02-23 13:57:14', NULL),
(49, 2, 1, '2026-02-23', '21:03:00', 3, 'ee', NULL, 'cancelled', NULL, 1050000.00, '2026-02-23 14:00:36', '2026-02-23 14:00:45', NULL),
(50, 2, 1, '2026-02-23', '21:03:00', 3, 'aa', NULL, 'cancelled', 'cash', 1050000.00, '2026-02-23 14:01:34', '2026-02-23 14:02:00', NULL),
(51, 2, 1, '2026-02-23', '21:03:00', 2, 'bb', NULL, 'cancelled', 'cash', 700000.00, '2026-02-23 14:02:36', '2026-02-23 14:02:56', NULL),
(52, 2, 2, '2026-02-23', '21:14:00', 3, 'tes', NULL, 'cancelled', 'cash', 1440000.00, '2026-02-23 14:12:15', '2026-02-23 14:16:02', NULL),
(53, 3, 1, '2026-02-23', '21:17:00', 3, 'tes', NULL, 'cancelled', 'cash', 1050000.00, '2026-02-23 14:14:37', '2026-02-23 14:15:48', NULL),
(54, 2, 2, '2026-02-23', '21:19:00', 4, 'tes', NULL, 'cancelled', NULL, 1920000.00, '2026-02-23 14:17:17', '2026-02-23 14:17:45', NULL),
(55, 2, 2, '2026-02-23', '21:21:00', 3, 'tes', NULL, 'on_progress', 'transfer', 1440000.00, '2026-02-23 14:18:27', '2026-02-23 16:09:10', 'payment-proofs/yTBRl5z6JT2AS4omGyC3L2xKmwHzzP5AE2p3OP1V.jpg'),
(56, 2, 1, '2026-02-23', '22:13:00', 3, 'tes', NULL, 'cancelled', 'cash', 1050000.00, '2026-02-23 15:11:54', '2026-02-23 15:12:22', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0QZbGAlX2ydqVmmtkU1aftaOtoPh47NjIVGUQ59C', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHRzQmcwdHpXSHE4cm94bGZtRmJ0OGVvZ2VmSVlqZVBhZklPYWxTeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjtzOjU6InJvdXRlIjtzOjE2OiJjdXN0b21lci5wcm9maWxlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1771863624),
('47YgZEoQT6rtTmUrSzsQKTqsq8Cfiri5gjpxQQE7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWWtxRm9LU3ZrbTBLNVJTYTFVbEJaWVM5Sml6VlU2R0RqNWFTRTVFUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQvYWxlcnRzIjtzOjU6InJvdXRlIjtOO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1771864326),
('ZekL6rOxqlc91sK5Ggh3zgHgDe7F2hKne8QSBc6l', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaHBLcWZ2NndhWk9qTFdqTUc0UTRNZ2lhNzRXcVY4OG1NbDhhMWVSdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXRhbG9nLzUvc3RhdHVzIjtzOjU6InJvdXRlIjtzOjIzOiJjdXN0b21lci5jYXRhbG9nLnN0YXR1cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1771857247);

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonis`
--

CREATE TABLE `testimonis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `testimonis`
--

INSERT INTO `testimonis` (`id`, `user_id`, `rating`, `content`, `photo`, `video`, `created_at`, `updated_at`) VALUES
(14, 2, 5, 'Alhamdulillah', NULL, 'testimoni/videos/fYnsS3YjpBfciF0kDvwwi7a90jwmc6vRMIrrODjS.mp4', '2026-02-23 15:57:53', '2026-02-23 15:57:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `image`, `role`, `phone`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `notes`) VALUES
(1, 'Admin', 'hkadmin@gmail.com', NULL, 'admin', NULL, NULL, NULL, '$2y$12$D2ON4PE48U.QZ71R6Kztgu8r.cYW3HHgvkJeVHa9GaeOsYV39TY7y', 'haBi8LrgrdGNWVdLg37YobeS8uouSfPm5l35bEAPceCtmoX5ZbzdSPq6OJZZ', '2025-11-27 05:04:19', '2025-11-27 05:04:19', NULL),
(2, 'Alfian Candra Irwana', 'alfiancandrairwana@gmail.com', NULL, 'customer', '089696171863', 'Duwet, Jati, Masaran, Sragen', NULL, '$2y$12$PUuQ6RFqhuRYecUEH0UZMuAPTxkt.8T8sTj64lk8ATFYitLdu/.Om', 'OoQO1vKt1R4rAFWjib0AoW8MKPsI3Ku06k4dBBN3Tlydo3sN9xBn6GDv93wO', '2025-11-27 05:12:13', '2026-01-01 06:18:44', 'tes tok'),
(3, 'Coba2', 'coba1@gmail.com', NULL, 'customer', '082918199999', 'Solo Utara', NULL, '$2y$12$wy1kmApOz.Vl8G4vFAN1ruSbFAm/9kw2c/ePAF9AUglSy4LkEbr.O', NULL, '2025-12-11 07:24:09', '2025-12-11 07:45:46', 'pppp'),
(8, 'Candra', 'candra@gmail.com', NULL, 'customer', '089696171863', 'Duwet', NULL, '$2y$12$v/LxQj88GUif3cDbkVjq0e9NqBnCRY99.G2FJ.fDZxy1e3ei5VMtW', NULL, '2026-02-19 23:45:06', '2026-02-19 23:45:06', NULL),
(9, 'tes1', 'tes1@gmail.com', '1771571004_2064748.jpg', 'customer', '154141541542', 'tes', NULL, '$2y$12$lq3PExNV1OIsFP1RSXveS.EAeZeDuTL1.7z3dCmGcYhGrGEbiW5uq', NULL, '2026-02-20 00:03:25', '2026-02-20 00:03:25', NULL),
(10, 'tes2', 'tes2@gmail.com', '1771571492_pxfuel.jpg', 'customer', '5422562262', 'tes2', NULL, '$2y$12$SgFz1A0YPA6Axosmi6RiR.eDihaVgdzjIYKWGkqZbPXgVbdfSJPEe', NULL, '2026-02-20 00:11:33', '2026-02-20 00:11:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `overtimes`
--
ALTER TABLE `overtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `overtimes_rental_id_foreign` (`rental_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rentals_user_id_foreign` (`user_id`),
  ADD KEY `rentals_equipment_id_foreign` (`equipment_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `testimonis`
--
ALTER TABLE `testimonis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonis_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `finances`
--
ALTER TABLE `finances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `overtimes`
--
ALTER TABLE `overtimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `testimonis`
--
ALTER TABLE `testimonis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `overtimes`
--
ALTER TABLE `overtimes`
  ADD CONSTRAINT `overtimes_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `testimonis`
--
ALTER TABLE `testimonis`
  ADD CONSTRAINT `testimonis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
