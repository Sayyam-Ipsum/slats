ALTER TABLE `transactions` ADD `payment_method` VARCHAR(255) NULL AFTER `tracking_number`;
ALTER TABLE `transactions` ADD `transaction_number` VARCHAR(255) NULL AFTER `payment_method`;