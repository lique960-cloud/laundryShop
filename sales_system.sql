-- ============================================
-- HypeLaundry Sales & Inventory Management System
-- Database Migration Script
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Add role column to user table if not exists
ALTER TABLE `user` ADD COLUMN IF NOT EXISTS `user_role` ENUM('admin','staff') NOT NULL DEFAULT 'admin' AFTER `user_password`;
ALTER TABLE `user` ADD COLUMN IF NOT EXISTS `user_fullname` VARCHAR(100) NOT NULL DEFAULT 'Administrator' AFTER `user_role`;

-- --------------------------------------------------------
-- Redesigned Sales table for product sales
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sale_items`;
DROP TABLE IF EXISTS `product_sales`;

CREATE TABLE `product_sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_reference` varchar(30) NOT NULL,
  `sale_customer_name` varchar(100) DEFAULT 'Walk-in',
  `sale_total` float NOT NULL DEFAULT '0',
  `sale_discount` float NOT NULL DEFAULT '0',
  `sale_amount_paid` float NOT NULL DEFAULT '0',
  `sale_change` float NOT NULL DEFAULT '0',
  `sale_payment_method` ENUM('Cash','GCash','Bank Transfer') NOT NULL DEFAULT 'Cash',
  `sale_notes` varchar(255) DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `sale_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `sale_reference` (`sale_reference`),
  KEY `processed_by` (`processed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` float NOT NULL,
  `unit_price` float NOT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `fk_sale_items_sale` FOREIGN KEY (`sale_id`) REFERENCES `product_sales` (`sale_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sale_items_item` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Update default admin user with role and fullname
UPDATE `user` SET `user_role` = 'admin', `user_fullname` = 'Administrator' WHERE `user_id` = 1;
