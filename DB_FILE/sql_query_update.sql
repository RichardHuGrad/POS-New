-- DATE 25-11-2016 ---------------------------------

ALTER TABLE `cashiers` ADD COLUMN `ipad_deviceid` varchar(100) default '', -- column added to save ipad deviceid for tracking
ADD COLUMN `ipad_devicetoken` varchar(100) default '', -- column added to save ipad devicetoken, if need to send push notification
ADD COLUMN `logintype` ENUM('W','M','NA') default 'NA' comment "W-> Web, M-> Mobile, NA-> Not Applicable"; -- For define cashier loggedin from ipad or webpanel

-- table added to save reservation data
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

ALTER TABLE `orders` ADD COLUMN `reservation_id` int(11) default '0'; -- to reserve the table

-- DATE 28-11-2016 ----------------------------------------------------------
ALTER TABLE `cooks` ADD COLUMN `image` VARCHAR(100) NULL AFTER `is_verified`;  -- This columns we had added previously but you did not inluded in your code

ALTER TABLE `orders` ADD COLUMN `name` VARCHAR(100) NULL AFTER `reservation_id`, -- added for takeout + waiting orders 
ADD COLUMN `noofperson` int(3) NULL, ADD COLUMN `phoneno` VARCHAR(20) NULL,  -- added for takeout + waiting orders 
ADD COLUMN `takeout_date` date NULL, ADD COLUMN `takeout_time` time NULL; -- added for takeout date + time

-- These columns we had added previously but you did not inluded in your code ----------------------------------------------------------
ALTER TABLE pos.promocodes ADD COLUMN `start_time` time null AFTER `valid_to`,
ADD COLUMN `end_time` time null AFTER `start_time`,
ADD COLUMN `week_days` varchar(200) null AFTER `end_time`,
ADD COLUMN `category_id` int(10) DEFAULT '0' AFTER `restaurant_id`,
ADD COLUMN `item_id`  int(10) DEFAULT '0' AFTER `category_id`; 

-- DATE 30-11-2016 ----------------------------------------------------------
ALTER table pos.extras 
ADD COLUMN `type` ENUM('T','E')  DEFAULT 'E' COMMENT 'T => Topping, E => Extra' after `id`; -- for saving the type of extras

-- DATE 01-12-2016 ----------------------------------------------------------
ALTER TABLE `order_items` ADD COLUMN `discount` FLOAT DEFAULT '0' AFTER `tax_amount`; -- for save the discount on perticuler item

-- DATE 06-12-2016 -----------------------------------------------------------
ALTER TABLE `order_items` ADD COLUMN `special_instructions` ENUM('', 'NO','MORE','LESS')  DEFAULT '' after `extras_amount`; -- for save the special_instructions on perticuler item

-- DATE 07-12-2016 -----------------------------------------------------------
ALTER TABLE `orders` ADD COLUMN `manager_id` INT(10)  DEFAULT 0 after `reservation_id`; -- for save manager id who has set available the table

-- DATE 08-12-2016 -----------------------------------------------------------
ALTER TABLE `order_items` ADD COLUMN `actual_unit_price` FLOAT DEFAULT 0 after `category_id`,
ADD COLUMN `order_unit_price` FLOAT DEFAULT 0 after `actual_unit_price`, -- for saving actual price and changed price
ADD COLUMN `delivery_type` ENUM('', 'T', 'D', 'W')  DEFAULT '' COMMENT 'T => Take out, D => DineIn, W->Waiting' after `discount`; -- for savig order type item wise


ALTER TABLE `order_items` ADD COLUMN `price_changed_by` INT(10) DEFAULT 0 after `is_kitchen`; -- for trackinh who has chnaged the price

