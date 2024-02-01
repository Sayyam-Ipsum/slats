ALTER TABLE `transactions` ADD `OE` VARCHAR(255) NULL AFTER `model`;
ALTER TABLE `transactions` ADD `delivery_charge` DOUBLE UNSIGNED NULL DEFAULT '0' AFTER `discount`;
ALTER TABLE `transactions` ADD `user2_id` INT UNSIGNED NULL AFTER `user_id`;
ALTER TABLE `transactions` ADD CONSTRAINT `transactions_ibfk_6` FOREIGN KEY (`user2_id`) REFERENCES `users`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE;