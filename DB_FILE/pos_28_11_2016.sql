-- DATE 25-11-2016 ---------------------------------

ALTER TABLE `cashiers` ADD COLUMN `ipad_deviceid` varchar(100) default '',
ADD COLUMN `ipad_devicetoken` varchar(100) default '',
ADD COLUMN `logintype` ENUM('W','M','NA') default 'NA' comment "W-> Web, M-> Mobile, NA-> Not Applicable";

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cashier_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `noofperson` int(3) NOT NULL DEFAULT '0',
  `phoneno` varchar(20) NOT NULL,
  `reserve_date` date NOT NULL,
  `reserve_time` time NOT NULL,
  `required` varchar(50) DEFAULT NULL,
  `status` ENUM('P', 'C', 'A') NOT NULL DEFAULT 'P' comment "P -> Pending, A -> Assigned, C-> Cancelled",
  `cancelledby` int(11) unsigned NOT NULL DEFAULT '0',
  `reservedby` int(11) unsigned NOT NULL DEFAULT '0',
  `cancelledreason` varchar(500) DEFAULT NULL,
  `cancelledat` datetime DEFAULT NULL,
  `reservedat` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `orders` ADD COLUMN `reservation_id` int(11) default '0';

-- DATE 28-11-2016 -----------------------------------------------------
ALTER TABLE `cooks` ADD COLUMN `image` VARCHAR(100) NULL AFTER `is_verified`;

ALTER TABLE `orders` ADD COLUMN `name` VARCHAR(100) NULL AFTER `reservation_id`, 
ADD COLUMN `noofperson` int(3) NULL, ADD COLUMN `phoneno` VARCHAR(20) NULL, 
ADD COLUMN `takeout_date` date NULL, ADD COLUMN `takeout_time` time NULL;
