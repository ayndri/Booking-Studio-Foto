-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2026 at 02:31 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_upfotostudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `service_package_id` bigint UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `add_on_amount` int UNSIGNED NOT NULL DEFAULT '0',
  `total_amount` int UNSIGNED NOT NULL,
  `payment_type` enum('DP','LUNAS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('PENDING_PAYMENT','CONFIRMED','CANCELLED','COMPLETED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING_PAYMENT',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `guest_id`, `studio_id`, `service_package_id`, `booking_date`, `start_time`, `end_time`, `add_on_amount`, `total_amount`, `payment_type`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'BOOK-SAMPLE-0001', 1, 1, 1, '2026-03-03', '10:00:00', '10:30:00', 0, 150000, 'LUNAS', 'CONFIRMED', 'Seed data booking berhasil dibayar.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 'BOOK-SAMPLE-0002', 2, 1, 1, '2026-03-05', '14:00:00', '14:30:00', 50000, 200000, 'DP', 'PENDING_PAYMENT', 'Seed data booking menunggu pembayaran.', '2026-03-04 00:19:36', '2026-03-04 00:19:36');

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
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `full_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Guest Contoh Satu', 'guest1@example.com', '081200000001', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 'Guest Contoh Dua', 'guest2@example.com', '081200000002', '2026-03-04 00:19:36', '2026-03-04 00:19:36');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_03_04_070109_add_role_to_users_table', 1),
(6, '2026_03_04_070109_create_guests_table', 1),
(7, '2026_03_04_070110_create_studios_table', 1),
(8, '2026_03_04_070111_create_service_packages_table', 1),
(9, '2026_03_04_070111_create_website_contents_table', 1),
(10, '2026_03_04_070112_create_bookings_table', 1),
(11, '2026_03_04_070112_create_payment_transactions_table', 1);

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
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` enum('DP','LUNAS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('QRIS') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QRIS',
  `amount` int UNSIGNED NOT NULL,
  `status` enum('PENDING','SUCCESS','FAILED','EXPIRED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `gateway_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_payload` text COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `callback_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `booking_id`, `invoice_number`, `payment_type`, `payment_method`, `amount`, `status`, `gateway_reference`, `qr_payload`, `paid_at`, `expires_at`, `callback_payload`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV-SAMPLE-0001', 'LUNAS', 'QRIS', 150000, 'SUCCESS', 'MOCK-QRIS-SAMPLE1', 'MOCKQRIS|INVOICE:INV-SAMPLE-0001', '2026-03-03 00:19:36', '2026-03-04 00:19:36', '{\"status\": \"SUCCESS\", \"invoice_number\": \"INV-SAMPLE-0001\"}', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 2, 'INV-SAMPLE-0002', 'DP', 'QRIS', 60000, 'PENDING', 'MOCK-QRIS-SAMPLE2', 'MOCKQRIS|INVOICE:INV-SAMPLE-0002', NULL, '2026-03-04 00:49:36', NULL, '2026-03-04 00:19:36', '2026-03-04 00:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

CREATE TABLE `service_packages` (
  `id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int UNSIGNED NOT NULL,
  `duration_minutes` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_packages`
--

INSERT INTO `service_packages` (`id`, `studio_id`, `name`, `description`, `price`, `duration_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic Portrait 30 Menit', 'Sesi portrait singkat 30 menit.', 150000, 30, 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 1, 'Premium Portrait 60 Menit', 'Sesi portrait lengkap dengan 2 set background.', 300000, 60, 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(3, 2, 'Editorial Session 90 Menit', 'Sesi editorial untuk personal branding.', 450000, 90, 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(4, 3, 'Family Kids 60 Menit', 'Sesi keluarga dengan properti anak.', 350000, 60, 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`id`, `name`, `slug`, `location`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Studio Aurora', 'studio-aurora', 'Lantai 1, UPFotoStudio', 'Ruangan bernuansa bright untuk portrait dan keluarga.', 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 'Studio Monochrome', 'studio-monochrome', 'Lantai 2, UPFotoStudio', 'Ruangan konsep editorial dengan lighting fleksibel.', 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(3, 'Studio Kids', 'studio-kids', 'Lantai 1, UPFotoStudio', 'Ruang tematik untuk kebutuhan foto anak dan keluarga.', 1, '2026-03-04 00:19:36', '2026-03-04 00:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','owner') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@upfoto.test', 'admin', NULL, '$2y$10$.gUVDQv2/4uDJniw44Fgk.fr.cP0SYNG89rOUER15kafKaFSSQBVi', NULL, '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 'Owner Studio', 'owner@upfoto.test', 'owner', NULL, '$2y$10$0o3Wk7ISKT2GLiByEpaqAOtpSPdPeTG7vqF1NmO9hqBDh16/uTsEu', NULL, '2026-03-04 00:19:36', '2026-03-04 00:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `website_contents`
--

CREATE TABLE `website_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `website_contents`
--

INSERT INTO `website_contents` (`id`, `key`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 'home_hero', 'Sistem Booking Ruang Photostudio', 'Booking studio online dengan pembayaran digital realtime QRIS, cepat dan aman.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(2, 'about_page', 'Tentang Kami', 'UPFotoStudio hadir untuk membantu kebutuhan foto personal, keluarga, hingga branding bisnis.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(3, 'gallery_page', 'Galeri', 'Contoh hasil foto dari berbagai studio dan paket layanan kami.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(4, 'pricing_page', 'Paket Harga', 'Pilih paket sesuai durasi yang Anda butuhkan. Harga transparan dan fleksibel.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(5, 'terms_page', 'Syarat dan Ketentuan', 'Booking aktif setelah pembayaran berhasil. DP minimal 30% atau minimum Rp50.000.', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(6, 'contact_page', 'Kontak', 'WhatsApp: 0812-0000-0000 | Email: hello@upfotostudio.test', '2026-03-04 00:19:36', '2026-03-04 00:19:36'),
(7, 'home_gallery_section', 'Preview Galeri', 'Lihat Semua', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(8, 'home_service_section', 'Layanan UPFotoStudio', 'Lihat Paket Harga', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(9, 'home_faq_section', 'FAQ', 'Pertanyaan yang paling sering ditanyakan sebelum booking studio.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(10, 'home_why_choose', 'Kenapa pilih kami?', 'Keunggulan utama untuk pengalaman studio yang nyaman.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(11, 'home_why_choose_item_1', 'Studio bersih dan nyaman untuk semua jenis sesi foto.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(12, 'home_why_choose_item_2', 'Pembayaran digital realtime via QRIS.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(13, 'home_why_choose_item_3', 'Sistem booking otomatis, anti bentrok jadwal.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(14, 'home_why_choose_item_4', 'Invoice PDF dan status booking bisa dipantau online.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(15, 'home_promo_slide_1', 'First Flipbook Photobooth', '{\"caption\":\"Promo design terbaru untuk pengalaman photobooth yang lebih seru.\",\"image\":\"assets/images/home/promo/promo-1.svg\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(16, 'home_promo_slide_2', 'Level Up Your Photos', '{\"caption\":\"Abadikan momen bersama teman dan keluarga dengan kualitas studio profesional.\",\"image\":\"assets/images/home/promo/promo-2.svg\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(17, 'home_promo_slide_3', 'Explore Moments', '{\"caption\":\"Pilih tipe sesi favoritmu: couple, group, solo, hingga kebutuhan ID photo.\",\"image\":\"assets/images/home/promo/promo-3.svg\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(23, 'home_faq_1', 'Bagaimana cara booking studio?', 'Pilih studio, tanggal, jam mulai, lalu pilih paket layanan. Sistem otomatis menghitung jam selesai dan total pembayaran.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(24, 'home_faq_2', 'Apakah bisa pembayaran DP?', 'Bisa. DP dihitung 30% dari total biaya, dengan nilai minimum Rp50.000 sesuai aturan sistem.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(25, 'home_faq_3', 'Bagaimana jika jadwal bentrok?', 'Sistem menolak otomatis booking yang overlap pada studio yang sama jika status booking lain masih PENDING_PAYMENT atau CONFIRMED.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(26, 'home_faq_4', 'Setelah bayar, kapan booking dikonfirmasi?', 'Booking akan berubah menjadi CONFIRMED secara realtime saat callback pembayaran mengirim status SUCCESS.', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(27, 'gallery_item_1', 'Preview Foto 1', '{\"image\":\"assets/images/home/gallery/gallery-1.svg\",\"caption\":\"Dokumentasi sesi foto keluarga.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(28, 'gallery_item_2', 'Preview Foto 2', '{\"image\":\"assets/images/home/gallery/gallery-2.svg\",\"caption\":\"Sesi portrait profesional untuk kebutuhan branding.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(29, 'gallery_item_3', 'Preview Foto 3', '{\"image\":\"assets/images/home/gallery/gallery-3.svg\",\"caption\":\"Konsep studio minimalis dengan pencahayaan soft.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(30, 'gallery_item_4', 'Preview Foto 4', '{\"image\":\"assets/images/home/gallery/gallery-4.svg\",\"caption\":\"Sesi couple dengan tema color mood modern.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(31, 'gallery_item_5', 'Preview Foto 5', '{\"image\":\"assets/images/home/gallery/gallery-5.svg\",\"caption\":\"Paket group session untuk momen komunitas.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(32, 'gallery_item_6', 'Preview Foto 6', '{\"image\":\"assets/images/home/gallery/gallery-1.svg\",\"caption\":\"Koleksi highlight terbaru dari studio kami.\"}', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(33, 'pricing_package_image_1', 'Gambar Paket 1', 'assets/images/home/gallery/gallery-1.svg', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(34, 'pricing_package_image_2', 'Gambar Paket 2', 'assets/images/home/gallery/gallery-2.svg', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(35, 'pricing_package_image_3', 'Gambar Paket 3', 'assets/images/home/gallery/gallery-3.svg', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(36, 'pricing_package_image_4', 'Gambar Paket 4', 'assets/images/home/gallery/gallery-4.svg', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(37, 'pricing_package_image_5', 'Gambar Paket 5', 'assets/images/home/gallery/gallery-5.svg', '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(38, 'terms_item_1', 'Booking overlap pada studio dan waktu yang sama akan ditolak otomatis.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(39, 'terms_item_2', 'Status booking aktif jika transaksi QRIS berstatus SUCCESS.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41'),
(40, 'terms_item_3', 'Pembayaran DP dihitung 30% dari total atau minimal Rp50.000.', NULL, '2026-03-07 20:13:41', '2026-03-07 20:13:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_guest_id_foreign` (`guest_id`),
  ADD KEY `bookings_service_package_id_foreign` (`service_package_id`),
  ADD KEY `bookings_studio_id_booking_date_index` (`studio_id`,`booking_date`),
  ADD KEY `bookings_status_booking_date_index` (`status`,`booking_date`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guests_email_phone_unique` (`email`,`phone`),
  ADD KEY `guests_email_index` (`email`),
  ADD KEY `guests_phone_index` (`phone`);

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
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_transactions_invoice_number_unique` (`invoice_number`),
  ADD KEY `payment_transactions_booking_id_foreign` (`booking_id`),
  ADD KEY `payment_transactions_status_created_at_index` (`status`,`created_at`),
  ADD KEY `payment_transactions_gateway_reference_index` (`gateway_reference`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_packages_studio_id_foreign` (`studio_id`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studios_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `website_contents`
--
ALTER TABLE `website_contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `website_contents_key_unique` (`key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_packages`
--
ALTER TABLE `service_packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `website_contents`
--
ALTER TABLE `website_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_service_package_id_foreign` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD CONSTRAINT `service_packages_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
