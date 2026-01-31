-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Jan 2026 pada 02.37
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
(1, 'EXCAVATOR ZX 200', 'Excavator', 'Excavator ZX200 dalam kondisi sangat baik, cocok untuk pekerjaan konstruksi, penggalian tanah, dan pemindahan material. Unit terawat, performa hidrolik kuat, dan siap digunakan di berbagai medan. Servis rutin dan pengecekan berkala tersedia.', 2019, 'Hitachi', 350000.00, 'available', NULL, '1765446344_5.jpg', '2025-12-11 02:45:44', '2026-01-26 22:42:21'),
(2, 'EXCAVATOR BREAKER', 'Excavator Breaker', 'Excavator Breaker kapasitas 20 ton, dilengkapi hydraulic breaker untuk pekerjaan pembongkaran beton, batuan, dan konstruksi keras. Performa stabil, tenaga kuat, dan cocok untuk proyek tambang maupun konstruksi skala besar.', 2020, 'Komatsu', 480000.00, 'available', NULL, '1765447408_6.jpg', '2025-12-11 03:03:28', '2026-01-02 18:29:22'),
(3, 'DUMP TRUCK HINO 260 PS', 'Dump Truck', 'Dump truck dengan kapasitas angkut 10 m³. Sangat cocok untuk mengangkut material seperti tanah, pasir, batu split, dan limbah konstruksi. Kondisi unit terawat, mesin responsif, dan siap mendukung kebutuhan mobilisasi material proyek.', 2018, 'Hino', 300000.00, 'available', NULL, '1765447548_4.jpg', '2025-12-11 03:05:48', '2026-01-26 23:56:48'),
(4, 'EXCAVATOR COBA', 'Excavator', 'sangat kuat dan tahan lama', 2020, 'CAT', 550000.00, 'available', NULL, '1767105910_Screenshot 2025-10-06 175955.png', '2025-12-30 07:45:10', '2026-01-26 22:40:04'),
(5, 'BREAKER COBA', 'Excavator Breaker', '----', 2019, 'Komatsu', 350000.00, 'available', NULL, '1767107950_Screenshot 2025-10-19 134614.png', '2025-12-30 08:19:10', '2026-01-26 22:43:22'),
(6, 'DUMP COBA', 'Dump Truck', '-', 2016, 'HINO', 25000.00, 'available', NULL, '1767107997_Screenshot 2025-11-04 135011.png', '2025-12-30 08:19:57', '2026-01-30 06:59:51');

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
(17, '2026_01_07_114735_add_payment_fields_to_overtimes', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `overtimes`
--

CREATE TABLE `overtimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `extra_hours` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` enum('cash','transfer') DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `overtimes`
--

INSERT INTO `overtimes` (`id`, `rental_id`, `extra_hours`, `price`, `status`, `payment_status`, `created_at`, `updated_at`, `payment_method`, `proof`) VALUES
(3, 5, 36, 900000.00, 'pending', 'unpaid', '2026-01-28 18:20:53', '2026-01-28 18:20:53', NULL, NULL);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rentals`
--

INSERT INTO `rentals` (`id`, `user_id`, `equipment_id`, `rent_date`, `start_time`, `duration_hours`, `location`, `notes`, `status`, `payment_method`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 2, 3, '2025-12-30', '21:30:00', 24, 'Jalan Duwet', 'Jangan Telat', 'completed', NULL, 7200000.00, '2025-12-30 06:12:54', '2026-01-02 08:12:32'),
(2, 2, 2, '2025-12-31', '08:15:00', 13, 'bdjdb', 'dhhbdj', 'completed', NULL, 6240000.00, '2025-12-30 06:15:41', '2026-01-02 18:29:22'),
(3, 2, 4, '2025-12-31', '09:55:00', 5, 'tess', 'deiid', 'completed', NULL, 2750000.00, '2025-12-30 07:54:47', '2026-01-26 22:40:04'),
(4, 2, 3, '2026-01-27', '17:00:00', 12, 'Jl.Duwet', 'Harus Tepat waktu ya', 'completed', NULL, 3600000.00, '2026-01-26 22:45:47', '2026-01-26 22:50:21'),
(5, 2, 6, '2026-01-27', '12:55:00', 1, 'Jln. Rejosari', 'testt', 'completed', NULL, 25000.00, '2026-01-26 22:53:41', '2026-01-30 06:59:51'),
(6, 2, 3, '2026-01-27', '14:14:00', 5, 'dwhd', 'ddjh', 'cancelled', 'transfer', 1500000.00, '2026-01-26 23:27:48', '2026-01-29 20:03:17');

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
('fOa0w1cZFQwNKyeqnnH6tzFqqkfrpXolB1Nl7TDN', 2, '192.168.1.3', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiekMzejRoTWxSSGFlMHdVaE5YTDdYdTVsSHFhbUVsaWdsdEFJWVBGYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHA6Ly8xOTIuMTY4LjEuODo4MDAwIjtzOjU6InJvdXRlIjtzOjc6IndlbGNvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1769781172),
('ikx8qKHegFmA4Ot0ZTwQQo6JanE1yNq7r8SPezKe', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGFoT2hxRGliS1hGWlFPempDRURNNEFlVHlzRDgzbG1BZnpranZBRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmRhc2hib2FyZC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1769781595),
('QoA8Nlh6hEZcD6xiyALlSWlYKGh9CfUb0UjzL15h', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHpDdWZhTG41RWNVTm9IbEU3cWMwNHgxNU80clFGVEUwQ1FVZk9VMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJ3ZWxjb21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769788989),
('xcnwhlgJ0CoLkWHx3so4qMhnotv60ix5G38IC8kD', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoienFLcTdUc3hmVzdJQ0htYXVvWEtFbnZZVHJsRU02TFZXTWRRVTcxNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9ob21lIjtzOjU6InJvdXRlIjtzOjEzOiJjdXN0b21lci5ob21lIjt9czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2NhdGFsb2ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1769781153);

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
(7, 2, 5, 'Bismillah lancar. Aamiin.', 'testimoni/photos/pHeAJBffQFo7QTMLCp5c99oUucAURXi5pyMbqr6Q.jpg', NULL, '2026-01-29 22:38:28', '2026-01-30 05:47:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
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

INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `notes`) VALUES
(1, 'Admin', 'hkadmin@gmail.com', 'admin', NULL, NULL, NULL, '$2y$12$D2ON4PE48U.QZ71R6Kztgu8r.cYW3HHgvkJeVHa9GaeOsYV39TY7y', 'c5Wg974nV5xJoT5fw5dSGjBgjMyZSEtXUILIdSLVOXTfD8pHuFrc8JFVWJpU', '2025-11-27 05:04:19', '2025-11-27 05:04:19', NULL),
(2, 'Alfian Candra Irwana', 'alfiancandrairwana@gmail.com', 'customer', '089696171863', 'Duwet, Jati, Masaran, Sragen', NULL, '$2y$12$PUuQ6RFqhuRYecUEH0UZMuAPTxkt.8T8sTj64lk8ATFYitLdu/.Om', 'GZIonndTz5MHQLUftHalz87pii8pqTMM5p3cbn2V3uorBvf7qyQNvAeJvuvH', '2025-11-27 05:12:13', '2026-01-01 06:18:44', 'tes tok'),
(3, 'Coba2', 'coba1@gmail.com', 'customer', '082918199999', 'Solo Utara', NULL, '$2y$12$wy1kmApOz.Vl8G4vFAN1ruSbFAm/9kw2c/ePAF9AUglSy4LkEbr.O', NULL, '2025-12-11 07:24:09', '2025-12-11 07:45:46', 'pppp'),
(5, 'Coba3', 'coba3@gmail.com', 'customer', '1111111111112', 'Sragen Utara', NULL, '$2y$12$.5/15XBhrOFiL4D6EbvLZupq/G7y0JLbw41pzyVqRRLQzv/RbTw5K', NULL, '2025-12-11 07:36:47', '2026-01-01 18:56:30', 'Testing. Moga Bisa');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `overtimes`
--
ALTER TABLE `overtimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `testimonis`
--
ALTER TABLE `testimonis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
