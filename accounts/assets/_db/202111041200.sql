ALTER TABLE `items`
  DROP `TVA`,
  DROP `price_ttc`;

ALTER TABLE `transactions` ADD `TVA` DOUBLE UNSIGNED NULL AFTER `discount`;

