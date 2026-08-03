-- phpMyAdmin SQL Dump compatible
-- New table: calamity_incidents (Calamity Monitoring Module)
-- Lists MSMEs affected by calamities:
--   Type of Calamity (calamities), Date of Occurrence, Business Affected (juridicals),
--   Nature of Damage, Estimated cost of damages

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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

ALTER TABLE `calamity_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_juridical` (`juridical_id`),
  ADD KEY `idx_calamity` (`calamity_id`),
  ADD KEY `idx_damage_date` (`date_occurred`,`nature_of_damage`);

ALTER TABLE `calamity_incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `calamity_incidents`
  ADD CONSTRAINT `fk_incident_calamity` FOREIGN KEY (`calamity_id`) REFERENCES `calamities` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_incident_juridical` FOREIGN KEY (`juridical_id`) REFERENCES `juridicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
