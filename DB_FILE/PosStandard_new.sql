-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2018 at 11:55 AM
-- Server version: 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.0.31-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(10) NOT NULL,
  `created_by_id` int(10) DEFAULT NULL COMMENT 'admin id who created this admin',
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_super_admin` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=Super Admin, N=Sub Admin(Normal admin)',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('A','I') NOT NULL COMMENT 'A-active, I-inactive',
  `mobile_no` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `hst_number` varchar(50) NOT NULL,
  `print_offset` varchar(100) NOT NULL COMMENT 'number seperated by comma',
  `restaurant_name` varchar(100) NOT NULL,
  `tax` int(10) NOT NULL,
  `default_tip_rate` int(11) NOT NULL DEFAULT '0',
  `no_of_tables` int(10) NOT NULL DEFAULT '0',
  `no_of_takeout_tables` int(10) DEFAULT NULL,
  `no_of_waiting_tables` int(10) DEFAULT NULL,
  `no_of_online_tables` int(11) DEFAULT NULL,
  `table_size` mediumtext COMMENT 'table per size',
  `table_order` mediumtext,
  `takeout_table_size` mediumtext,
  `waiting_table_size` mediumtext,
  `is_verified` enum('Y','N') NOT NULL COMMENT 'Y-yes, N-no',
  `printer_ip` varchar(50) DEFAULT '192.168.192.168',
  `kitchen_printer_device` varchar(50) DEFAULT NULL,
  `service_printer_device` varchar(50) DEFAULT NULL,
  `logo_path` varchar(100) DEFAULT '../webroot/img/logo.bmp',
  `oc_store_id` int(11) DEFAULT NULL,
  `oc_api_url` varchar(255) DEFAULT NULL,
  `oc_api_key` varchar(1000) DEFAULT NULL,
  `oc_last_push_order_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `created_by_id`, `firstname`, `lastname`, `email`, `password`, `is_super_admin`, `created`, `modified`, `status`, `mobile_no`, `address`, `city`, `province`, `zipcode`, `hst_number`, `print_offset`, `restaurant_name`, `tax`, `default_tip_rate`, `no_of_tables`, `no_of_takeout_tables`, `no_of_waiting_tables`, `no_of_online_tables`, `table_size`, `table_order`, `takeout_table_size`, `waiting_table_size`, `is_verified`, `printer_ip`, `kitchen_printer_device`, `service_printer_device`, `logo_path`, `oc_store_id`, `oc_api_url`, `oc_api_key`, `oc_last_push_order_time`) VALUES(1, 0, 'POS', 'Jeff', 'admin', '96e79218965eb72c92a549dd5a330112', 'Y', '0000-00-00 00:00:00', '2017-10-19 20:10:51', 'A', NULL, '', '', '', '', '', '', '', 0, 0, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, 'Y', NULL, NULL, NULL, '../webroot/img/logo.bmp', NULL, 'http://posonline.auroraeducationonline.info/index.php?route=api', 'RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno', NULL);
