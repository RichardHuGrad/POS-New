ALTER TABLE `orders`
  CHANGE COLUMN `order_no` `order_no` varchar(15) NOT NULL DEFAULT '0';

ALTER TABLE `promocodes`
  ADD COLUMN `week_days` varchar(100) NULL DEFAULT NULL AFTER `valid_to`;

ALTER TABLE `promocodes`
  ADD COLUMN `start_time` time NULL DEFAULT NULL AFTER `week_days`;

ALTER TABLE `promocodes`
  ADD COLUMN `end_time` time NULL DEFAULT NULL AFTER `start_time`;
  
CREATE TABLE `attendances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(10) NOT NULL DEFAULT '',
  `day` date DEFAULT NULL,
  `checkin` time DEFAULT NULL,
  `checkout` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
