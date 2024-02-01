CREATE TABLE `user_permissions` ( `user_type` VARCHAR(255) NOT NULL , `permission` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `user_permissions` ADD `id` INT NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `transactions` ADD `driver_id` INT UNSIGNED NULL AFTER `user_id`;
ALTER TABLE `transactions` ADD `delivered` TINYINT UNSIGNED NULL AFTER `status`;