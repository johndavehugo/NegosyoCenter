-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2026 at 05:16 AM
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
  `region` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `upblb_num`, `street`, `subdivision`, `barangay`, `city`, `province`, `region`, `created_at`) VALUES
('addr-01a00da9-fc0f-7ff3-af4a-a225881c8a5c', '', '', '', '', '', '', '', '2026-08-17 03:00:39'),
('addr-01a00db7-91b8-762e-8c0a-a5df96812144', '', '', '', '', '', '', '', '2026-08-17 03:15:29'),
('addr-23f6fc-1d90-5a0c76cb-c7a8', '', '', '', 'Barangay IV', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:14:37'),
('addr-5340f9-ac48-931b724a-c8c7', '', 'F.C LEDESMA AVE.', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:49'),
('addr-a37f49-6e25-3ab02132-b568', '', '', '', 'Rizal', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:28'),
('addr-c30504-230c-71902c84-6aba', '', 'RIZAL ST.', '', 'Barangay V', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:00:39'),
('addr-c56a0a-74fc-925506b2-a5ea', '', '', '', 'Rizal', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:28'),
('addr-d02419-852f-e6861d03-e3ea', '', 'LOCSIN ST.,', '', 'Barangay IV', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:14:37'),
('addr-d348f7-dfce-2d1d86d0-4613', '', '', '', 'Palampas', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:15:29'),
('addr-fe1f3a-bfd2-4cee0ee2-b84b', '', 'F.C. LEDESMA AVE.', '', 'Barangay II', 'City of San Carlos', 'Negros Occidental', 'Negros Island Region (NIR)', '2026-08-17 03:13:49');

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
(3, 'Bagyong Belly', 'TYPHOON', '2026-08-11', 'Cheeseball', '2026-08-11 07:02:44');

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
(2, '1', 3, '2026-08-11', 'PARTIAL', 80000.00, '', 'AID_RELEASED', '2026-08-11 07:07:38', '2026-08-11 07:08:11');

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
('emp-825fe4-9e95-256634cb-a24f', 'UCAWQC8632', 'VIPER\'S MUSCLE AND FITNESS GYM', 'addr-23f6fc-1d90-5a0c76cb-c7a8', '2026-08-17 03:14:37', '2026-08-17 03:14:37', 'None'),
('emp-8bbd88-c0f7-16a7db84-e163', 'DFEAHF7704', 'GLOBAL AUTHENTIC WEAR CORP.', 'addr-01a00da9-fc0f-7ff3-af4a-a225881c8a5c', '2026-08-17 03:00:39', '2026-08-17 03:00:39', 'None'),
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
('neg-01a00db7-91b8-7248-b895-52fdfb6893da', 'BVBPWF6784', 'emp-051ec8-9c8b-d9fa5350-e86f', 'addr-d348f7-dfce-2d1d86d0-4613', 'LUMINISCENT INC.-GAISANO CAPITAL', 'NEW', 'ACTIVE', '09153531877', 'sayingga98@gmail.com', 'WHOLESALE AND RETAIL TRADE', 0.00, '2026-08-17 03:15:29', '2026-08-17 03:15:29');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calamities`
--
ALTER TABLE `calamities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  ADD CONSTRAINT `fk_incident_calamity` FOREIGN KEY (`calamity_id`) REFERENCES `calamities` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_incident_juridical` FOREIGN KEY (`juridical_id`) REFERENCES `juridicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
