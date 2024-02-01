ALTER TABLE `accounts` ADD `cuurency_id` INT(10) UNSIGNED NULL DEFAULT 1 AFTER `id`;
ALTER TABLE `accounts` ADD INDEX `idx_currency_id` (`currency_id`);
ALTER TABLE `accounts` CHANGE `currency_id` `cuurency_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `accounts` ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `transaction_items` ADD `net_cost` DOUBLE UNSIGNED NOT NULL AFTER `discount`;


ALTER TABLE `items` ADD COLUMN `cost` DOUBLE UNSIGNED NOT NULL AFTER `category`;
ALTER TABLE `journals` 
DROP COLUMN `total`,
DROP COLUMN `trans_date`,
DROP COLUMN `trans_type`,
ADD COLUMN `journal_type` VARCHAR(2) NOT NULL AFTER `transaction_id`,
ADD COLUMN `journal_date` DATE NOT NULL AFTER `account2_id`,
ADD COLUMN `amount` DOUBLE UNSIGNED NOT NULL AFTER `currency_rate`,
CHANGE COLUMN `value_date` `value_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `description` `description` VARCHAR(255) NULL DEFAULT NULL ,
ADD INDEX `journal_type` (`journal_type` ASC, `auto_no` ASC, `account_id` ASC, `account2_id` ASC, `currency_id` ASC, `user_id` ASC),
ADD INDEX `fiscal_year_id` (`fiscal_year_id` ASC);