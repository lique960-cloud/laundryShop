-- Customer Portal Tables for Laundry System
-- Run this in phpMyAdmin on your `laundry_system` database

-- Customers table
CREATE TABLE IF NOT EXISTS `customers` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_fullname` varchar(100) NOT NULL,
  `cust_email` varchar(100) NOT NULL,
  `cust_mobile` varchar(20) NOT NULL,
  `cust_address` text NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Password reset tokens
CREATE TABLE IF NOT EXISTS `password_resets` (
  `reset_id` int(11) NOT NULL AUTO_INCREMENT,
  `reset_email` varchar(100) NOT NULL,
  `reset_token` varchar(64) NOT NULL,
  `reset_expires` datetime NOT NULL,
  `reset_used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- If you already have the customers table, run this to add the address column:
-- ALTER TABLE `customers` ADD COLUMN `cust_address` text NOT NULL DEFAULT '' AFTER `cust_mobile`;
