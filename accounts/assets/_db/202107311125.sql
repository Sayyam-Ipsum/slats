ALTER TABLE `accounts` CHANGE `debit` `debit` DOUBLE UNSIGNED NULL DEFAULT '0', 
CHANGE `credit` `credit` DOUBLE UNSIGNED NULL DEFAULT '0', 
CHANGE `balance` `balance` DOUBLE NULL DEFAULT '0', 
CHANGE `open_balance` `open_balance` DOUBLE NULL DEFAULT '0';