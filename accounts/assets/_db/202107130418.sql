ALTER TABLE `transactions` ADD `relation_id` INT UNSIGNED NULL AFTER `user_id`;
ALTER TABLE `journals` ADD `relation_id` INT UNSIGNED NULL AFTER `user_id`;
ALTER TABLE `users` ADD UNIQUE `idx_user_name` (`user_name`(255));