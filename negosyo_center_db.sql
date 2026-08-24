-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 02:32 AM
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
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` varchar(100) NOT NULL,
  `upblb_num` varchar(50) DEFAULT NULL,
  `street` varchar(150) DEFAULT NULL,
  `subdivision` varchar(150) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `upblb_num`, `street`, `subdivision`, `barangay`, `city`, `province`, `region`, `created_at`) VALUES
('addr-01a00da9-fc0f-7ff3-af4a-a225881c8a5c', '', '', '', '', '', '', '', '2026-08-17 03:00:39'),
('addr-01a00db7-91b8-762e-8c0a-a5df96812144', '', '', '', '', '', '', '', '2026-08-17 03:15:29'),
('addr-07b219-c4f6-bd713747-f04c', '', '', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-19 06:12:39'),
('addr-23f6fc-1d90-5a0c76cb-c7a8', '', '', '', 'Barangay IV', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:14:37'),
('addr-24fc0b-ed00-f33c1320-2448', '', '', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-19 02:36:22'),
('addr-39e29a-ef81-5d971c6c-6cbe', '', '', '', 'Barangay IV', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-18 07:10:48'),
('addr-5340f9-ac48-931b724a-c8c7', '', 'F.C LEDESMA AVE.', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:49'),
('addr-5b4275-9dc3-8511f30a-cabf', '', '', '', 'Barangay III', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-18 07:10:48'),
('addr-700c8f-26e3-e8b3fd4d-a629', '', 'Endrina CL ledesma  Ave. brgy 2', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-19 06:12:39'),
('addr-72b807-713c-3ab6ba6c-2ba4', 'enk', 'grove st', 'cheeseball', 'Suligan', 'Tabuan-Lasa', 'Basilan', 'Bangsamoro Autonomous Region In Muslim Mindanao (BARMM)', '2026-08-17 03:22:48'),
('addr-a37f49-6e25-3ab02132-b568', '', '', '', 'Rizal', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:28'),
('addr-c30504-230c-71902c84-6aba', '', 'RIZAL ST.', '', 'Barangay V', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:00:39'),
('addr-c56a0a-74fc-925506b2-a5ea', '', '', '', 'Rizal', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:28'),
('addr-d02419-852f-e6861d03-e3ea', '', 'LOCSIN ST.,', '', 'Barangay IV', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:14:37'),
('addr-d348f7-dfce-2d1d86d0-4613', '', '', '', 'Palampas', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:15:29'),
('addr-e8decc-8db5-7bfe3e86-8b2f', 'belly', 'hey', 'there', 'Butansapa', 'Mogpog', 'Marinduque', 'MIMAROPA Region', '2026-08-17 03:22:48'),
('addr-ecf745-0c94-c0291dba-648b', '', '', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-19 02:36:22'),
('addr-fe1f3a-bfd2-4cee0ee2-b84b', '', 'F.C. LEDESMA AVE.', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `coverage` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agencies`
--

