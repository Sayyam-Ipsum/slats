ALTER TABLE `items` ADD `item_range` VARCHAR(255) NULL AFTER `qty`, ADD `OE_nb` VARCHAR(255) NULL AFTER `item_range`, ADD `weight` DOUBLE UNSIGNED NULL AFTER `OE_nb`;