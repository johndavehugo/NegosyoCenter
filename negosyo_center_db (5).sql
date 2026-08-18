-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2026 at 06:06 PM
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
(2, 'BAGYONG BILLY', 'FLOOD', '2026-08-02', 'rdryhtfu', '2026-08-03 05:11:48');

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
(65, 2, 'mais', '5', '5', 58.00, 2, 1, '2026-08-17 00:37:10', '2026-08-17 00:41:58'),
(66, 5, 'd', 'd', 'd', 66.00, 3, 1, '2026-08-18 15:47:31', '2026-08-18 16:00:50');

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
(5, 3, 'Fuel & Petroleum Products', '2026-08-03 05:15:08');

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

-- --------------------------------------------------------

--
-- Table structure for table `price_logs`
--

CREATE TABLE `price_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commodity_id` bigint(20) UNSIGNED NOT NULL,
  `prevailing_price` decimal(10,2) NOT NULL,
  `status` enum('WITHIN_SRP','OVERPRICED','BELOW_SRP') DEFAULT 'WITHIN_SRP',
  `monitored_by_agency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `monitored_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_logs`
--

INSERT INTO `price_logs` (`id`, `commodity_id`, `prevailing_price`, `status`, `monitored_by_agency_id`, `monitored_at`) VALUES
(38, 65, 56.00, '', 2, '2026-08-18 16:06:17'),
(39, 66, 0.00, '', 3, '2026-08-18 16:06:10');

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
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `calamities`
--
ALTER TABLE `calamities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `calamity_incidents`
--
ALTER TABLE `calamity_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `juridicals`
--
ALTER TABLE `juridicals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `price_logs`
--
ALTER TABLE `price_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
-- Constraints for table `commodities`
--
ALTER TABLE `commodities`
  ADD CONSTRAINT `fk_commodity_category` FOREIGN KEY (`category_id`) REFERENCES `commodity_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  ADD CONSTRAINT `fk_category_agency` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Constraints for table `price_logs`
--
ALTER TABLE `price_logs`
  ADD CONSTRAINT `fk_price_agency` FOREIGN KEY (`monitored_by_agency_id`) REFERENCES `agencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_price_commodity` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