INSERT INTO `agencies` (`id`, `code`, `name`, `coverage`, `created_at`) VALUES
(1, 'DTI', 'Department of Trade and Industry', 'Basic Necessities and Prime Commodities (BNPC)', '2026-08-03 05:15:08'),
(2, 'DA', 'Department of Agriculture', 'Agricultural commodities', '2026-08-03 05:15:08'),
(3, 'DOE', 'Department of Energy', 'Petroleum products', '2026-08-03 05:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `calamities`
--

CREATE TABLE `calamities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `calamity_type` enum('TYPHOON','FLOOD','EARTHQUAKE','FIRE','LANDSLIDE','OTHER') NOT NULL,
  `declaration_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calamities`
--

INSERT INTO `calamities` (`id`, `name`, `calamity_type`, `declaration_date`, `description`, `created_at`) VALUES
(1, 'Bagyong Kyle', 'TYPHOON', '0000-00-00', 'AHHH bagyo', '2026-07-29 00:46:58'),
(2, 'Bagyong Frenk', 'LANDSLIDE', '2026-08-01', 'Frenk gaunsa ka diha frenk', '2026-08-06 00:24:02'),
(3, 'Bagyong Belly', 'TYPHOON', '2026-08-11', 'Cheeseball', '2026-08-11 07:02:44'),
(4, 'Typhoon Rango', 'TYPHOON', '2026-08-02', '', '2026-08-19 07:22:52'),
(5, 'Typhoon Jowseph', 'TYPHOON', '2026-07-28', '', '2026-08-19 07:29:22'),
(6, 'Typhoon Ding', 'TYPHOON', '2026-08-01', '', '2026-08-19 07:52:21'),
(7, 'Typhoon Zor', 'TYPHOON', '2026-08-01', '', '2026-08-19 08:00:37'),
(8, 'Typhoon Donan', 'TYPHOON', '2026-08-01', '', '2026-08-19 08:17:16'),
(9, 'Typhoon Bert', 'TYPHOON', '2026-08-01', '', '2026-08-19 08:35:04'),
(10, 'typhoon ralph', 'TYPHOON', '2026-08-02', '', '2026-08-19 08:55:28'),
(11, 'Earthquake', 'EARTHQUAKE', '2026-08-01', '', '2026-08-20 05:16:03'),
(12, 'epoy bday', 'TYPHOON', '2026-08-20', '', '2026-08-20 05:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `calamity_incidents`
--

CREATE TABLE `calamity_incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `juridical_id` varchar(100) NOT NULL,
  `calamity_id` bigint(20) UNSIGNED NOT NULL,
  `date_occurred` date NOT NULL,
  `nature_of_damage` enum('PARTIAL','TOTAL') NOT NULL,
  `estimated_cost_of_damages` decimal(12,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('PENDING_VERIFICATION','VERIFIED','AID_RELEASED') DEFAULT 'VERIFIED',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calamity_incidents`
--

INSERT INTO `calamity_incidents` (`id`, `juridical_id`, `calamity_id`, `date_occurred`, `nature_of_damage`, `estimated_cost_of_damages`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(2, '1', 3, '2026-08-11', 'PARTIAL', 80000.00, '', 'AID_RELEASED', '2026-08-11 07:07:38', '2026-08-11 07:08:11'),
(4, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 2, '2026-08-04', 'TOTAL', 500000.00, '', 'VERIFIED', '2026-08-19 07:19:02', '2026-08-19 07:19:02'),
(5, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 4, '2026-08-08', 'TOTAL', 3000000.00, '', 'VERIFIED', '2026-08-19 07:23:35', '2026-08-19 07:23:35'),
(6, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', 5, '2026-08-06', 'TOTAL', 150000.00, '', 'VERIFIED', '2026-08-19 07:29:59', '2026-08-19 07:29:59'),
(7, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 3, '2026-08-06', 'TOTAL', 500000.00, '', 'VERIFIED', '2026-08-19 07:51:31', '2026-08-19 07:51:31'),
(8, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 6, '2026-08-04', 'TOTAL', 20000.00, '', 'VERIFIED', '2026-08-19 07:52:58', '2026-08-19 07:53:53'),
(11, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 8, '2026-08-12', 'TOTAL', 600000.00, '', 'VERIFIED', '2026-08-19 08:17:55', '2026-08-19 08:17:55'),
(13, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 7, '2026-08-01', 'TOTAL', 450000.00, '', 'VERIFIED', '2026-08-19 08:29:07', '2026-08-19 08:29:07'),
(15, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 9, '2026-08-02', 'TOTAL', 300000.00, '', 'VERIFIED', '2026-08-19 08:35:49', '2026-08-19 08:35:49'),
(16, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', 10, '2026-08-04', 'TOTAL', 100000.00, '', 'VERIFIED', '2026-08-19 08:55:55', '2026-08-19 08:55:55'),
(17, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', 10, '2026-08-06', 'TOTAL', 300000.00, '', 'VERIFIED', '2026-08-19 08:56:28', '2026-08-19 08:56:28'),
(18, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 11, '2026-08-01', 'TOTAL', 800000.00, '', 'VERIFIED', '2026-08-20 05:17:10', '2026-08-20 05:17:10'),
(19, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 12, '2026-08-20', 'PARTIAL', 824325.00, '', 'VERIFIED', '2026-08-20 05:25:34', '2026-08-20 05:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `calamity_incident_businesses`
--

CREATE TABLE `calamity_incident_businesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `juridical_id` varchar(100) NOT NULL,
  `date_occurred` date DEFAULT NULL,
  `nature_of_damage` enum('PARTIAL','TOTAL') DEFAULT NULL,
  `status` enum('PENDING_VERIFICATION','VERIFIED','AID_RELEASED') DEFAULT 'VERIFIED',
  `estimated_cost_of_damages` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calamity_incident_businesses`
--

INSERT INTO `calamity_incident_businesses` (`id`, `incident_id`, `juridical_id`, `date_occurred`, `nature_of_damage`, `status`, `estimated_cost_of_damages`, `remarks`) VALUES
(3, 4, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', NULL, NULL, 'VERIFIED', 0.00, NULL),
(4, 4, 'neg-01a017e0-7994-7fc9-a09d-dae142bc3728', NULL, NULL, 'VERIFIED', 0.00, NULL),
(5, 4, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', NULL, NULL, 'VERIFIED', 0.00, NULL),
(6, 4, 'neg-01a00db6-c5c1-7cb4-80f6-670aba5d5de5', NULL, NULL, 'VERIFIED', 0.00, NULL),
(7, 5, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', NULL, NULL, 'VERIFIED', 0.00, NULL),
(8, 5, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', NULL, NULL, 'VERIFIED', 0.00, NULL),
(9, 5, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', NULL, NULL, 'VERIFIED', 0.00, NULL),
(10, 6, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', NULL, NULL, 'VERIFIED', 0.00, NULL),
(11, 6, 'neg-01a00db7-91b8-7248-b895-52fdfb6893da', NULL, NULL, 'VERIFIED', 0.00, NULL),
(12, 6, 'neg-01a00db5-ba4e-72fb-9f68-84fc274e0a1d', NULL, NULL, 'VERIFIED', 0.00, NULL),
(13, 7, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', NULL, NULL, 'VERIFIED', 0.00, NULL),
(14, 7, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', NULL, NULL, 'VERIFIED', 0.00, NULL),
(15, 7, 'neg-01a00da9-fc0f-78ca-98bd-d8c03216f410', NULL, NULL, 'VERIFIED', 0.00, NULL),
(16, 7, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', NULL, NULL, 'VERIFIED', 0.00, NULL),
(17, 8, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', NULL, NULL, 'VERIFIED', 0.00, NULL),
(18, 8, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', NULL, NULL, 'VERIFIED', 0.00, NULL),
(19, 8, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', NULL, NULL, 'VERIFIED', 0.00, NULL),
(25, 11, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', NULL, NULL, 'VERIFIED', 100000.00, NULL),
(26, 11, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', NULL, NULL, 'VERIFIED', 200000.00, NULL),
(27, 11, 'neg-01a00db7-91b8-7248-b895-52fdfb6893da', NULL, NULL, 'VERIFIED', 300000.00, NULL),
(30, 13, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', '2026-08-01', 'TOTAL', 'VERIFIED', 100000.00, ''),
(31, 13, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', '2026-08-02', 'TOTAL', 'VERIFIED', 300000.00, ''),
(32, 13, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', '2026-08-03', 'TOTAL', 'VERIFIED', 50000.00, ''),
(35, 15, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', '2026-08-02', 'TOTAL', 'VERIFIED', 100000.00, ''),
(36, 15, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', '2026-08-03', 'TOTAL', 'VERIFIED', 200000.00, ''),
(37, 16, 'neg-01a018a6-7f12-7c45-9569-9e9f862291f5', '2026-08-04', 'TOTAL', 'VERIFIED', 100000.00, ''),
(38, 17, 'neg-01a00dbe-46a6-7712-b958-8a27d579a59d', '2026-08-06', 'TOTAL', 'VERIFIED', 200000.00, ''),
(39, 17, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', '2026-08-07', 'TOTAL', 'VERIFIED', 100000.00, ''),
(40, 19, 'neg-01a013b5-5e99-7462-8f41-1323e5f8a342', '2026-08-20', 'PARTIAL', 'VERIFIED', 43545.00, ''),
(41, 19, 'neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', '2026-08-20', 'TOTAL', 'PENDING_VERIFICATION', 345435.00, ''),
(42, 19, 'neg-01a00db5-ba4e-72fb-9f68-84fc274e0a1d', '2026-08-20', 'PARTIAL', 'AID_RELEASED', 435345.00, '');

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
  `agency_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodities`
--

INSERT INTO `commodities` (`id`, `category_id`, `product_name`, `brand_name`, `unit_of_measure`, `srp`, `agency_id`, `is_active`, `created_at`, `updated_at`) VALUES
(66, 5, 'Diesel', 'Shell', 'Per Liter', 100.00, 3, 1, '2026-08-18 15:47:31', '2026-08-20 06:17:35'),
(67, 1, '555 Sardines Tomato Sauce', 'Century Pacific Food Inc.', '150 g', 28.00, 1, 1, '2026-08-18 16:08:02', '2026-08-20 06:19:30'),
(68, 2, 'Master Chef', 'Suncrest Foods Inc.', '50 kg', 1000.00, 2, 1, '2026-08-18 16:08:22', '2026-08-20 06:17:57'),
(69, 5, 'Gasoline', 'Petron Blaze', 'Per Liter', 60.00, 3, 1, '2026-08-18 16:09:25', '2026-08-20 05:50:49'),
(70, 8, 'Apple', 'Apple Company', '1 kg', 50.00, 2, 1, '2026-08-20 06:10:25', '2026-08-20 06:15:48'),
(71, 8, 'Pineapple', 'Dole Golden Pineapple', '1 kg', 55.00, 2, 1, '2026-08-20 06:10:50', '2026-08-20 06:15:53'),
(72, 2, 'Ganador Premium Rice', 'Ganador', '50 kg', 1500.00, 2, 1, '2026-08-20 06:11:17', '2026-08-20 06:18:02'),
(73, 4, 'Beef Carcass', 'Cargill', '1 kg', 300.00, 2, 1, '2026-08-20 06:12:35', '2026-08-20 06:17:16'),
(74, 4, 'Chicken Meat', 'Magnolia', '1 kg', 200.00, 2, 1, '2026-08-20 06:13:17', '2026-08-20 06:17:21'),
(75, 3, 'Tomato', 'Del Monte', '1 kg', 65.00, 2, 1, '2026-08-20 06:15:15', '2026-08-20 06:16:06'),
(76, 3, 'Potato', 'Del Monte', '1 kg', 60.00, 2, 1, '2026-08-20 06:15:35', '2026-08-20 06:16:02'),
(77, 1, 'Century Tuna Flakes in OIl', 'Century Pacific Food Inc.', '150 g', 40.00, 1, 1, '2026-08-20 06:19:08', '2026-08-20 06:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `commodity_categories`
--

CREATE TABLE `commodity_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agency_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodity_categories`
--

INSERT INTO `commodity_categories` (`id`, `agency_id`, `name`, `created_at`) VALUES
(1, 1, 'Canned Goods & Processed Foods', '2026-08-03 05:15:08'),
(2, 2, 'Grains & Rice', '2026-08-03 05:15:08'),
(3, 2, 'Vegetables & Root Crops', '2026-08-03 05:15:08'),
(4, 2, 'Livestock & Poultry Products', '2026-08-03 05:15:08'),
(5, 3, 'Fuel & Petroleum Products', '2026-08-03 05:15:08'),
(8, 2, 'Fruits', '2026-08-20 06:09:59'),
(9, 1, 'Household Appliances', '2026-08-20 06:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` varchar(100) NOT NULL,
  `entity_no` varchar(50) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `address_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `special_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `entity_no`, `full_name`, `address_id`, `created_at`, `updated_at`, `special_category`) VALUES
('emp-051ec8-9c8b-d9fa5350-e86f', 'GGTQBX1285', 'LUMINISCENT INC.', 'addr-01a00db7-91b8-762e-8c0a-a5df96812144', '2026-08-17 03:15:29', '2026-08-17 03:15:29', 'None'),
('emp-16986e-5175-4bac270a-8f8f', 'UDBNQA4863', 'EVANGELINE Y. RAMAS', 'addr-a37f49-6e25-3ab02132-b568', '2026-08-17 03:13:28', '2026-08-17 03:13:28', 'None'),
('emp-3fb7b9-e5e2-148d32a8-e337', 'JSGDHX0554', 'Jhay Arjhay', 'addr-72b807-713c-3ab6ba6c-2ba4', '2026-08-17 03:22:48', '2026-08-17 04:52:18', 'Young Entrepreneur'),
('emp-825fe4-9e95-256634cb-a24f', 'UCAWQC8632', 'VIPER\'S MUSCLE AND FITNESS GYM', 'addr-23f6fc-1d90-5a0c76cb-c7a8', '2026-08-17 03:14:37', '2026-08-17 03:14:37', 'None'),
('emp-8bbd88-c0f7-16a7db84-e163', 'DFEAHF7704', 'GLOBAL AUTHENTIC WEAR CORP.', 'addr-01a00da9-fc0f-7ff3-af4a-a225881c8a5c', '2026-08-17 03:00:39', '2026-08-17 03:00:39', 'None'),
('emp-9e128f-fdd2-95dc4e7b-ca9c', 'JEDVPA9659', 'CHARLENE\'S FASHION & BRIDAL RENTAL', 'addr-39e29a-ef81-5d971c6c-6cbe', '2026-08-18 07:10:48', '2026-08-18 07:10:48', 'None'),
('emp-c2a965-f2e5-bcd9cce9-7cff', 'UMAGWO5478', 'MR. C FOODS', 'addr-07b219-c4f6-bd713747-f04c', '2026-08-19 06:12:39', '2026-08-19 06:12:39', 'None'),
('emp-d213f0-3f4e-a36a640f-1144', 'EGXAES7188', 'K.M.A. ARCHITECTURAL SERVICES', 'addr-ecf745-0c94-c0291dba-648b', '2026-08-19 02:36:22', '2026-08-20 01:59:56', ''),
('emp-fb7186-4db6-a295fba0-5a11', 'IAGOQR3354', 'CLICKPOINT SARI-SARI STORE', 'addr-fe1f3a-bfd2-4cee0ee2-b84b', '2026-08-17 03:13:49', '2026-08-17 03:13:49', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `juridicals`
--

CREATE TABLE `juridicals` (
  `id` varchar(100) NOT NULL,
  `entity_no` varchar(50) NOT NULL,
  `employer_id` varchar(100) NOT NULL,
  `address_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `registration_type` varchar(50) DEFAULT NULL,
  `bus_status` varchar(30) DEFAULT 'ACTIVE',
  `contact_no` varchar(30) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `line_of_industry` varchar(150) DEFAULT NULL,
  `capitalization` decimal(15,2) NOT NULL DEFAULT 0.00,
  `category` enum('MICRO','SMALL','MEDIUM','LARGE') GENERATED ALWAYS AS (case when `capitalization` <= 3000000.00 then 'MICRO' when `capitalization` <= 15000000.00 then 'SMALL' when `capitalization` <= 100000000.00 then 'MEDIUM' else 'LARGE' end) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `juridicals`
--

INSERT INTO `juridicals` (`id`, `entity_no`, `employer_id`, `address_id`, `name`, `registration_type`, `bus_status`, `contact_no`, `contact_email`, `line_of_industry`, `capitalization`, `created_at`, `updated_at`) VALUES
('neg-01a00da9-fc0f-78ca-98bd-d8c03216f410', 'BBNYOM0018', 'emp-8bbd88-c0f7-16a7db84-e163', 'addr-c30504-230c-71902c84-6aba', 'GLOBAL AUTHENTIC WEAR CORPORATION - RIZAL ST.', 'NEW', 'ACTIVE', '09452184759', 'speednetshop02@gmail.com', 'WHOLESALE AND RETAIL TRADE', 0.00, '2026-08-17 03:00:39', '2026-08-17 03:00:39'),
('neg-01a00db5-ba4e-72fb-9f68-84fc274e0a1d', 'WKWUHR4133', 'emp-16986e-5175-4bac270a-8f8f', 'addr-c56a0a-74fc-925506b2-a5ea', 'EVANGELINE Y. RAMAS - SARISARI', 'NEW', 'ACTIVE', '09212121079', 'ramasvangie@gmail.com', 'WHOLESALE AND RETAIL TRADE', 0.00, '2026-08-17 03:13:28', '2026-08-17 03:13:28'),
('neg-01a00db6-0d0f-7201-a0ff-f68c231e5830', 'QXKGVX7632', 'emp-fb7186-4db6-a295fba0-5a11', 'addr-5340f9-ac48-931b724a-c8c7', 'CLICKPOINT SARI-SARI STORE', 'NEW', 'ACTIVE', '09166349898', '-', 'WHOLESALE AND RETAIL TRADE', 0.00, '2026-08-17 03:13:49', '2026-08-17 03:13:49'),
('neg-01a00db6-c5c1-7cb4-80f6-670aba5d5de5', 'PXIJKB7604', 'emp-825fe4-9e95-256634cb-a24f', 'addr-d02419-852f-e6861d03-e3ea', 'VIPER\'S MUSCLE AND FITNESS GYM', 'NEW', 'ACTIVE', '.', '.', 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES', 0.00, '2026-08-17 03:14:37', '2026-08-17 03:14:37'),
('neg-01a00db7-91b8-7248-b895-52fdfb6893da', 'BVBPWF6784', 'emp-051ec8-9c8b-d9fa5350-e86f', 'addr-d348f7-dfce-2d1d86d0-4613', 'LUMINISCENT INC.-GAISANO CAPITAL', 'NEW', 'ACTIVE', '09153531877', 'sayingga98@gmail.com', 'WHOLESALE AND RETAIL TRADE', 0.00, '2026-08-17 03:15:29', '2026-08-17 03:15:29'),
('neg-01a00dbe-46a6-7712-b958-8a27d579a59d', 'AAUKTT6112', 'emp-3fb7b9-e5e2-148d32a8-e337', 'addr-e8decc-8db5-7bfe3e86-8b2f', 'Epoy Flower Shop', 'NEW', 'ACTIVE', '111111111111111111111', 'epoy', 'FISHING', 9999999999999.99, '2026-08-17 03:22:48', '2026-08-17 08:46:55'),
('neg-01a013b5-5e99-7462-8f41-1323e5f8a342', 'ADDOTW9568', 'emp-9e128f-fdd2-95dc4e7b-ca9c', 'addr-5b4275-9dc3-8511f30a-cabf', 'CHARLENE\'S FASHION & BRIDAL RENTAL - S. CARMONA ST.', 'RENEWAL', 'ACTIVE', '09122270903', 'cbratteng@yahoo.com', 'REAL ESTATE, RENTING, AND BUSINESS ACTIVITIES', 4000000.00, '2026-08-18 07:10:48', '2026-08-20 03:36:02'),
('neg-01a017e0-7994-7fc9-a09d-dae142bc3728', 'AAAUJC2263', 'emp-d213f0-3f4e-a36a640f-1144', 'addr-24fc0b-ed00-f33c1320-2448', 'K.M.A. ARCHITECTURAL SERVICES - SAN JULIO SUBD.', 'RENEWAL', 'INACTIVE', '09778198545', 'acabalkimbie@gmail.com', 'OTHER COMMUNITY, SOCIAL AND PERSONAL SERVICE ACTIVITIES', 6000000.00, '2026-08-19 02:36:22', '2026-08-20 02:00:32'),
('neg-01a018a6-7f12-7c45-9569-9e9f862291f5', 'CQWDAN1025', 'emp-c2a965-f2e5-bcd9cce9-7cff', 'addr-700c8f-26e3-e8b3fd4d-a629', 'MR. C FOODS - CENTER MALL', 'NEW', 'ACTIVE', '09173014986', 'crizz_chef@yahoo.com', 'HOTELS AND RESTAURANTS', 9000000.00, '2026-08-19 06:12:39', '2026-08-19 06:12:39');

-- --------------------------------------------------------

--
-- Table structure for table `price_logs`
--

CREATE TABLE `price_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commodity_id` bigint(20) UNSIGNED NOT NULL,
  `prevailing_price` decimal(10,2) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `monitored_by_agency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `monitored_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_logs`
--

INSERT INTO `price_logs` (`id`, `commodity_id`, `prevailing_price`, `status`, `monitored_by_agency_id`, `monitored_at`) VALUES
(39, 66, 0.00, 'ACTIVE', 3, '2026-08-20 06:17:35'),
(40, 67, 0.00, 'ACTIVE', 1, '2026-08-20 06:19:30'),
(41, 68, 0.00, 'ACTIVE', 2, '2026-08-20 06:17:57'),
(42, 69, 0.00, 'ACTIVE', 3, '2026-08-20 05:50:49'),
(43, 70, 0.00, 'ACTIVE', 2, '2026-08-20 06:15:48'),
(44, 71, 0.00, 'ACTIVE', 2, '2026-08-20 06:15:53'),
(45, 76, 0.00, 'ACTIVE', 2, '2026-08-20 06:16:02'),
(46, 75, 0.00, 'ACTIVE', 2, '2026-08-20 06:16:06'),
(47, 73, 0.00, 'ACTIVE', 2, '2026-08-20 06:17:16'),
(48, 74, 0.00, 'ACTIVE', 2, '2026-08-20 06:17:21'),
(49, 72, 0.00, 'ACTIVE', 2, '2026-08-20 06:18:02'),
(50, 77, 0.00, 'ACTIVE', 1, '2026-08-20 06:19:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_address_location` (`city`,`barangay`);

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `calamities`
--
ALTER TABLE `calamities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_juridical` (`juridical_id`),
  ADD KEY `idx_calamity` (`calamity_id`),
  ADD KEY `idx_damage_date` (`date_occurred`,`nature_of_damage`);

--
-- Indexes for table `calamity_incident_businesses`
--
ALTER TABLE `calamity_incident_businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_incident_business` (`incident_id`,`juridical_id`),
  ADD KEY `idx_incident` (`incident_id`);

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commodity_category` (`category_id`);

--
-- Indexes for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_agency` (`agency_id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entity_no` (`entity_no`),
  ADD KEY `employers_ibfk_1` (`address_id`);

--
-- Indexes for table `juridicals`
--
ALTER TABLE `juridicals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entity_no` (`entity_no`),
  ADD KEY `idx_business_name` (`name`),
  ADD KEY `juridicals_ibfk_1` (`employer_id`),
  ADD KEY `juridicals_ibfk_2` (`address_id`);

--
-- Indexes for table `price_logs`
--
ALTER TABLE `price_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price_commodity` (`commodity_id`),
  ADD KEY `idx_monitored_agency` (`monitored_by_agency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `calamities`
--
ALTER TABLE `calamities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `calamity_incident_businesses`
--
ALTER TABLE `calamity_incident_businesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `price_logs`
--
ALTER TABLE `price_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calamity_incident_businesses`
--
ALTER TABLE `calamity_incident_businesses`
  ADD CONSTRAINT `fk_ib_incident` FOREIGN KEY (`incident_id`) REFERENCES `calamity_incidents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employers`
--
ALTER TABLE `employers`
  ADD CONSTRAINT `employers_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `juridicals`
--
ALTER TABLE `juridicals`
  ADD CONSTRAINT `juridicals_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`),
  ADD CONSTRAINT `juridicals_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
