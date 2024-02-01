SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_number` int(10) UNSIGNED NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` varchar(255) DEFAULT NULL,
  `opening_date` date NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `debit` double UNSIGNED DEFAULT NULL,
  `credit` double UNSIGNED DEFAULT NULL,
  `balance` double UNSIGNED DEFAULT NULL,
  `open_balance` double UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_account_number` (`account_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `valueInt` double DEFAULT NULL,
  `valueStr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_key_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `currency_rate` double UNSIGNED NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_curr_code` (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `fiscal_years`;
CREATE TABLE IF NOT EXISTS `fiscal_years` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `barcode` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `open_cost` double UNSIGNED NOT NULL,
  `open_qty` double UNSIGNED NOT NULL,
  `price` double UNSIGNED NOT NULL,
  `qty` double UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `journals`;
CREATE TABLE IF NOT EXISTS `journals` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fiscal_year_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `trans_type` varchar(2) NOT NULL,
  `auto_no` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `account2_id` int(10) UNSIGNED NOT NULL,
  `trans_date` date NOT NULL,
  `value_date` date NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `currency_rate` double UNSIGNED NOT NULL,
  `total` double UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fiscal_year_id` int(10) UNSIGNED NOT NULL,
  `trans_type` varchar(2) NOT NULL,
  `auto_no` int(10) UNSIGNED NOT NULL,
  `trans_date` date NOT NULL,
  `value_date` date DEFAULT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `account2_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `currency_rate` double UNSIGNED NOT NULL,
  `discount` double UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_trans_no` (`trans_type`,`auto_no`),
  KEY `idx_fiscal_year_id` (`fiscal_year_id`),
  KEY `idx_account_id` (`account_id`),
  KEY `idx_account2_id` (`account2_id`),
  KEY `idx_currency_id` (`currency_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transaction_items`;
CREATE TABLE IF NOT EXISTS `transaction_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `qty` double UNSIGNED NOT NULL,
  `mvt_type` tinyint(4) NOT NULL,
  `cost` double UNSIGNED NOT NULL,
  `price` double UNSIGNED NOT NULL,
  `discount` double UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transaction_id` (`transaction_id`),
  KEY `idx_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fiscal_year_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fiscal_year_id` (`fiscal_year_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`account2_id`) REFERENCES `accounts` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;