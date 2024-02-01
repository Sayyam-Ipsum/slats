ALTER TABLE `journal_accounts`
 ADD CONSTRAINT `journal_accounts_ibfk_1` FOREIGN KEY (`journal_id`)
  REFERENCES `journals`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `journal_accounts_ibfk_2` FOREIGN KEY (`account_id`)
   REFERENCES `accounts`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `journals`
 CHANGE `account_id` `account_id` INT(10) UNSIGNED NULL DEFAULT NULL,
 CHANGE `account2_id` `account2_id` INT(10) UNSIGNED NULL DEFAULT NULL;


ALTER TABLE `journals`
 DROP INDEX `journal_type`,
 ADD UNIQUE `unq_journal_auto_no` (`fiscal_year_id`, `journal_type`, `auto_no`) USING BTREE;

ALTER TABLE `journals`
 ADD CONSTRAINT `journals_ibfk_1` FOREIGN KEY (`fiscal_year_id`)
  REFERENCES `fiscal_years`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `journals_ibfk_2` FOREIGN KEY (`transaction_id`)
   REFERENCES `transactions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `journal_accounts` CHANGE `value_date` `value_date` DATE NULL DEFAULT NULL;