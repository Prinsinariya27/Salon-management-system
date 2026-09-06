-- Update tblorders table to add new columns for checkout
-- Run this SQL to add missing columns

ALTER TABLE `tblorders` 
ADD COLUMN IF NOT EXISTS `Address` TEXT DEFAULT NULL AFTER `CustomerPhone`,
ADD COLUMN IF NOT EXISTS `City` varchar(100) DEFAULT NULL AFTER `Address`,
ADD COLUMN IF NOT EXISTS `Pincode` varchar(10) DEFAULT NULL AFTER `City`,
ADD COLUMN IF NOT EXISTS `PaymentMethod` varchar(50) DEFAULT NULL AFTER `Pincode`;

-- Display the updated table structure
DESCRIBE `tblorders`;
