-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 07:52 AM
-- Server version: 13.0.1-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weighing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `tin_number` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `mayor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `branch_code`, `company`, `address`, `tin_number`, `contact_person`, `contact_number`, `mayor`, `created_at`, `updated_at`) VALUES
(1, NULL, 'XYZ', 'Albay', NULL, NULL, NULL, NULL, '2026-05-07 01:07:56', '2026-05-07 01:07:56'),
(2, NULL, 'ZYX', 'Albay', NULL, NULL, NULL, NULL, '2026-05-10 04:28:07', '2026-05-10 04:28:07'),
(5, '000010', 'LGU BAAO', 'Camarines Sur', '', '', '', 'Jeffrey S. Besinio', NULL, NULL),
(6, '000075', 'LGU BACACAY', 'Albay', '', '', '', 'Daniel Jose R. Bombales', NULL, NULL),
(7, '000022', 'LGU BARCELONA', 'Sorsogon', '', '', '', 'Cynthia Falcotelo-Fortes', NULL, NULL),
(8, '000024', 'LGU BASUD', 'Camarines Norte', '', '', '', 'Shunn Frederick Davoco', NULL, NULL),
(9, '000064', 'LGU BATO', 'Camarines Sur', '', '', '', 'Enric Dancalan', NULL, NULL),
(10, '000018', 'LGU BUHI', 'Camarines Sur', '', '', '', 'Rey P. Lacoste', NULL, NULL),
(11, '000016', 'LGU BULA', 'Camarines Sur', '', '', '', 'Manuel \"Nonoy\" Ibasco Jr. ', NULL, NULL),
(12, '000021', 'LGU BULAN', 'Sorsogon', '', '', '', 'Meo Gordola', NULL, NULL),
(13, '000006', 'LGU BULUSAN', 'Sorsogon', '', '', '', 'Wennie Rafallo-Romano', NULL, NULL),
(14, '000050', 'LGU CABUSAO', 'Camarines Sur', '', '', '', 'Jun Aguilar', NULL, NULL),
(15, '000038', 'LGU CAMALIG', 'Albay', '', '', '', 'Carlos Irwin G. Baldo Jr', NULL, NULL),
(16, '000058', 'LGU CAMALIGAN', 'Camarines Sur', '', '', '', 'Diano Ibardaloza Jr.', NULL, NULL),
(17, '000072', 'LGU CANAMAN', 'Camarines Sur', '', '', '', 'Nelson Legaspi', NULL, NULL),
(18, '000019', 'LGU CARAMOAN', 'Camarines Sur', '', '', '', 'Malyn Co', NULL, NULL),
(19, '000025', 'LGU CASIGURAN', 'Sorsogon', '', '', '', 'Ma. Minez R. Hamor', NULL, NULL),
(20, '000027', 'LGU CASTILLA', 'Sorsogon', '', '', '', 'Isagani \"Bong\" Mendoza', NULL, NULL),
(21, '000007', 'LGU DARAGA', 'Albay', '', '', '', 'Victor U. Perete', NULL, NULL),
(22, '000034', 'LGU DEL GALLEGO', 'Camarines Sur', '', '', '', 'Melanie Abarientos', NULL, NULL),
(23, '000048', 'DESCO TIWI', 'Tiwi,Albay', '', '', '', 'The Management', NULL, NULL),
(24, '000020', 'LGU DONSOL', 'Sorsogon', '', '', '', 'Chuck Lubiano', NULL, NULL),
(25, '000052', 'EDC ORMOC', '', '', '', '', '', NULL, NULL),
(26, '000051', 'EDC SORSOGON', '', '', '', '', '', NULL, NULL),
(27, '000067', 'LGU GAINZA', 'Camarines Sur', '', '', '', 'Glenn Gontang', NULL, NULL),
(28, '000060', 'LGU GUBAT', 'Sorsogon', '', '', '', 'Ronnel \"Nono\" Lim', NULL, NULL),
(29, '000042', 'LGU GUINOBATAN', 'Albay', '', '', '', 'Ann Gemma Y. Ongjoco', NULL, NULL),
(30, '000044', 'LGU IROSIN', 'Sorsogon', '', '', '', 'Grace Hamor', NULL, NULL),
(31, '000043', 'LGU JOVELLAR', 'Albay', '', '', '', 'Jorem L. Arcangel', NULL, NULL),
(32, '000030', 'LGU JUBAN', 'Sorsogon', '', '', '', 'Rogel Fulleros', NULL, NULL),
(33, '000032', 'LGU LAGONOY', 'Camarines Sur', '', '', '', 'Dyego Luiz Pilapil', NULL, NULL),
(34, '000045', 'LGU LIBMANAN', 'Camarines Sur', '', '', '', 'Edelson Marfil', NULL, NULL),
(35, '000031', 'LGU LIBON', 'Albay', '', '', '', 'Markgregor Edward C. Sayson', NULL, NULL),
(36, '000037', 'LGU LIGAO', 'Albay', '', '', '', 'Fernando V. Gonzales', NULL, NULL),
(37, '000026', 'LGU MAGALLANES', 'Sorsogon', '', '', '', 'Maria Elena Ragrario', NULL, NULL),
(38, '000033', 'LGU MAGARAO', 'Camarines Sur', '', '', '', 'Atty Ma Maruja Se?ar\r', NULL, NULL),
(39, '000013', 'LGU MALILIPOT', 'Albay', '', '', '', 'Cenon B. Volante', NULL, NULL),
(40, '000039', 'LGU MALINAO', 'Albay', '', '', '', 'Sheryl Capus Bilo', NULL, NULL),
(41, '000014', 'LGU MANITO', 'Albay', '', '', '', 'Jerry D. Arizapa', NULL, NULL),
(42, '000049', 'MCKENZIE', '0', '', '', '', '0', NULL, NULL),
(43, '000046', 'LGU MILAOR', 'Camarines Sur', '', '', '', 'Bhing Euste', NULL, NULL),
(44, '000012', 'LGU NABUA', 'Camarines Sur', '', '', '', 'Fernando Simbulan', NULL, NULL),
(45, '000008', 'LGU OAS', 'Albay', '', '', '', 'John Kenneth M. Trinidad', NULL, NULL),
(46, '000035', 'LGU OCAMPO', 'Camarines Sur', '', '', '', 'Allan Go', NULL, NULL),
(47, '000074', 'OLD LEGAZPI AIRPORT (BICOL LOCO)', '0', '', '', '', '0', NULL, NULL),
(48, '000069', 'LGU PAMPLONA', 'Camarines Sur', '', '', '', 'Manuel Agustin', NULL, NULL),
(49, '000023', 'LGU PANGANIBAN', 'Catanduanes', '', '', '', 'Cesar Robles', NULL, NULL),
(50, '000073', 'LGU PASACAO', 'Camarines Sur', '', '', '', 'Ni?o A. Tayco', NULL, NULL),
(51, '000017', 'LGU PILAR', 'Sorsogon', '', '', '', 'Dennis Sy Reyes', NULL, NULL),
(52, '000029', 'LGU PIODURAN', 'Albay', '', '', '', 'Evangeline C. Arandia', NULL, NULL),
(53, '000011', 'LGU POLANGUI', 'Albay', '', '', '', 'Jesciel Richard G. Salceda', NULL, NULL),
(54, '000065', 'LGU PRIETO DIAZ', 'Sorsogon', '', '', '', 'Romeo Domasian', NULL, NULL),
(55, '000015', 'LGU RAGAY', 'Camarines Sur', '', '', '', 'Benchie G. Horibata', NULL, NULL),
(56, '000040', 'LGU SAN FERNANDO', 'Camarines Sur', '', '', '', 'Tin Danabar', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_30_060247_create-transaction-table', 1),
(5, '2026_04_30_152608_add_staff_fields_to_transactions', 2),
(6, '2026_05_01_054857_add_representative_name_to_transactions', 3),
(7, '2026_05_05_053620_add_weight_timestamps', 4),
(8, '2026_05_07_052356_add_transaction_type_to_transactions', 5),
(9, '2026_05_07_053012_create_clients_table', 5),
(10, '2026_05_07_085702_add_client_id_to_transactions_table', 6),
(11, '2026_06_08_135822_add_client_fields_to_clients_table', 7),
(12, '2026_06_12_121554_add_username_to_users_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('J6q42az60P6efVGCH9YXszkZEXOOCr85jD5hUQAw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjBiOG9HYW5oWFhvZFdsU2hXU3EyT1Y2NVBROWJLQnNhbnEyamFFZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kZWJ1Zy1kYiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781365885),
('Oe2cAaLNIVxpHVSjUoZjG61JPKQsv6DYQaS1b0bU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTG1hZjhEeTNNbTZqTE1VUk5vSGc3M2dyNTFWUVZhMklXT2psOXdGdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1781315089),
('tt5fg4erYzlkK32ViKpk05S8Hqhhhu3MQRBHsrVh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1BCbGZpTk9TVDhmZ0s0V0x5cnJGaTFjdkpsYjMyblVoQmlNb1RtMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQtcmVwb3J0IjtzOjU6InJvdXRlIjtOO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1781278210);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(255) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `representative_name` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `moisture_content` double DEFAULT NULL,
  `net_weight` double DEFAULT NULL,
  `tare_weight` double DEFAULT NULL,
  `gross_weight` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clerk` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `gross_time` timestamp NULL DEFAULT NULL,
  `tare_time` timestamp NULL DEFAULT NULL,
  `net_time` timestamp NULL DEFAULT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_no`, `plate_number`, `driver_name`, `representative_name`, `material`, `product`, `company`, `moisture_content`, `net_weight`, `tare_weight`, `gross_weight`, `created_at`, `updated_at`, `clerk`, `approved_by`, `gross_time`, `tare_time`, `net_time`, `transaction_type`, `client_id`) VALUES
(1, '0505-0001', 'LEX5457', 'Alex', 'Izeah', 'Minerals', 'Dumi', 'XYZ', 0.16, 14500, NULL, NULL, '2026-05-05 05:16:00', '2026-05-05 05:16:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '0505-0002', 'LEX5457', 'Alex', 'Izeah', 'Minerals', 'Dumi', 'XYZ', 0.16, 14630, NULL, NULL, '2026-05-05 05:18:17', '2026-05-05 05:18:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '0505-0003', 'LEX5457', 'Alex', 'Izeah', 'Solid Waste', 'Dumi', 'XYZ', 0.16, 14630, NULL, NULL, '2026-05-05 05:40:37', '2026-05-05 05:40:37', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '0505-0004', 'LEX5457', 'Alex', 'Izeah', 'Bottles', 'Bote', 'XYZ', 0.16, 14630, 14920, 29550, '2026-05-05 06:01:28', '2026-05-05 06:01:28', 'Alexander Mari Prelligera', 'Admin 1', '2026-05-05 05:51:21', '2026-05-05 05:51:21', '2026-05-05 05:51:21', NULL, NULL),
(6, '0505-0005', 'LEX5457', 'Alex', 'Izeah', 'Bottles', 'Bote', 'XYZ', 0.16, 14630, 14920, 29550, '2026-05-05 06:01:29', '2026-05-05 06:01:29', 'Alexander Mari Prelligera', 'Admin 1', '2026-05-05 05:51:21', '2026-05-05 05:51:21', '2026-05-05 05:51:21', NULL, NULL),
(7, '0506-0001', 'LEX5457', 'Alex', 'Syv', 'Bottles', 'Bote', 'XYZ', 0.16, 11099, 12314, 23413, '2026-05-06 01:31:24', '2026-05-06 01:31:24', 'Alexander Mari Prelligera', NULL, NULL, NULL, NULL, NULL, NULL),
(8, '0507-0001', 'ZEY0228', 'Lex', 'Syv', 'Solid Waste', NULL, 'ZYX', NULL, 15100, 14530, 29630, '2026-05-06 23:09:08', '2026-05-06 23:09:08', 'Alexander Mari Prelligera', 'Admin 1', '2026-05-07 07:08:01', '2026-05-07 07:08:01', '2026-05-07 07:08:01', 'incoming', NULL),
(9, '0507-0002', 'LEX5457', 'Alex', 'Izeah', 'Solid Waste', NULL, 'XYZ', NULL, 14000, 15650, 29650, '2026-05-07 01:08:31', '2026-05-07 01:08:31', 'Alexander Mari Prelligera', 'Admin 1', '2026-05-07 09:08:08', '2026-05-07 09:08:08', '2026-05-07 09:08:08', 'incoming', 1),
(10, '0510-0001', 'LEX5457', 'Alex', 'Syv', 'Minerals', NULL, 'ZYX', NULL, 15000, 14550, 29550, '2026-05-10 04:28:07', '2026-05-10 04:28:07', 'Alexander Mari Prelligera', 'Admin 1', '2026-05-10 12:27:29', '2026-05-10 12:27:29', '2026-05-10 12:27:29', 'incoming', 2),
(11, '0602-0001', 'LEX5457', 'Alex', 'Izeah', 'Solid Waste', NULL, 'XYZ', NULL, 0, NULL, NULL, '2026-06-02 06:16:21', '2026-06-02 06:16:21', 'Alexander Mari Prelligera', NULL, NULL, NULL, NULL, 'incoming', 1),
(12, '0602-0002', 'LEX5457', 'Alex', 'Izeah', 'Solid Waste', NULL, 'XYZ', NULL, 0, NULL, NULL, '2026-06-02 06:16:39', '2026-06-02 06:16:39', 'Alexander Mari Prelligera', NULL, NULL, NULL, NULL, 'incoming', 1),
(13, '0603-0001', 'LEX5457', 'Alex', 'Syv', 'Solid Waste', NULL, 'XYZ', NULL, 30.82, 49, 79.82, '2026-06-03 02:00:45', '2026-06-03 02:00:45', 'Alexander Mari Prelligera', 'Admin 1', '2026-06-03 10:00:02', '2026-06-03 10:00:02', '2026-06-03 10:00:02', 'incoming', 1),
(14, '0609-0001', 'LEX5457', 'Alex', NULL, 'Solid Waste', NULL, 'XYZ', NULL, -15, 15, NULL, '2026-06-09 05:23:40', '2026-06-09 05:23:40', 'Alexander Mari Prelligera', NULL, NULL, NULL, NULL, 'incoming', 1),
(15, '0609-0002', 'LEX5457', 'Alex', NULL, 'Solid Waste', NULL, 'XYZ', NULL, -15, 15, NULL, '2026-06-09 05:31:59', '2026-06-09 05:31:59', 'Alexander Mari Prelligera', NULL, NULL, NULL, NULL, 'incoming', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`) VALUES
(1, 'Alexander Mari Prelligera', 'amvprelligera174@gmail.com', NULL, '$2y$12$LWPpjIlZKZpEYkYAj3KmbOJ9FcPGceL2g0HDxkJrwlI3ufWCiE2um', 'VsBAY3mEbABQV1MLd4UbbhVDpNY0URjCUXoovwuH57qhtz6GUmAmJwN2nfSr', '2026-04-30 06:23:40', '2026-04-30 06:23:40', 'alexander');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_client_id_foreign` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
