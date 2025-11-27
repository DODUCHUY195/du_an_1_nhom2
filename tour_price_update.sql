-- Add the missing tour_price table for seasonal pricing functionality
CREATE TABLE IF NOT EXISTS `tour_price` (
  `price_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `tour_id` int UNSIGNED NOT NULL,
  `season` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`price_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `tour_price_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add start_date and end_date columns to tour table if they don't exist
ALTER TABLE `tour` 
  ADD COLUMN IF NOT EXISTS `start_date` date DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `end_date` date DEFAULT NULL;

-- Add the date constraint if it doesn't exist
SET @constraint_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tour' AND CONSTRAINT_NAME = 'chk_date');
SET @sql := IF(@constraint_exists = 0, 'ALTER TABLE `tour` ADD CONSTRAINT chk_date CHECK (end_date IS NULL OR end_date >= start_date)', 'SELECT "Constraint already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update the status enum to include all values if needed
ALTER TABLE `tour` 
  MODIFY COLUMN `status` enum('draft','pending_approval','active','inactive','suspended','cancelled','completed') COLLATE utf8mb4_general_ci DEFAULT 'draft';

-- Add image column to tour table if it doesn't exist (for backward compatibility)
ALTER TABLE `tour` 
  ADD COLUMN IF NOT EXISTS `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL;

-- Update tour_image table to match expected structure
ALTER TABLE `tour_image` 
  CHANGE COLUMN IF EXISTS `image_path` `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  CHANGE COLUMN IF EXISTS `is_main` `is_primary` tinyint(1) DEFAULT '0';

-- Add logs_approved column to tour_schedule if it doesn't exist
ALTER TABLE `tour_schedule` 
  ADD COLUMN IF NOT EXISTS `logs_approved` tinyint(4) DEFAULT '0';