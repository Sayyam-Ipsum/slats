ALTER TABLE `transactions` ADD `VIN` VARCHAR(255) NULL AFTER `description`, ADD `model` VARCHAR(255) NULL AFTER `VIN`;