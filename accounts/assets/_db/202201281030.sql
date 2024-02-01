ALTER TABLE `transactions` 
ADD `locked` TINYINT UNSIGNED NULL DEFAULT '0' AFTER `relation_id`, 
ADD `edit_user_id` INT UNSIGNED NULL AFTER `locked`;