-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2026 at 05:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `negosyo_center_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE `commodities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `brand_name` varchar(100) DEFAULT NULL,
  `unit_of_measure` varchar(50) NOT NULL DEFAULT '1 kg',
  `srp` decimal(10,2) DEFAULT NULL,
  `prevailing_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Establishments` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodities`
--

INSERT INTO `commodities` (`id`, `category_id`, `product_name`, `brand_name`, `unit_of_measure`, `srp`, `prevailing_price`, `is_active`, `created_at`, `updated_at`, `Establishments`) VALUES
(67, 1, '555 Sardines Tomato Sauce', 'Century Pacific Food Inc.', '150 g', 28.00, NULL, 1, '2026-08-18 16:08:02', '2026-09-08 07:09:19', 'CLICKPOINT SARI-SARI STORE'),
(68, 2, 'Master Chef', 'Suncrest Foods Inc.', '50 kg', 1000.00, NULL, 1, '2026-08-18 16:08:22', '2026-08-20 03:17:10', ''),
(69, 5, 'Diesel', 'Shell', 'Per Liter', 100.00, NULL, 1, '2026-08-18 16:09:25', '2026-08-20 01:09:57', ''),
(70, 4, 'Chicken Meat', 'Magnolia', '1 kg', 50.00, NULL, 1, '2026-08-20 01:54:41', '2026-08-20 03:09:43', ''),
(71, 3, 'Tomato', 'Del Monte', '1 kg', 50.00, NULL, 1, '2026-08-20 01:58:04', '2026-08-20 03:09:49', ''),
(72, 8, 'Pineapple', 'Dole Golden Pineapple', '1 kg', 50.00, NULL, 1, '2026-08-20 03:06:43', '2026-08-20 03:09:54', ''),
(73, 8, 'Apple', 'Apple Company', '1 kg', 50.00, 5.00, 1, '2026-08-20 03:09:35', '2026-09-07 18:14:18', ''),
(74, 1, 'Century Tuna Flakes in Oil', 'Century Pacific Food, Inc.', '150 g', 40.00, NULL, 1, '2026-08-20 03:12:47', '2026-08-20 03:15:20', ''),
(75, 2, 'Ganador Premium Rice', 'Ganador', '50kg', 1500.00, NULL, 1, '2026-08-20 03:19:36', '2026-08-20 03:20:06', ''),
(76, 4, 'Beef Carcass', 'Cargill', '1 kg', 300.00, NULL, 1, '2026-08-20 03:24:53', '2026-08-20 03:25:16', ''),
(77, 3, 'Potato', 'Del Monte', '1 kg', 60.00, NULL, 1, '2026-08-20 03:27:12', '2026-08-20 03:27:44', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commodity_category` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
