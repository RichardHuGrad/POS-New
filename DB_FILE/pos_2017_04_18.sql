ALTER TABLE `cooks`
  ADD COLUMN `userid` varchar(4) NOT NULL DEFAULT '' AFTER `id`;

ALTER TABLE `cooks`
  ADD COLUMN `position` enum('K','S') NOT NULL DEFAULT 'K' COMMENT 'K-kitchen, S-service' AFTER `lastname`;