INSERT INTO `admins` (`id`, `created_by_id`, `firstname`, `lastname`, `email`, `password`, `is_super_admin`, `created`, `modified`, `status`, `mobile_no`, `address`, `city`, `province`, `zipcode`, `hst_number`, `print_offset`, `restaurant_name`, `tax`, `default_tip_rate`, `no_of_tables`, `no_of_takeout_tables`, `no_of_waiting_tables`, `no_of_online_tables`, `table_size`, `table_order`, `takeout_table_size`, `waiting_table_size`, `is_verified`, `printer_ip`, `kitchen_printer_device`, `service_printer_device`, `logo_path`, `oc_store_id`, `oc_api_url`, `oc_api_key`, `oc_last_push_order_time`) VALUES(5, 1, 'restaurant', 'panel', 'restaurant@pos_v1.com', 'e10adc3949ba59abbe56e057f20f883e', 'N', '2016-06-30 08:31:12', '2018-08-08 09:53:20', 'A', '647-352-5333', '108-3700 Midland Ave', 'Scarborogh', 'ON', 'M1V 0B3', '798703096 RT0001', '90,130,140', 'HeyNoodle', 13, 10, 19, 9, 9, 9, '1,3,5,7,9,11,2,4,6,8,10,12,1,1,1,11,1,1', '["position: absolute; left: 0%; top: 0%;","position: absolute; left: 0%; top: 20.7324%;","position: absolute; left: 13.4316%; top: 0%;","position: absolute; left: 13.1947%; top: 20.0521%;","position: absolute; left: 29.6077%; top: 0%;","position: absolute; left: 29.3708%; top: 18.3333%;","position: absolute; left: 53.4306%; top: 0%;","position: absolute; left: 53.1005%; top: 21.4583%;","position: absolute; left: 70.8722%; top: 0%;","position: absolute; left: 70.8832%; top: 20.3255%;","position: absolute; left: 0%; top: 51.3574%;","position: absolute; left: 14.4909%; top: 52.0801%;","position: absolute; left: 29.6139%; top: 51.875%;","position: absolute; left: 42.6122%; top: 51.875%;","position: absolute; left: 0%; top: 77.5%;","position: absolute; left: 14.481%; top: 77.0833%;","position: absolute; left: 29.2861%; top: 76.6667%;","position: absolute; left: 42.4403%; top: 76.25%;"]', '1,2,5,7,8,9,5,4,2', '8,5,6,8,2,2,2,3,2', 'Y', '192.168.192.168', 'kitchen', 'front', '../webroot/img/logo.bmp', 0, 'http://127.0.0.1:8080/posonline/index.php?route=api', 'RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_privilages`
--

DROP TABLE IF EXISTS `admin_privilages`;
CREATE TABLE `admin_privilages` (
  `id` int(10) NOT NULL,
  `admin_id` int(10) NOT NULL DEFAULT '0',
  `module` varchar(100) NOT NULL COMMENT 'Name of section to apply rule',
  `can_view` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  `can_add` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  `can_edit` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  `can_delete` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=Yes, N=No',
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apis`
--

DROP TABLE IF EXISTS `apis`;
CREATE TABLE `apis` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL DEFAULT '0',
  `cashier_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apis`
--

INSERT INTO `apis` (`id`, `restaurant_id`, `cashier_id`, `email`, `password`, `token`, `ip`, `created`, `modified`) VALUES(1, 5, 3, 'cashier@pos_v1.com', 'e10adc3949ba59abbe56e057f20f883e', '736983118b022063c6708629b4a9b8a7', NULL, '2017-05-29 10:05:53', '2017-05-29 10:05:53');
INSERT INTO `apis` (`id`, `restaurant_id`, `cashier_id`, `email`, `password`, `token`, `ip`, `created`, `modified`) VALUES(2, 5, 4, '102@pos.com', 'e10adc3949ba59abbe56e057f20f883e', 'b460c35dcd5f3463b5434153ad95b82c', NULL, '2017-07-10 15:54:10', '2017-07-10 15:54:10');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `userid` varchar(10) NOT NULL DEFAULT '',
  `checkin` datetime DEFAULT NULL,
  `checkout` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

DROP TABLE IF EXISTS `cashiers`;
CREATE TABLE `cashiers` (
  `id` int(10) NOT NULL,
  `userid` varchar(3) DEFAULT NULL,
  `restaurant_id` int(10) NOT NULL DEFAULT '0',
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `is_verified` enum('Y','N') NOT NULL COMMENT 'Y-yes, N-no',
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A-active, I-inactive',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `printer_ip` varchar(50) DEFAULT NULL,
  `printer_device_id` varchar(50) DEFAULT NULL,
  `position` enum('K','S') NOT NULL DEFAULT 'S' COMMENT 'K-kitchen, S-service'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `userid`, `restaurant_id`, `firstname`, `lastname`, `mobile_no`, `email`, `password`, `image`, `is_verified`, `status`, `created`, `modified`, `printer_ip`, `printer_device_id`, `position`) VALUES(3, '101', 5, 'bhawani', 'shankar', '7023311807', 'cashier@pos_v1.com', 'e10adc3949ba59abbe56e057f20f883e', '1467436684_Cashier.jpg', 'Y', 'A', '2016-06-30 08:49:53', '2017-04-30 07:37:14', NULL, NULL, 'S');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `orderby` int(11) NOT NULL,
  `printer` enum('C','K') DEFAULT 'K' COMMENT 'C-Cashier, K-kitchen',
  `group_id` smallint(5) UNSIGNED DEFAULT '1',
  `remote_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category_groups`
--

DROP TABLE IF EXISTS `category_groups`;
CREATE TABLE `category_groups` (
  `id` smallint(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category_locales`
--

DROP TABLE IF EXISTS `category_locales`;
CREATE TABLE `category_locales` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cookies`
--

DROP TABLE IF EXISTS `cookies`;
CREATE TABLE `cookies` (
  `id` int(10) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL,
  `validate_days` int(10) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cooks`
--

DROP TABLE IF EXISTS `cooks`;
CREATE TABLE `cooks` (
  `id` int(10) NOT NULL,
  `userid` varchar(4) NOT NULL DEFAULT '',
  `restaurant_id` int(10) NOT NULL DEFAULT '0',
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `position` enum('K','S') NOT NULL DEFAULT 'K' COMMENT 'K-kitchen, S-service',
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_verified` enum('Y','N') NOT NULL COMMENT 'Y-yes, N-no',
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A-active, I-inactive',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cousines`
--

DROP TABLE IF EXISTS `cousines`;
CREATE TABLE `cousines` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL DEFAULT '0',
  `casier_id` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `comb_num` int(11) NOT NULL DEFAULT '0',
  `image` varchar(100) DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` int(11) NOT NULL,
  `popular` bigint(20) NOT NULL DEFAULT '0',
  `is_tax` enum('Y','N') DEFAULT 'Y',
  `modified` int(11) NOT NULL,
  `remote_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `cousine_extrascategories`
--

DROP TABLE IF EXISTS `cousine_extrascategories`;
CREATE TABLE `cousine_extrascategories` (
  `id` int(11) NOT NULL,
  `cousine_id` int(11) NOT NULL,
  `extrascategorie_id` int(11) NOT NULL,
  `remote_id` int(11) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cousine_locals`
--

DROP TABLE IF EXISTS `cousine_locals`;
CREATE TABLE `cousine_locals` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `extras`
--

DROP TABLE IF EXISTS `extras`;
CREATE TABLE `extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `cousine_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_zh` varchar(100) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `status` enum('A','I') DEFAULT 'A',
  `category_id` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `extrascategories`
--

DROP TABLE IF EXISTS `extrascategories`;
CREATE TABLE `extrascategories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `name_zh` varchar(50) DEFAULT NULL,
  `extras_num` int(11) NOT NULL DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `remote_id` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
CREATE TABLE `global_settings` (
  `id` int(11) NOT NULL,
  `delivery_charge` float NOT NULL,
  `from_email` varchar(255) NOT NULL COMMENT 'email id to show in send from email',
  `to_email` varchar(255) NOT NULL COMMENT 'email id to send to the admin',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `delivery_charge`, `from_email`, `to_email`, `created`, `modified`) VALUES(1, 0, 'info@auroratd.com', 'info@auroratd.com', '2018-05-02 11:08:57', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language` varchar(100) NOT NULL,
  `lang_code` char(2) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language`, `lang_code`, `status`) VALUES(1, 'English', 'en', 'A');
INSERT INTO `languages` (`id`, `language`, `lang_code`, `status`) VALUES(2, 'Chinese', 'zh', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `cashier_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `operation` varchar(50) NOT NULL,
  `logs` mediumtext NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `cardnumber` varchar(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` text NOT NULL,
  `created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_trans`
--

DROP TABLE IF EXISTS `member_trans`;
CREATE TABLE `member_trans` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `opt` varchar(16) NOT NULL,
  `amount` float NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_number` varchar(64) NOT NULL,
  `bill_amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) NOT NULL,
  `order_no` varchar(15) NOT NULL DEFAULT '0',
  `reorder_no` varchar(15) DEFAULT '0',
  `hide_no` bigint(20) DEFAULT '0',
  `cashier_id` int(10) DEFAULT NULL COMMENT 'stand for restaurant_id',
  `counter_id` int(10) DEFAULT NULL COMMENT 'stand for cashier',
  `table_no` int(10) DEFAULT NULL,
  `table_status` enum('P','N','A','V','R') DEFAULT 'N' COMMENT 'P-paid, N-not paid, A-available, V-Void, R-Receipt Printed',
  `tax` int(11) DEFAULT NULL,
  `tax_amount` decimal(8,2) DEFAULT '0.00',
  `default_tip_rate` int(10) DEFAULT NULL,
  `default_tip_amount` decimal(10,2) DEFAULT NULL,
  `subtotal` float DEFAULT '0',
  `total` decimal(10,2) DEFAULT '0.00',
  `card_val` float DEFAULT '0',
  `membercard_id` float NOT NULL,
  `membercard_val` float NOT NULL,
  `cash_val` float DEFAULT '0',
  `tip` float DEFAULT '0',
  `tip_paid_by` enum('CARD','CASH','MIXED','NO TIP','MEMBERCARD') DEFAULT NULL,
  `paid` float DEFAULT '0',
  `change` float DEFAULT '0',
  `promocode` varchar(100) DEFAULT NULL,
  `message` mediumtext,
  `reason` mediumtext,
  `order_type` enum('D','T','W','L') DEFAULT NULL COMMENT 'D-Dinein, T-takeway, W-waiting, L-Online',
  `is_kitchen` enum('Y','N') DEFAULT 'N',
  `cooking_status` enum('COOKED','UNCOOKED') DEFAULT 'UNCOOKED',
  `is_hide` enum('Y','N','P') DEFAULT 'P' COMMENT 'P-Pending',
  `created` datetime DEFAULT NULL,
  `is_completed` enum('Y','N') DEFAULT 'N',
  `paid_by` enum('CARD','CASH','MIXED','MEMBERCARD') DEFAULT NULL,
  `fix_discount` float DEFAULT '0',
  `percent_discount` float DEFAULT '0',
  `discount_value` float(8,2) DEFAULT '0.00',
  `merge_id` int(11) NOT NULL DEFAULT '0',
  `after_discount` float DEFAULT '0',
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) NOT NULL,
  `order_id` int(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `name_xh` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(25,2) DEFAULT NULL,
  `qty` int(10) NOT NULL DEFAULT '1',
  `tax` float NOT NULL DEFAULT '0',
  `tax_amount` float DEFAULT '0',
  `selected_extras` mediumtext,
  `all_extras` mediumtext,
  `extras_amount` float DEFAULT '0',
  `is_done` enum('Y','N') NOT NULL DEFAULT 'N',
  `created` datetime DEFAULT NULL,
  `is_print` enum('N','Y') DEFAULT 'N',
  `is_kitchen` enum('N','Y') DEFAULT 'N',
  `is_takeout` enum('N','Y') NOT NULL DEFAULT 'N',
  `comb_id` int(11) DEFAULT '0',
  `special_instruction` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `order_logs`
--

DROP TABLE IF EXISTS `order_logs`;
CREATE TABLE `order_logs` (
  `id` int(10) NOT NULL,
  `order_no` varchar(15) NOT NULL DEFAULT '0',
  `json` mediumtext NOT NULL,
  `operation` mediumtext NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_splits`
--

DROP TABLE IF EXISTS `order_splits`;
CREATE TABLE `order_splits` (
  `id` int(10) NOT NULL,
  `table_no` int(10) DEFAULT NULL,
  `order_no` varchar(15) NOT NULL DEFAULT '0',
  `suborder_no` varchar(10) NOT NULL DEFAULT '0',
  `subtotal` float DEFAULT NULL,
  `discount_type` enum('UNKNOWN','FIXED','PERCENT') DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `default_tip_rate` float DEFAULT NULL,
  `default_tip_amount` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `paid_card` float DEFAULT NULL,
  `membercard_id` float NOT NULL,
  `paid_membercard` float NOT NULL,
  `paid_cash` float DEFAULT NULL,
  `tip_card` float DEFAULT NULL,
  `tip_membercard` float NOT NULL,
  `tip_cash` float DEFAULT NULL,
  `change` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `items` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(105) NOT NULL,
  `body` longtext NOT NULL,
  `slug` varchar(105) NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `body`, `slug`, `status`, `created`, `modified`) VALUES(1, 'About Us', '<span style="color:#800080"><span style="font-size:24px"><u><strong>uterm</strong></u></span></span><br />\r\n<br />\r\nThis page is all about uterm.<br />\r\nUterm is the mobile application for customer and vendor.<br />\r\n<br />\r\nOnline order for wine made by customer to nearest wine dealer.<br />\r\n<br />\r\n<strong>User finds the nearest dealer using application.</strong><br />\r\n<br />\r\n<br />\r\nand many more....<br />\r\n<br />\r\n&nbsp;', 'about-us', 'A', '2016-04-30 10:18:25', '2016-04-30 10:27:45');
INSERT INTO `pages` (`id`, `name`, `body`, `slug`, `status`, `created`, `modified`) VALUES(2, 'Support', '<span style="color:#FF0000"><span style="font-size:16px">This section is under construction !</span></span>', 'support', 'I', '2016-04-30 10:36:46', '2016-04-30 10:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
CREATE TABLE `printers` (
  `id` int(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `printer_ID` char(10) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `num` int(10) DEFAULT NULL,
  `admin_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `printers`
--

INSERT INTO `printers` (`id`, `name`, `printer_ID`, `type`, `num`, `admin_id`) VALUES(1, 'GP-80160(Cut) Series', '1', 'C', 1, 5);
INSERT INTO `printers` (`id`, `name`, `printer_ID`, `type`, `num`, `admin_id`) VALUES(2, 'XP-80Cc', '2', 'K', 2, 5);
INSERT INTO `printers` (`id`, `name`, `printer_ID`, `type`, `num`, `admin_id`) VALUES(6, 'GP-80160(Cut) Series', '3', 'K', 3, 5);
INSERT INTO `printers` (`id`, `name`, `printer_ID`, `type`, `num`, `admin_id`) VALUES(7, 'XP-80Cc1', '4', 'K', 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

DROP TABLE IF EXISTS `promocodes`;
CREATE TABLE `promocodes` (
  `id` int(11) UNSIGNED NOT NULL,
  `restaurant_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '0',
  `valid_from` varchar(255) NOT NULL DEFAULT '0',
  `valid_to` varchar(255) NOT NULL DEFAULT '0',
  `week_days` varchar(100) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `discount_type` tinyint(8) NOT NULL DEFAULT '0',
  `discount_value` varchar(50) NOT NULL DEFAULT '0',
  `is_multiple` tinyint(8) NOT NULL DEFAULT '0',
  `status` tinyint(8) NOT NULL DEFAULT '1',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`created_by_id`);

--
-- Indexes for table `admin_privilages`
--
ALTER TABLE `admin_privilages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK1` (`admin_id`);

--
-- Indexes for table `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_userid` (`userid`),
  ADD KEY `FK_cashiers_admins` (`restaurant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_groups`
--
ALTER TABLE `category_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_locales`
--
ALTER TABLE `category_locales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookies`
--
ALTER TABLE `cookies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cooks`
--
ALTER TABLE `cooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cousines`
--
ALTER TABLE `cousines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cousine_extrascategories`
--
ALTER TABLE `cousine_extrascategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cousine_locals`
--
ALTER TABLE `cousine_locals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_extras_cousines` (`cousine_id`);

--
-- Indexes for table `extrascategories`
--
ALTER TABLE `extrascategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lang_code` (`lang_code`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card` (`cardnumber`);

--
-- Indexes for table `member_trans`
--
ALTER TABLE `member_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_orders_items_orders` (`order_id`);

--
-- Indexes for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_splits`
--
ALTER TABLE `order_splits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `printers`
--
ALTER TABLE `printers`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `admin_privilages`
--
ALTER TABLE `admin_privilages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apis`
--
ALTER TABLE `apis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category_groups`
--
ALTER TABLE `category_groups`
  MODIFY `id` smallint(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category_locales`
--
ALTER TABLE `category_locales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cookies`
--
ALTER TABLE `cookies`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cooks`
--
ALTER TABLE `cooks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cousines`
--
ALTER TABLE `cousines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cousine_extrascategories`
--
ALTER TABLE `cousine_extrascategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cousine_locals`
--
ALTER TABLE `cousine_locals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `extrascategories`
--
ALTER TABLE `extrascategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `global_settings`
--
ALTER TABLE `global_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `member_trans`
--
ALTER TABLE `member_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_logs`
--
ALTER TABLE `order_logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_splits`
--
ALTER TABLE `order_splits`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `printers`
--
ALTER TABLE `printers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_privilages`
--
ALTER TABLE `admin_privilages`
  ADD CONSTRAINT `FK1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD CONSTRAINT `FK_cashiers_admins` FOREIGN KEY (`restaurant_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `FK_orders_items_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
