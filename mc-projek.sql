-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 09:21 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mc-projek`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sedan', '2025-03-25 03:18:29', '2025-03-25 03:18:34'),
(5, 'Mobil Sport', '2025-03-26 18:38:23', '2025-03-26 18:38:23'),
(6, 'Pickup', '2025-03-28 06:23:03', '2025-03-28 06:23:03'),
(8, 'Hatchback', '2025-04-20 19:42:33', '2025-04-20 19:42:33');

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
(5, '2023_12_12_072543_create_permission_tables', 1),
(6, '2023_12_31_064553_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '1'),
(2, 'App\\Models\\User', '3');

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(2, 'master', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(3, 'master-user', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(4, 'master-role', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(5, 'website', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(6, 'setting', 'api', '2025-03-20 06:29:55', '2025-03-20 06:29:55'),
(7, 'product', 'api', '2025-03-22 05:02:20', '2025-03-22 05:02:51'),
(9, 'product-categories', 'api', '2025-03-25 01:25:24', '2025-03-25 01:25:34'),
(10, 'product-items', 'api', '2025-03-25 01:32:45', '2025-03-25 01:32:45'),
(11, 'transaction', 'api', '2025-03-26 03:05:48', '2025-03-26 03:05:48'),
(12, 'sale', 'api', '2025-03-28 02:07:01', '2025-03-28 02:07:01'),
(13, 'restock', 'api', '2025-05-16 01:47:39', '2025-05-16 01:47:39');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_category` int NOT NULL,
  `price` float NOT NULL,
  `stock` int NOT NULL,
  `photo` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `id_category`, `price`, `stock`, `photo`, `created_at`, `updated_at`) VALUES
