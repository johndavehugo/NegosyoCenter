-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2026 at 05:10 AM
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `upblb_num` varchar(50) DEFAULT NULL,
  `street` varchar(150) DEFAULT NULL,
  `subdivision` varchar(150) DEFAULT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `upblb_num`, `street`, `subdivision`, `barangay`, `city`, `province`, `region`, `created_at`) VALUES
(1, 'Unit 3A, Lot 12', 'Rizal Street', 'Poblacion', 'Barangay 1', 'San Carlos City', 'Negros Occidental', 'Region VI', '2026-07-26 08:29:48'),
(2, 'Building B, Door 2', 'V. Gustilo Street', NULL, 'Barangay 2', 'San Carlos City', 'Negros Occidental', 'Region VI', '2026-07-26 08:29:48'),
(3, 'Purok Sunflower', 'National Highway', 'Villa Soledad', 'Palampas', 'San Carlos City', 'Negros Occidental', 'Region VI', '2026-07-26 08:29:48'),
(4, 'Block 5, Lot 8', 'Locsin Street', 'Silay Heights', 'Barangay 3', 'Silay City', 'Negros Occidental', 'Region VI', '2026-07-26 08:29:48'),
(5, 'Suite 101', 'Lacsons Street', 'Mandalagan', 'Barangay 18', 'Bacolod City', 'Negros Occidental', 'Region VI', '2026-07-26 08:29:48');

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
(1, 'Bagyong Kyle', 'TYPHOON', '0000-00-00', 'AHHH bagyo', '2026-07-29 00:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `calamity_incidents`
--

CREATE TABLE `calamity_incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `juridical_id` bigint(20) UNSIGNED NOT NULL,
  `calamity_id` bigint(20) UNSIGNED NOT NULL,
  `date_occurred` date NOT NULL,
  `nature_of_damage` enum('PARTIAL','TOTAL') NOT NULL,
  `estimated_cost_of_damages` decimal(12,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('PENDING_VERIFICATION','VERIFIED','AID_RELEASED') DEFAULT 'VERIFIED',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_no` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `special_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `entity_no`, `first_name`, `middle_name`, `last_name`, `address_id`, `created_at`, `updated_at`, `special_category`) VALUES
(1, 'EMP-2026-0001', 'Juan', 'Reyes', 'Dela Cruz', 1, '2026-07-26 08:29:48', '2026-07-29 22:21:26', '4ps Beneficiary'),
(2, 'EMP-2026-0002', 'Maria Clara', 'Santos', 'Alvarez', 2, '2026-07-26 08:29:48', '2026-07-29 22:21:26', 'Solo Parent'),
(3, 'EMP-2026-0003', 'Antonio', 'Luna', 'Montefalco', 3, '2026-07-26 08:29:48', '2026-07-29 22:21:26', 'Person with Disability'),
(4, 'EMP-2026-0004', 'Grace', 'Perez', 'Tan', 4, '2026-07-26 08:29:48', '2026-07-29 22:21:26', 'Youth Entreprenuer'),
(5, 'EMP-2026-0005', 'Jose', 'Protacio', 'Rizal', 5, '2026-07-26 08:29:48', '2026-07-29 22:21:26', '4ps Beneficiary'),
(7, 'EMP-0001', 'Juan', 'Dela', 'Cruz', NULL, '2026-07-29 11:30:56', '2026-07-29 11:30:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `juridicals`
--

CREATE TABLE `juridicals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entity_no` varchar(50) NOT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
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
(1, 'BUS-2026-0001', 1, 1, 'Dela Cruz General Merchandise', 'NEW', 'ACTIVE', '09171234567', 'contact@delacruzmdse.ph', 'Retail & Wholesale Trade', 250000.00, '2026-07-26 08:29:48', '2026-07-29 22:19:07'),
(2, 'BUS-2026-0002', 2, 2, 'Clara\'s Native Delicacies & Bakeshop', 'NEW', 'ACTIVE', '09189876543', 'info@clarasbakery.ph', 'Food & Beverage Processing', 850000.00, '2026-07-26 08:29:48', '2026-07-29 22:19:07'),
(3, 'BUS-2026-0003', 3, 3, 'Montefalco Agro-Industrial Supply', 'RENEWAL', 'ACTIVE', '09205551234', 'sales@montefalcoagro.com', 'Agriculture & Farm Services', 5500000.00, '2026-07-26 08:29:48', '2026-07-29 22:19:07'),
(4, 'BUS-2026-0004', 4, 4, 'Visayas Apex Tech & BPO Solutions', 'NEW', 'ACTIVE', '09998887766', 'support@visayasapex.com', 'Information Technology / BPO', 28000000.00, '2026-07-26 08:29:48', '2026-07-29 22:19:07'),
(5, 'BUS-2026-0005', 5, 5, 'Rizal Hardware & Construction Supply', 'RENEWAL', 'EXPIRED', '09170001122', 'admin@rizalhardware.ph', 'Construction & Hardware Material', 1200000.00, '2026-07-26 08:29:48', '2026-07-29 22:19:07');

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
  ADD KEY `address_id` (`address_id`),
  ADD KEY `idx_owner_name` (`last_name`,`first_name`);

--
-- Indexes for table `juridicals`
--
ALTER TABLE `juridicals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entity_no` (`entity_no`),
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `idx_business_name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `calamities`
--
ALTER TABLE `calamities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `juridicals`
--
ALTER TABLE `juridicals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
