CREATE TABLE `items_ean` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `item_id` INT UNSIGNED NOT NULL , `ean` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `items_ean` ADD CONSTRAINT `idx_items_ean1` FOREIGN KEY (`item_id`) REFERENCES `items`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;