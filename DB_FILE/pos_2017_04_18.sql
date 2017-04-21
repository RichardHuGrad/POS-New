ALTER TABLE `cooks`
  ADD COLUMN `userid` varchar(4) NOT NULL DEFAULT '' AFTER `id`;

ALTER TABLE `cooks`
  ADD COLUMN `position` enum('K','S') NOT NULL DEFAULT 'K' COMMENT 'K-kitchen, S-service' AFTER `lastname`;

ALTER TABLE `apis`
  ADD COLUMN `cashier_id` int(11) NOT NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `apis`
  ADD COLUMN `restaurant_id` int(11) NOT NULL DEFAULT 0 AFTER `id`;

