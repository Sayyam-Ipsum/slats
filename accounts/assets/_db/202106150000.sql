ALTER TABLE `accounts` CHANGE `open_balance` `open_balance` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `accounts` CHANGE `balance` `balance` DOUBLE NULL DEFAULT NULL;

CREATE TABLE `journal_accounts` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `journal_id` INT UNSIGNED NOT NULL , `account_id` INT UNSIGNED NOT NULL , `description` VARCHAR(255) NULL , `mvt_type` TINYINT(4) NOT NULL , `amount` DOUBLE UNSIGNED NOT NULL , `value_date` DATE NOT NULL , PRIMARY KEY (`id`), INDEX `idx_journal_id` (`journal_id`), INDEX `idx_account_id` (`account_id`)) ENGINE = InnoDB;