(22, 'Rolls-Royce La Rose Noire Droptail', 5, 450000000000, 2, 'photo/gwVk57H41a5j9xLNKJf9jHs6RbAfZGTtK9JchBo7.jpg', '2025-04-08 20:49:27', '2025-05-19 03:43:18'),
(23, 'Porsche Taycan', 1, 5000000000, 0, 'photo/HF7l1MEAKw54eeP6MENP892kzY5dXuCZg032fS6O.jpg', '2025-04-09 20:53:36', '2025-05-19 03:42:40'),
(24, 'Toyota Hilux Single Cab', 6, 500000000, 0, 'photo/TTqyNbvEokA2dgwZFIlqKI7ELLAuNthVGPxe1lYh.jpg', '2025-04-14 23:24:22', '2025-05-19 11:39:37'),
(26, 'Honda Brio', 8, 288000000, 1, 'photo/XN8KmUyedl8jmz97iXJ8zMoj0KNZDjVYhyXaTZOb.jpg', '2025-04-20 19:47:56', '2025-05-15 03:47:49'),
(28, 'BYD Seal', 1, 726000000, 0, 'photo/Cgrxtg0Yrr7VMDjZrYwbsbxDMldat9ceZNZ10YLX.jpg', '2025-05-09 01:27:40', '2025-05-19 11:34:23'),
(29, 'Lamborghini Revuelto', 5, 30000000000, 0, 'photo/8hTvDX5d5Zelj70aBBeDaFiNW0uJxur6e5ZWSCvr.jpg', '2025-05-09 01:43:32', '2025-05-19 11:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stock`
--

CREATE TABLE `riwayat_stock` (
  `id` int NOT NULL,
  `id_product` int NOT NULL,
  `tipe` enum('masuk','keluar') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `riwayat_stock`
--

INSERT INTO `riwayat_stock` (`id`, `id_product`, `tipe`, `quantity`, `created_at`, `updated_at`) VALUES
(15, 26, 'masuk', 5, '2025-05-27 01:43:17', '2025-05-27 01:43:17'),
(16, 26, 'keluar', 1, '2025-06-11 02:24:25', '2025-06-11 02:24:25'),
(17, 26, 'masuk', 2, '2025-06-26 13:47:44', '2025-06-26 13:47:44'),
(18, 26, 'masuk', 5, '2025-06-26 14:08:26', '2025-06-26 14:08:26');

--
-- Triggers `riwayat_stock`
--
DELIMITER $$
CREATE TRIGGER `sriwayat` AFTER INSERT ON `riwayat_stock` FOR EACH ROW BEGIN
    IF NEW.tipe = 'masuk' THEN
        UPDATE products
        SET stock = stock + NEW.quantity
        WHERE id = NEW.id_product;
    ELSE
        UPDATE products
        SET stock = stock - NEW.quantity
        WHERE id = NEW.id_product;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `full_name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'api', '2025-03-20 06:29:54', '2025-03-20 06:29:54'),
(2, 'sales', 'sales', 'api', '2025-05-19 07:18:05', '2025-05-19 07:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
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
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(9, 2),
(10, 2),
(11, 2),
(12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bg_auth` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dinas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pemerintah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `uuid`, `app`, `description`, `logo`, `banner`, `bg_auth`, `dinas`, `pemerintah`, `alamat`, `telepon`, `email`, `created_at`, `updated_at`) VALUES
(1, '43f9fd64-aad4-4342-ae71-e03c166ce28c', 'e-MOBIL PNG', 'Aplikasi Toko mobil', '/storage/setting/LCPBD5TaORVoNh5e5LyUC4LtSdsSn0JDowuDwUT6.jpg', '/media/misc/banner.jpg', '/storage/setting/oOIDAorfQlrilqeYcq83jMZES4b5lxVXjvv5nK3H.jpg', 'Dinas Lingkungan Hidup', 'Pemerintah Provinsi Jawa Timur', 'gunung sari indah blok g no 23', '085755502868', 'grizeldaagnurindrag@gmail.com', '2025-03-20 06:29:58', '2025-04-20 21:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `transaction_code` varchar(100) NOT NULL,
  `total` float NOT NULL,
  `seller` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `metode_pembayaran` varchar(100) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_code`, `total`, `seller`, `metode_pembayaran`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(171, 'TRX-XO9N00GP', 322560000, 'Admin', 'Cash', NULL, NULL, '2025-06-11 02:24:25', '2025-06-11 02:24:25'),
(172, 'TRX-SE1JBYFY', 813120000, 'Admin', 'Debit', NULL, NULL, '2025-06-26 12:34:33', '2025-06-26 12:34:33'),
(173, 'TRX-NGKXMORE', 813120000, 'Admin', 'Debit', NULL, NULL, '2025-06-26 12:36:20', '2025-06-26 12:36:20'),
(176, 'TRX-OLXC70KF', 33600000000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 12:40:15', '2025-06-26 12:40:15'),
(177, 'TRX-WHGRT0LN', 322560000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 12:53:08', '2025-06-26 12:53:08'),
(178, 'TRX-ATVX3VF9', 322560000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 13:08:18', '2025-06-26 13:08:18'),
(179, 'TRX-HDDPXFQ9', 322560000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 13:33:17', '2025-06-26 13:33:17'),
(180, 'TRX-PG2IT8NX', 33600000000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 13:39:44', '2025-06-26 13:39:44'),
(181, 'TRX-LHIZF5TQ', 322560000, 'Admin', 'Debit', 'pending', NULL, '2025-06-26 13:47:54', '2025-06-26 13:47:54'),
(182, 'TRX-NKYUATTN', 322560000, 'Admin', 'Debit', 'PAID', NULL, '2025-06-26 13:53:30', '2025-06-26 14:04:06'),
(183, 'TRX-HQDDYEII', 322560000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-26 14:08:45', '2025-06-26 14:08:45'),
(184, 'TRX-LITCP1NJ', 504000000000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-26 14:40:53', '2025-06-26 14:40:53'),
(185, 'TRX-R21NQF13', 322560000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-26 14:41:22', '2025-06-26 14:41:22'),
(186, 'TRX-8PDKTHVU', 322560000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-26 14:46:15', '2025-06-26 14:46:15'),
(187, 'TRX-C7XLRANU', 813120000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-30 01:31:40', '2025-06-30 01:31:40'),
(188, 'TRX-IXOWVI5R', 813120000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-30 01:49:20', '2025-06-30 01:49:20'),
(189, 'TRX-LNNHACDV', 322560000, 'Admin', 'Debit', 'PENDING', NULL, '2025-06-30 01:53:09', '2025-06-30 01:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_product`
--

CREATE TABLE `transaction_product` (
  `id` int NOT NULL,
  `id_product` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction_product`
--

INSERT INTO `transaction_product` (`id`, `id_product`, `id_transaksi`, `quantity`, `created_at`, `updated_at`) VALUES
(158, 26, 171, 1, '2025-06-11 02:24:25', '2025-06-11 02:24:25'),
(159, 28, 172, 1, '2025-06-26 12:34:33', '2025-06-26 12:34:33'),
(160, 28, 173, 1, '2025-06-26 12:36:21', '2025-06-26 12:36:21'),
(161, 29, 176, 1, '2025-06-26 12:40:15', '2025-06-26 12:40:15'),
(162, 26, 177, 1, '2025-06-26 12:53:08', '2025-06-26 12:53:08'),
(163, 26, 178, 1, '2025-06-26 13:08:18', '2025-06-26 13:08:18'),
(164, 26, 179, 1, '2025-06-26 13:33:17', '2025-06-26 13:33:17'),
(165, 29, 180, 1, '2025-06-26 13:39:44', '2025-06-26 13:39:44'),
(166, 26, 181, 1, '2025-06-26 13:47:54', '2025-06-26 13:47:54'),
(167, 26, 182, 1, '2025-06-26 13:53:30', '2025-06-26 13:53:30'),
(168, 26, 183, 1, '2025-06-26 14:08:45', '2025-06-26 14:08:45'),
(169, 22, 184, 1, '2025-06-26 14:40:53', '2025-06-26 14:40:53'),
(170, 26, 185, 1, '2025-06-26 14:41:22', '2025-06-26 14:41:22'),
(171, 26, 186, 1, '2025-06-26 14:46:15', '2025-06-26 14:46:15'),
(172, 28, 187, 1, '2025-06-30 01:31:40', '2025-06-30 01:31:40'),
(173, 28, 188, 1, '2025-06-30 01:49:20', '2025-06-30 01:49:20'),
(174, 26, 189, 1, '2025-06-30 01:53:09', '2025-06-30 01:53:09');

--
-- Triggers `transaction_product`
--
DELIMITER $$
CREATE TRIGGER `minstok` AFTER INSERT ON `transaction_product` FOR EACH ROW UPDATE products SET stock = stock - new.quantity WHERE id = new.id_product
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `phone`, `photo`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'eee3f544-b437-481b-b128-54356bb9ebc7', 'Admin', 'admin@gmail.com', '08123456789', NULL, '$2y$12$BWLPJxH/iyTaJaFjzTKdj.bidfaLZZhTawRAb2ET17J4/RRIkBpWW', NULL, '2025-03-20 06:29:57', '2025-03-20 06:29:57'),
(3, '6f88fd18-5e21-495f-9be3-839308162a26', 'sales', 'sales@gmail.com', '09988754785', NULL, '$2y$12$QMq1wirhJh3LcipuxDtGEu6WL5Gt9w8n8SW5ugwdJK69yznKBR0Ga', NULL, '2025-05-19 07:18:51', '2025-05-19 07:18:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `riwayat_stock`
--
ALTER TABLE `riwayat_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_uuid_unique` (`uuid`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_product`
--
ALTER TABLE `transaction_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PRODUCT` (`id_product`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `riwayat_stock`
--
ALTER TABLE `riwayat_stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `transaction_product`
--
ALTER TABLE `transaction_product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `id_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_stock`
--
ALTER TABLE `riwayat_stock`
  ADD CONSTRAINT `riwayat` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_product`
--
ALTER TABLE `transaction_product`
  ADD CONSTRAINT `PRODUCT` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TRANSAKSI` FOREIGN KEY (`id_transaksi`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
