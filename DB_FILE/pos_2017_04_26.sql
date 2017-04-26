ALTER TABLE `cashiers`
  ADD COLUMN `userid` varchar(3) NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `cashiers` ADD CONSTRAINT uc_userid UNIQUE (userid);
  
ALTER TABLE `cashiers`
  ADD COLUMN `position` enum('K','S') NOT NULL DEFAULT 'S' COMMENT 'K-kitchen, S-service';
  

