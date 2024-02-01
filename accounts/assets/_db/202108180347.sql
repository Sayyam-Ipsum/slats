ALTER TABLE `transactions` ADD `employee_id` INT UNSIGNED NULL AFTER `driver_id`;
ALTER TABLE `transactions` ADD `pickup` TINYINT UNSIGNED NULL AFTER `delivered`;