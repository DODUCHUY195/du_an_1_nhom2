-- Update script to enhance daily_log table for tour diary functionality
-- This script adds columns for incident tracking and resolution

-- Add columns for incident tracking
ALTER TABLE `daily_log` 
ADD COLUMN `incident` TEXT DEFAULT NULL COMMENT 'Description of any incident that occurred',
ADD COLUMN `resolution` TEXT DEFAULT NULL COMMENT 'How the incident was resolved',
ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Add index for better query performance
ALTER TABLE `daily_log` 
ADD INDEX `idx_schedule_date` (`schedule_id`, `log_date`),
ADD INDEX `idx_guide_schedule` (`guide_id`, `schedule_id`);