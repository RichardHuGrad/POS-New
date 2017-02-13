SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `status` enum('A', 'I') DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `printer` enum('C','K') DEFAULT 'K' COMMENT 'C-Cashier, K-kitchen',
  `is_synced` enum('Y', 'N') DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;


CREATE TABLE `category_locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE `cousines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `comb_num` int(11) NOT NULL DEFAULT 0,
  -- `image` varchar(100) DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `is_tax` enum('Y','N') DEFAULT 'Y' COMMENT 'Y=Yes, N=No',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `is_synced` enum('Y', 'N') DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE `cousine_locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cousine_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) NOT NULL,
  `restaurant_order_id` int(11) NOT NULL,
  `order_no` varchar(10) NOT NULL DEFAULT '0',
  `table_no` int(10) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0,
  `subtotal` decimal(10,2) DEFAULT 0,
  `total` decimal(10,2) DEFAULT 0,
  `card_val` decimal(10,2) DEFAULT 0,
  `cash_val` decimal(10,2) DEFAULT 0,
  `tip` decimal(10,2) DEFAULT 0,
  `tip_paid_by` enum('CARD','CASH','MIXED','NO TIP') DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT 0,
  `change` decimal(10,2) DEFAULT 0,
  `promocode` varchar(100) DEFAULT NULL,
  `message` text,
  `reason` text,
  `order_type` enum('D','T','W') DEFAULT NULL COMMENT 'D-Dinein, T-takeway, W-waiting',
  `is_completed` enum('Y','N') DEFAULT 'N',
  `paid_by` enum('CARD','CASH','MIXED') DEFAULT NULL,
  `fix_discount` decimal(10,2) DEFAULT 0,
  `percent_discount` decimal(10,2) DEFAULT 0,
  `discount_value` decimal(10,2) DEFAULT 0,
  `after_discount` decimal(10,2) DEFAULT 0,
  `merge_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;


CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(255) DEFAULT NULL,
  `name_zh` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;


CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
