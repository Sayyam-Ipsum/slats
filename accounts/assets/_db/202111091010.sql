ALTER TABLE `items`
  DROP `profit`,
  DROP `price`;

ALTER TABLE `transaction_items` ADD `item_profit` DOUBLE UNSIGNED NOT NULL AFTER `cost`;