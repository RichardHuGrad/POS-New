-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2016 at 05:43 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

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
  `address` text,
  `restaurant_name` varchar(100) NOT NULL,
  `tax` int(10) NOT NULL,
  `no_of_tables` int(10) NOT NULL DEFAULT '0',
  `no_of_takeout_tables` int(10) DEFAULT NULL,
  `no_of_waiting_tables` int(10) DEFAULT NULL,
  `table_size` text COMMENT 'table per size',
  `table_order` text,
  `takeout_table_size` text,
  `waiting_table_size` text,
  `is_verified` enum('Y','N') NOT NULL COMMENT 'Y-yes, N-no',
  `printer_ip` varchar(50) DEFAULT NULL,
  `printer_device_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `created_by_id`, `firstname`, `lastname`, `email`, `password`, `is_super_admin`, `created`, `modified`, `status`, `mobile_no`, `address`, `restaurant_name`, `tax`, `no_of_tables`, `no_of_takeout_tables`, `no_of_waiting_tables`, `table_size`, `table_order`, `takeout_table_size`, `waiting_table_size`, `is_verified`, `printer_ip`, `printer_device_id`) VALUES
(1, 0, 'POS', 'Admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Y', '0000-00-00 00:00:00', '2016-07-13 19:24:51', 'A', NULL, NULL, '', 0, 0, NULL, NULL, '0', NULL, NULL, NULL, 'Y', NULL, NULL),
(5, 1, 'restaurant', 'panel', 'restaurant@pos_v1.com', 'e10adc3949ba59abbe56e057f20f883e', 'N', '2016-06-30 08:31:12', '2016-10-30 16:15:31', 'A', '213131321', 'yahoo hsshshsh', 'HeyNoodle', 13, 19, 9, 9, '12,5,4,6,5,2,4,5,4,5,1,2,4,2,2', '["position: absolute; left: 63.9477%; top: 0%;","position: absolute; left: 63.9984%; top: 19.4453%;","position: absolute; left: 64.0927%; top: 39.1016%;","position: absolute; left: 64.1555%; top: 59.25%;","position: absolute; left: 64.4029%; top: 78.125%;","position: absolute; left: 11.8796%; top: 0%;","position: absolute; left: 11.8735%; top: 19.7917%;","position: absolute; left: 11.8867%; top: 39.1667%;","position: absolute; left: 12.0185%; top: 59.375%;","position: absolute; left: 38.382%; top: 0%;","position: absolute; left: 31.9597%; top: 10.2083%;","position: absolute; left: 38.1225%; top: 24.1953%;","position: absolute; left: 32.3824%; top: 36.6641%;","position: absolute; left: 37.94%; top: 48.3229%;","position: absolute; left: 32.2374%; top: 63.3281%;","position: absolute; left: 38.1438%; top: 78.125%;","position: absolute; left: 0%; top: 78.125%;","position: absolute; left: 8.12044%; top: 78.125%;","position: absolute; left: 17.0022%; top: 78.125%;"]', '1,2,5,7,8,9,5,4,2', '8,5,6,8,2,2,2,3,2', 'Y', '192.168.192.168', 'device1');

-- --------------------------------------------------------

--
-- Table structure for table `admin_privilages`
--

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
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` int(10) NOT NULL,
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
  `printer_device_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `restaurant_id`, `firstname`, `lastname`, `mobile_no`, `email`, `password`, `image`, `is_verified`, `status`, `created`, `modified`, `printer_ip`, `printer_device_id`) VALUES
(3, 5, 'bhawani', 'shankar', '7023311807', 'cashier@pos_v1.com', 'e10adc3949ba59abbe56e057f20f883e', '1467436684_Cashier.jpg', 'Y', 'A', '2016-06-30 08:49:53', '2016-07-04 18:39:58', NULL, NULL),
(4, 5, 'a', 'a', '0000000000', 'xxx@hotmail.com', 'c33367701511b4f6020ec61ded352059', NULL, 'Y', 'A', '2016-07-13 11:36:35', '2016-09-20 06:52:07', '123.12.123.122', '223121213'),
(5, 5, 'Cashier', '01', '12345678', '01@pos.com', '96e79218965eb72c92a549dd5a330112', NULL, 'Y', 'A', '2016-10-29 14:55:06', '2016-10-29 14:55:06', '123', '123'),
(6, 5, 'cashier', '02', '11111111111111', '02@pos.com', '96e79218965eb72c92a549dd5a330112', NULL, 'Y', 'A', '2016-10-29 14:55:47', '2016-10-29 14:55:47', '111111', '111111'),
(7, 5, 'cashier', '03', '1112223333', '03@pos.com', '96e79218965eb72c92a549dd5a330112', NULL, 'Y', 'A', '2016-10-29 14:56:25', '2016-10-29 14:56:25', '111111', '111111');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `printer` enum('C','K') DEFAULT 'K' COMMENT 'C-Cashier, K-kitchen'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `status`, `created`, `modified`, `printer`) VALUES
(5, 'A', 1467197749, 1467364041, 'K'),
(6, 'A', 1467364070, 1477605350, 'K'),
(7, 'A', 1467364088, 1477604464, 'C'),
(8, 'A', 1468423867, 1477604753, 'K'),
(9, 'A', 1468423954, 1477604504, 'K');

-- --------------------------------------------------------

--
-- Table structure for table `category_locales`
--

CREATE TABLE `category_locales` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_locales`
--

INSERT INTO `category_locales` (`id`, `category_id`, `name`, `lang_code`, `created`, `modified`) VALUES
(1, 5, 'Noodles', 'en', 1467197749, 1467364042),
(2, 5, 'é¢æ¡', 'zh', 1467197749, 1467364042),
(3, 8, 'Legend Lu', 'en', 1468423867, 1477604753),
(4, 8, 'ä¼ å¥‡è€å¤', 'zh', 1468423867, 1477604753),
(5, 9, 'Rice', 'en', 1468423954, 1477604504),
(6, 9, 'é¥­ç±»', 'zh', 1468423954, 1477604504),
(7, 6, 'Chongqing Sweet', 'en', 1467364070, 1477605350),
(8, 6, 'é‡åº†ç”œç‚¹', 'zh', 1467364070, 1477605350),
(9, 7, 'Drink', 'en', 1467364088, 1477604464),
(10, 7, 'é¥®æ–™', 'zh', 1467364088, 1477604464);

-- --------------------------------------------------------

--
-- Table structure for table `cooks`
--

CREATE TABLE `cooks` (
  `id` int(10) NOT NULL,
  `restaurant_id` int(10) NOT NULL DEFAULT '0',
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_verified` enum('Y','N') NOT NULL COMMENT 'Y-yes, N-no',
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A-active, I-inactive',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cooks`
--

INSERT INTO `cooks` (`id`, `restaurant_id`, `firstname`, `lastname`, `mobile_no`, `email`, `password`, `is_verified`, `status`, `created`, `modified`) VALUES
(4, 5, 'Danial', 'pos_v1', '1245512454', 'cook@pos.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y', 'A', '2016-07-09 11:38:20', '2016-10-12 02:25:37'),
(5, 5, '111', '222', ' 11111111', 'abc@hotmail.com', '50207fa2814e81a067bd2662ba10b0f1', 'Y', 'A', '2016-11-14 17:17:35', '2016-11-14 17:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `cousines`
--

CREATE TABLE `cousines` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL DEFAULT '0',
  `casier_id` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(100) DEFAULT '0',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` int(11) NOT NULL,
  `popular` bigint(20) NOT NULL DEFAULT '0',
  `is_tax` enum('Y','N') DEFAULT 'Y',
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cousines`
--

INSERT INTO `cousines` (`id`, `restaurant_id`, `casier_id`, `price`, `category_id`, `image`, `status`, `created`, `popular`, `is_tax`, `modified`) VALUES
(10, 5, 3, 10.99, 5, '1467777127_cousine.png', 'A', 1467364439, 34, 'Y', 1477667337),
(11, 5, 3, 9.99, 5, '1467370839_cousine.jpg', 'A', 1467364487, 48, 'Y', 1477668273),
(12, 5, 3, 6.5, 5, '1467370844_cousine.jpg', 'A', 1467364534, 20, 'Y', 1477667474),
(13, 5, 3, 9.99, 5, '1467370850_cousine.jpg', 'A', 1467364578, 18, 'Y', 1477667522),
(14, 5, 3, 10.99, 5, '1467370863_cousine.jpg', 'A', 1467364621, 19, 'Y', 1477667626),
(15, 5, 3, 9.99, 5, '1467370868_cousine.jpg', 'A', 1467364665, 35, 'Y', 1477667632),
(16, 5, 3, 6.5, 5, '1467370873_cousine.jpg', 'A', 1467364733, 25, 'Y', 1477667686),
(17, 5, 3, 5.99, 6, '', 'A', 1467452671, 27, 'Y', 1477664925),
(18, 5, 3, 1.5, 6, NULL, 'I', 1467452729, 14, 'Y', 1467453136),
(19, 5, 0, 4, 6, '1468422744_cousine.jpg', 'I', 1468422744, 8, 'Y', 1468422744),
(20, 5, 0, 0.99, 8, NULL, 'A', 1468435866, 5, 'Y', 1477665500),
(21, 5, 0, 10.99, 9, NULL, 'A', 1468436158, 0, 'Y', 1477604935),
(22, 5, 0, 9.99, 9, NULL, 'A', 1468436565, 1, 'Y', 1477604964),
(23, 5, 0, 10.99, 5, NULL, 'A', 1468436915, 0, 'Y', 1477667724),
(24, 5, 0, 1.99, 7, NULL, 'A', 1468436986, 3, 'Y', 1477601998),
(25, 5, 0, 10.99, 9, NULL, 'A', 1468437080, 1, 'Y', 1477604992),
(26, 5, 0, 1.99, 7, NULL, 'A', 1468438109, 0, 'Y', 1477603710),
(27, 5, 0, 10, 9, NULL, 'I', 1468438246, 2, 'Y', 1468438246),
(28, 5, 0, 15, 6, NULL, 'I', 1468438527, 1, 'Y', 1468438527),
(30, 5, 0, 9.99, 5, NULL, 'A', 1468438651, 2, 'Y', 1477667761),
(31, 5, 0, 9.99, 5, NULL, 'A', 1468438689, 5, 'Y', 1477667809),
(33, 5, 0, 1.99, 7, NULL, 'A', 1468440901, 1, 'Y', 1477603721),
(34, 5, 0, 1.99, 7, NULL, 'A', 1468443499, 1, 'Y', 1477603685),
(36, 5, 0, 1.99, 7, NULL, 'A', 1468443568, 0, 'Y', 1477603844),
(37, 5, 0, 11.99, 5, NULL, 'A', 1468443619, 3, 'Y', 1477667831),
(38, 5, 0, 1.99, 7, '1476103101_cousine.png', 'A', 1468443674, 3, 'Y', 1477603876),
(39, 5, 0, 50, 9, NULL, 'I', 1468443709, 6, 'Y', 1468443709),
(41, 5, 0, 1.99, 7, NULL, 'A', 1477603911, 0, 'Y', 1477603911),
(42, 5, 0, 1.99, 7, NULL, 'A', 1477603958, 1, 'Y', 1477603958),
(43, 5, 0, 2.5, 7, NULL, 'A', 1477603986, 2, 'Y', 1477605280),
(44, 5, 0, 2.5, 7, NULL, 'A', 1477604007, 1, 'Y', 1477605289),
(45, 5, 0, 1.99, 7, NULL, 'A', 1477604031, 1, 'Y', 1477604031),
(46, 5, 0, 1.99, 7, NULL, 'A', 1477604360, 1, 'Y', 1477604360),
(47, 5, 0, 1.99, 7, NULL, 'A', 1477604383, 0, 'Y', 1477604383),
(48, 5, 0, 9.99, 9, NULL, 'A', 1477605183, 1, 'Y', 1477605183),
(49, 5, 0, 1.99, 8, NULL, 'A', 1477665448, 0, 'Y', 1477665448),
(50, 5, 0, 4.99, 8, NULL, 'A', 1477665522, 0, 'Y', 1477665522),
(51, 5, 0, 6.99, 8, NULL, 'A', 1477665546, 0, 'Y', 1477665546),
(52, 5, 0, 6.99, 8, NULL, 'A', 1477665714, 1, 'Y', 1477665714),
(53, 5, 0, 6.99, 8, NULL, 'A', 1477665738, 0, 'Y', 1477665738),
(54, 5, 0, 5.5, 8, NULL, 'A', 1477665769, 0, 'Y', 1477665769),
(55, 5, 0, 3.5, 8, NULL, 'A', 1477665815, 1, 'Y', 1477665915),
(56, 5, 0, 5, 8, NULL, 'A', 1477665898, 0, 'Y', 1477665898),
(57, 5, 0, 9.99, 5, NULL, 'A', 1477667883, 3, 'Y', 1477667883),
(58, 5, 0, 6.5, 5, NULL, 'A', 1477667905, 2, 'Y', 1477668515),
(59, 5, 0, 10.99, 5, NULL, 'A', 1477667938, 1, 'Y', 1477668474),
(60, 5, 0, 9.99, 5, NULL, 'A', 1477668099, 0, 'Y', 1477668099),
(61, 5, 0, 10.99, 5, NULL, 'A', 1477668160, 1, 'Y', 1477668160),
(62, 5, 0, 10.99, 5, NULL, 'A', 1477668207, 0, 'Y', 1477668207),
(63, 5, 0, 9.99, 5, NULL, 'A', 1477677273, 1, 'Y', 1477677273);

-- --------------------------------------------------------

--
-- Table structure for table `cousine_locals`
--

CREATE TABLE `cousine_locals` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lang_code` char(2) NOT NULL DEFAULT 'en',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `cousine_locals`
--

INSERT INTO `cousine_locals` (`id`, `parent_id`, `name`, `lang_code`, `created`, `modified`) VALUES
(15, 10, 'Noodles w/Beef Sirloin', 'en', 1467364439, 1477667337),
(16, 10, 'å®‹å«‚ç‰›è‚‰é¢', 'zh', 1467364439, 1477667337),
(17, 11, 'Noodles w/Pea & Minced Meat', 'en', 1467364487, 1477668273),
(18, 11, 'é‡åº†è±Œæ‚é¢', 'zh', 1467364487, 1477668273),
(19, 12, 'Sichuan Style Hot & Spicy Wonton', 'en', 1467364534, 1477667474),
(20, 12, 'è€éº»æŠ„æ‰‹', 'zh', 1467364534, 1477667474),
(21, 58, 'Sichuan Style Wonton', 'en', 1477667905, 1477668515),
(22, 58, 'éª¨æ±¤æŠ„æ‰‹', 'zh', 1477667905, 1477668515),
(23, 14, 'Noodles w/Tomatoes & Beef Sirloin', 'en', 1467364621, 1477667626),
(24, 14, 'ç•ªèŒ„ç‰›è‚‰é¢', 'zh', 1467364621, 1477667626),
(25, 15, 'Noodles w/Tomatoes & Pork Chops', 'en', 1467364665, 1477667632),
(26, 15, 'ç•ªèŒ„å¤§æŽ’é¢', 'zh', 1467364665, 1477667632),
(27, 16, 'Chongqing-style Noodles', 'en', 1467364733, 1477667686),
(28, 16, 'éº»è¾£å°é¢', 'zh', 1467364733, 1477667686),
(29, 17, 'Pragrant Paddy Jelly', 'en', 1467452671, 1477664925),
(30, 17, 'ç¨»é¦™å‡‰ç³•', 'zh', 1467452671, 1477664925),
(31, 18, 'Veg Momos', 'en', 1467452729, 1467453136),
(32, 18, 'è”¬èœèŽ«èŽ«', 'zh', 1467452729, 1467453136),
(33, 19, 'Rice', 'en', 1468422744, 1468422744),
(34, 19, 'é¥­', 'zh', 1468422744, 1468422744),
(35, 20, 'Egg', 'en', 1468435866, 1477665500),
(36, 20, 'å¤è›‹', 'zh', 1468435866, 1477665500),
(37, 21, 'Pork Intestine', 'en', 1468436158, 1477604935),
(38, 21, 'è‚¥è‚ é¥­', 'zh', 1468436158, 1477604935),
(39, 22, 'Marinated Pork', 'en', 1468436565, 1477604964),
(40, 22, 'å¤è‚‰é¥­', 'zh', 1468436565, 1477604964),
(41, 23, 'Soybean & Pork Intestine Noodles', 'en', 1468436915, 1477667724),
(42, 23, 'è±†é¦™è‚¥è‚ é¢', 'zh', 1468436915, 1477667724),
(43, 24, 'Coke', 'en', 1468436986, 1477601998),
(44, 24, 'å¯ä¹', 'zh', 1468436986, 1477601998),
(45, 25, 'Beef', 'en', 1468437080, 1477604992),
(46, 25, 'ç‰›è‚‰é¥­', 'zh', 1468437080, 1477604992),
(47, 26, 'Diet Coke', 'en', 1468438109, 1477603710),
(48, 26, 'å¥æ€¡', 'zh', 1468438109, 1477603710),
(49, 27, 'D8', 'en', 1468438246, 1468438246),
(50, 27, 'èœ8', 'zh', 1468438246, 1468438246),
(51, 28, 'D9', 'en', 1468438527, 1468438527),
(52, 28, 'èœ9', 'zh', 1468438527, 1468438527),
(53, 29, 'D10', 'en', 1468438574, 1468438574),
(54, 29, 'èœ10', 'zh', 1468438574, 1468438574),
(55, 30, 'Noodles w/Grilled Hot Peppers', 'en', 1468438651, 1477667761),
(56, 30, 'ç‰¹è‰²çƒ§æ¤’é¢', 'zh', 1468438651, 1477667761),
(57, 31, 'Classic Noodles w/Minced Meat', 'en', 1468438689, 1477667809),
(58, 31, 'ç»å…¸å¹¹æ‹Œé¢', 'zh', 1468438689, 1477667809),
(59, 32, 'd13', 'en', 1468440831, 1468440831),
(60, 32, 'cai13', 'zh', 1468440831, 1468440831),
(61, 33, 'Sprite', 'en', 1468440901, 1477603721),
(62, 33, 'é›ªç¢§', 'zh', 1468440901, 1477603721),
(63, 34, 'C Plus', 'en', 1468443499, 1477603685),
(64, 34, 'æ©™å­æ±½æ°´', 'zh', 1468443499, 1477603685),
(65, 35, 'D15', 'en', 1468443537, 1468443537),
(66, 35, 'èœ15', 'zh', 1468443537, 1468443537),
(67, 36, 'Ice Tea', 'en', 1468443568, 1477603844),
(68, 36, 'å†°èŒ¶', 'zh', 1468443568, 1477603844),
(69, 37, 'Nutritious Lamb Noodles', 'en', 1468443619, 1477667831),
(70, 37, 'æ»‹è¡¥ç¾Šè‚‰é¢', 'zh', 1468443619, 1477667831),
(71, 38, 'Ginger Ale', 'en', 1468443674, 1477603876),
(72, 38, 'å§œæ±æ±½æ°´', 'zh', 1468443674, 1477603876),
(73, 39, 'D19', 'en', 1468443709, 1468443709),
(74, 39, 'èœ19', 'zh', 1468443709, 1468443709),
(75, 40, 'testqtes', 'en', 1476523146, 1476523146),
(76, 40, 'tets', 'zh', 1476523146, 1476523146),
(77, 41, 'Spring Water', 'en', 1477603911, 1477603911),
(78, 41, 'ç“¶è£…æ°´', 'zh', 1477603911, 1477603911),
(79, 42, 'Soya Milk', 'en', 1477603958, 1477603958),
(80, 42, 'è±†æµ†', 'zh', 1477603958, 1477603958),
(81, 43, 'Arizona Green Tea', 'en', 1477603986, 1477605280),
(82, 43, 'ç»¿èŒ¶', 'zh', 1477603986, 1477605280),
(83, 44, 'Chinese Herbal Tea', 'en', 1477604007, 1477605289),
(84, 44, 'çŽ‹è€å‰', 'zh', 1477604007, 1477605289),
(85, 45, 'Plum Juice', 'en', 1477604031, 1477604031),
(86, 45, 'ç§˜åˆ¶é…¸æ¢…æ±¤', 'zh', 1477604031, 1477604031),
(87, 46, 'Orange Juice', 'en', 1477604360, 1477604360),
(88, 46, 'æ©™æ±', 'zh', 1477604360, 1477604360),
(89, 47, 'Apple Juice', 'en', 1477604383, 1477604383),
(90, 47, 'è‹¹æžœæ±', 'zh', 1477604383, 1477604383),
(91, 48, 'Braised Pork Ribs Rice', 'en', 1477605183, 1477605183),
(92, 48, 'çº¢çƒ§æŽ’éª¨é¥­', 'zh', 1477605183, 1477605183),
(93, 49, 'Duck Wing', 'en', 1477665448, 1477665448),
(94, 49, 'é¸­ç¿…', 'zh', 1477665448, 1477665448),
(95, 50, 'Duck Neck', 'en', 1477665522, 1477665522),
(96, 50, 'é¸­è„–', 'zh', 1477665522, 1477665522),
(97, 51, 'Chicken Heart', 'en', 1477665546, 1477665546),
(98, 51, 'é¸¡å¿ƒ', 'zh', 1477665546, 1477665546),
(99, 52, 'Duck Gizzard', 'en', 1477665714, 1477665714),
(100, 52, 'é¸¡èƒ—', 'zh', 1477665714, 1477665714),
(101, 53, 'Chicken Soft Bones', 'en', 1477665738, 1477665738),
(102, 53, 'é¸¡è„†éª¨', 'zh', 1477665738, 1477665738),
(103, 54, 'Pig Feet', 'en', 1477665769, 1477665769),
(104, 54, 'çŠ¶å…ƒçŒªæ‰‹', 'zh', 1477665769, 1477665769),
(105, 55, 'Bowl Bowl Chicken 2 options', 'en', 1477665815, 1477665915),
(106, 55, 'é’µé’µé¸¡åŒæ‹¼', 'zh', 1477665815, 1477665915),
(107, 56, 'Bowl Bowl Chicken 3 options', 'en', 1477665898, 1477665898),
(108, 56, 'é’µé’µé¸¡ä¸‰æ‹¼', 'zh', 1477665898, 1477665898),
(109, 57, 'Noodles w/House Special Marinated Pork', 'en', 1477667883, 1477667883),
(110, 57, 'æ‹›ç‰Œå¤è‚‰é¢', 'zh', 1477667883, 1477667883),
(111, 13, 'Noodles w/Stewed Chicken & Mushroom', 'en', 1467364578, 1477667522),
(112, 13, 'é¦™è‡ç‚–é¸¡é¢', 'zh', 1467364578, 1477667522),
(113, 59, 'Chongqing-style Noodles w/Shrimp', 'en', 1477667938, 1477668474),
(114, 59, 'å¤§è™¾å°é¢', 'zh', 1477667938, 1477668474),
(115, 60, 'Hot&Sour Sweet Potato Vermicelli', 'en', 1477668099, 1477668099),
(116, 60, 'ç‰¹è‰²é…¸è¾£ç²‰', 'zh', 1477668099, 1477668099),
(117, 61, 'Beef w/Hot&Sour Sweet Potato Vermicelli', 'en', 1477668160, 1477668160),
(118, 61, 'ç‰›è‚‰é…¸è¾£ç²‰', 'zh', 1477668160, 1477668160),
(119, 62, 'Soybean & Pork Intestine w/ Hot&Sour Sweet Potato Vermicelli', 'en', 1477668207, 1477668207),
(120, 62, 'è‚¥è‚ é…¸è¾£ç²‰', 'zh', 1477668207, 1477668207),
(121, 63, 'Noodle w/Braised Pork Ribs', 'en', 1477677273, 1477677273),
(122, 63, 'é£˜é¦™æŽ’éª¨é¢', 'zh', 1477677273, 1477677273);

-- --------------------------------------------------------

--
-- Table structure for table `extras`
--

CREATE TABLE `extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `cousine_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_zh` varchar(100) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `status` enum('A','I') DEFAULT 'A',
  `category_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extras`
--

INSERT INTO `extras` (`id`, `cousine_id`, `name`, `name_zh`, `price`, `status`, `category_id`, `created`) VALUES
(1, 50, 'No', 'ä¸', 0, 'A', 1, '2016-10-28 10:50:52'),
(2, 47, 'Less', 'å°‘', 0, 'A', 1, '2016-10-28 10:56:45'),
(3, 50, 'Normal', 'æ­£å¸¸', 0, 'A', 1, '2016-10-28 11:04:36'),
(4, 50, 'More', 'å¤š', 0, 'A', 2, '2016-10-28 11:04:50'),
(5, 50, 'Extra', 'ç‰¹', 0, 'A', 2, '2016-10-28 11:05:06'),
(6, 50, 'Spicy', 'è¾£', 0, 'A', 2, '2016-10-28 11:05:20'),
(7, 50, 'Numb', 'éº»', 0, 'A', 2, '2016-10-28 11:05:37'),
(23, 34, 'Peanut', 'èŠ±ç”Ÿ', 0, 'A', 2, '2016-07-01 11:23:35'),
(24, 34, 'Caraway', 'é¦™èœ', 0, 'A', 2, '2016-07-01 11:23:53'),
(25, 34, 'Green Onion', 'è‘±', 0, 'A', 2, '2016-07-01 11:24:07'),
(26, 34, 'Garlic', 'è’œ', 0, 'A', 2, '2016-07-13 11:22:12'),
(27, 34, 'Sesame', 'èŠéº»', 0, 'A', 2, '2016-07-13 11:23:14'),
(28, 34, 'Sesame Paste', 'èŠéº»é…±', 0, 'A', 2, '2016-10-15 02:48:28'),
(29, 34, '-', '-', 0, 'A', 2, '2016-10-27 15:38:26'),
(30, 34, '-', '-', 0, 'A', 2, '2016-10-27 16:16:01'),
(31, 34, 'Noodle Soft', 'é¢è½¯', 0, 'A', 2, '2016-10-27 16:37:18'),
(32, 34, 'Noodle Hard', 'é¢ç¡¬', 0, 'A', 2, '2016-10-27 16:37:50'),
(33, 47, 'Add noodle-Dine In', 'åŠ é¢-å ‚é£Ÿ', 0, 'A', 2, '2016-10-27 16:42:17'),
(34, 34, 'Add Noodle-Take out', 'åŠ é¢-å¤–å–', 2, 'A', 2, '2016-10-27 16:42:24'),
(35, 34, 'Less Oil', 'å°‘æ²¹', 0, 'A', 2, '2016-10-27 16:42:02'),
(36, 61, 'Vegetable-small', 'é’èœ-å°', 1.99, 'A', 2, '2016-10-27 16:43:00'),
(37, 61, 'Vegetable-large', 'é’èœ-å¤§', 4.99, 'A', 2, '2016-10-28 11:47:53'),
(38, 61, 'Meat', 'è‚‰', 5.99, 'A', 2, '2016-10-27 16:43:21'),
(39, 34, 'Egg', 'è›‹', 1, 'A', 2, '2016-10-27 16:43:34'),
(40, 34, '-', '-', 0, 'A', 2, '2016-10-27 16:44:25'),
(41, 34, 'Black Fungus', 'æœ¨è€³', 0, 'A', 2, '2016-10-27 16:45:59'),
(42, 34, 'Seaweed', 'æµ·å¸¦', 0, 'A', 1, '2016-10-27 16:47:27'),
(43, 34, 'Tofu Skin', 'è±†è…çš®', 0, 'A', 1, '2016-10-27 16:50:13'),
(44, 34, 'Oyster Mushroom', 'å¹³è‡', 0, 'A', 2, '2016-10-27 16:50:36'),
(45, 34, 'Fish Tofu', 'é±¼è±†è…', 0, 'A', 1, '2016-10-27 16:51:21'),
(46, 46, 'Lotus Root', 'è—•ç‰‡', 0, 'A', 1, '2016-10-27 16:51:43'),
(47, 34, 'Enoki Mushroom', 'é‡‘é’ˆè‡', 0, 'A', 2, '2016-10-27 16:52:13'),
(48, 34, 'Beef Tendon Ball', 'ç‰›ç­‹ä¸¸', 0, 'A', 2, '2016-10-27 16:52:43'),
(49, 34, 'Fish Ball', 'é±¼è›‹', 0, 'A', 2, '2016-10-27 16:52:58'),
(50, 34, 'Quail Eggs', 'é¹Œé¹‘è›‹', 0, 'A', 2, '2016-10-27 16:57:02'),
(52, 47, 'Shredded Potato', 'åœŸè±†ä¸', 0, 'A', 1, '2016-10-28 10:15:00'),
(53, 47, 'ï¼ï¼ï¼', 'ï¼ï¼ï¼', 0, 'A', 1, '2016-10-29 15:02:39'),
(54, 47, 'ï¼ï¼ï¼', 'ï¼ï¼ï¼', 0, 'A', 2, '2016-10-29 15:03:23'),
(55, 47, 'Take out', 'å¤–å–', 0, 'A', 2, '2016-10-29 15:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `extrascategories`
--

CREATE TABLE `extrascategories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `name_zh` varchar(50) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extrascategories`
--

INSERT INTO `extrascategories` (`id`, `name`, `name_zh`, `status`) VALUES
(1, 'test', 'å£å‘³', 'A'),
(2, 'test1', 'æµ‹è¯•', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE `global_settings` (
  `id` int(11) NOT NULL,
  `delivery_charge` float NOT NULL,
  `from_email` varchar(255) NOT NULL COMMENT 'email id to show in send from email',
  `to_email` varchar(255) NOT NULL COMMENT 'email id to send to the admin',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `delivery_charge`, `from_email`, `to_email`, `created`, `modified`) VALUES
(1, 0, 'narendra.prajapat@brsoftech.org', 'narendra.prajapat@brsoftech.org', '2016-05-02 11:08:57', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language` varchar(100) NOT NULL,
  `lang_code` char(2) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language`, `lang_code`, `status`) VALUES
(1, 'English', 'en', 'A'),
(2, 'Chinese', 'zh', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) NOT NULL,
  `order_no` varchar(10) NOT NULL DEFAULT '0',
  `reorder_no` varchar(10) DEFAULT '0',
  `hide_no` bigint(20) DEFAULT '0',
  `cashier_id` int(10) DEFAULT NULL COMMENT 'stand for restaurant_id',
  `counter_id` int(10) DEFAULT NULL COMMENT 'stand for cashier',
  `table_no` int(10) DEFAULT NULL,
  `table_status` enum('P','N','A','V') DEFAULT 'N' COMMENT 'P-paid, N-not paid, A-available, V-Void',
  `tax` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `card_val` float DEFAULT NULL,
  `cash_val` float DEFAULT NULL,
  `tip` float DEFAULT NULL,
  `tip_paid_by` enum('CARD','CASH') DEFAULT NULL,
  `paid` float DEFAULT NULL,
  `change` float DEFAULT NULL,
  `promocode` varchar(100) DEFAULT NULL,
  `message` text,
  `reason` text,
  `order_type` enum('D','T','W') DEFAULT NULL COMMENT 'D-Dinein, T-takeway, W-waiting',
  `is_kitchen` enum('Y','N') DEFAULT 'N',
  `cooking_status` enum('COOKED','UNCOOKED') DEFAULT 'UNCOOKED',
  `is_hide` enum('Y','N','P') DEFAULT 'P' COMMENT 'P-Pending',
  `created` datetime DEFAULT NULL,
  `is_completed` enum('Y','N') DEFAULT 'N',
  `paid_by` enum('CARD','CASH','MIXED') DEFAULT NULL,
  `fix_discount` float DEFAULT NULL,
  `percent_discount` float DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `merge_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `reorder_no`, `hide_no`, `cashier_id`, `counter_id`, `table_no`, `table_status`, `tax`, `tax_amount`, `subtotal`, `total`, `card_val`, `cash_val`, `tip`, `tip_paid_by`, `paid`, `change`, `promocode`, `message`, `reason`, `order_type`, `is_kitchen`, `cooking_status`, `is_hide`, `created`, `is_completed`, `paid_by`, `fix_discount`, `percent_discount`, `discount_value`, `merge_id`) VALUES
(1, '87941', '0', 0, 5, 3, 11, 'P', 13, 3.4424, 26.48, 29.9224, NULL, 50, NULL, NULL, 37.5824, 7.66, NULL, NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-30 16:46:57', 'Y', 'CASH', NULL, NULL, NULL, 0),
(2, '91652', '0', 0, 5, 3, 13, 'P', 13, 1.4287, 10.99, 12.4187, 0, 0, 0, NULL, 12.4187, 0, NULL, NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-12-02 16:11:00', 'Y', 'CASH', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) NOT NULL,
  `order_id` int(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `name_xh` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL COMMENT 'P-paid, N-not paid, A-available',
  `qty` int(10) NOT NULL DEFAULT '1',
  `tax` float NOT NULL DEFAULT '0',
  `tax_amount` float DEFAULT '0',
  `selected_extras` text,
  `all_extras` text,
  `extras_amount` float DEFAULT NULL,
  `is_done` enum('Y','N') NOT NULL DEFAULT 'N',
  `created` datetime DEFAULT NULL,
  `is_print` enum('N','Y') DEFAULT 'N',
  `is_kitchen` enum('N','Y') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `name_en`, `name_xh`, `category_id`, `price`, `qty`, `tax`, `tax_amount`, `selected_extras`, `all_extras`, `extras_amount`, `is_done`, `created`, `is_print`, `is_kitchen`) VALUES
(9, 1, 57, 'Noodles w/House Special Marinated Pork', 'æ‹›ç‰Œå¤è‚‰é¢', 5, 9.99, 1, 13, 1.2987, NULL, '[{"id":"1","cousine_id":"50","name":"No","name_zh":"\\u4e0d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:50:52"},{"id":"42","cousine_id":"34","name":"Seaweed","name_zh":"\\u6d77\\u5e26","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:47:27"},{"id":"43","cousine_id":"34","name":"Tofu Skin","name_zh":"\\u8c46\\u8150\\u76ae","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:50:13"},{"id":"45","cousine_id":"34","name":"Fish Tofu","name_zh":"\\u9c7c\\u8c46\\u8150","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:21"},{"id":"46","cousine_id":"46","name":"Lotus Root","name_zh":"\\u85d5\\u7247","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:43"},{"id":"52","cousine_id":"47","name":"Shredded Potato","name_zh":"\\u571f\\u8c46\\u4e1d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:15:00"},{"id":"3","cousine_id":"50","name":"Normal","name_zh":"\\u6b63\\u5e38","price":"0","status":"A","category_id":"1","created":"2016-10-28 11:04:36"},{"id":"53","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"1","created":"2016-10-29 15:02:39"},{"id":"2","cousine_id":"47","name":"Less","name_zh":"\\u5c11","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:56:45"},{"id":"38","cousine_id":"61","name":"Meat","name_zh":"\\u8089","price":"5.99","status":"A","category_id":"2","created":"2016-10-27 16:43:21"},{"id":"39","cousine_id":"34","name":"Egg","name_zh":"\\u86cb","price":"1","status":"A","category_id":"2","created":"2016-10-27 16:43:34"},{"id":"40","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:44:25"},{"id":"41","cousine_id":"34","name":"Black Fungus","name_zh":"\\u6728\\u8033","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:45:59"},{"id":"50","cousine_id":"34","name":"Quail Eggs","name_zh":"\\u9e4c\\u9e51\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:57:02"},{"id":"44","cousine_id":"34","name":"Oyster Mushroom","name_zh":"\\u5e73\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:50:36"},{"id":"54","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:23"},{"id":"47","cousine_id":"34","name":"Enoki Mushroom","name_zh":"\\u91d1\\u9488\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:13"},{"id":"48","cousine_id":"34","name":"Beef Tendon Ball","name_zh":"\\u725b\\u7b4b\\u4e38","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:43"},{"id":"49","cousine_id":"34","name":"Fish Ball","name_zh":"\\u9c7c\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:58"},{"id":"37","cousine_id":"61","name":"Vegetable-large","name_zh":"\\u9752\\u83dc-\\u5927","price":"4.99","status":"A","category_id":"2","created":"2016-10-28 11:47:53"},{"id":"36","cousine_id":"61","name":"Vegetable-small","name_zh":"\\u9752\\u83dc-\\u5c0f","price":"1.99","status":"A","category_id":"2","created":"2016-10-27 16:43:00"},{"id":"35","cousine_id":"34","name":"Less Oil","name_zh":"\\u5c11\\u6cb9","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:02"},{"id":"4","cousine_id":"50","name":"More","name_zh":"\\u591a","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:04:50"},{"id":"5","cousine_id":"50","name":"Extra","name_zh":"\\u7279","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:06"},{"id":"6","cousine_id":"50","name":"Spicy","name_zh":"\\u8fa3","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:20"},{"id":"7","cousine_id":"50","name":"Numb","name_zh":"\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:37"},{"id":"23","cousine_id":"34","name":"Peanut","name_zh":"\\u82b1\\u751f","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:35"},{"id":"24","cousine_id":"34","name":"Caraway","name_zh":"\\u9999\\u83dc","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:53"},{"id":"25","cousine_id":"34","name":"Green Onion","name_zh":"\\u8471","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:24:07"},{"id":"26","cousine_id":"34","name":"Garlic","name_zh":"\\u849c","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:22:12"},{"id":"27","cousine_id":"34","name":"Sesame","name_zh":"\\u829d\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:23:14"},{"id":"28","cousine_id":"34","name":"Sesame Paste","name_zh":"\\u829d\\u9ebb\\u9171","price":"0","status":"A","category_id":"2","created":"2016-10-15 02:48:28"},{"id":"29","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 15:38:26"},{"id":"30","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:16:01"},{"id":"31","cousine_id":"34","name":"Noodle Soft","name_zh":"\\u9762\\u8f6f","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:18"},{"id":"32","cousine_id":"34","name":"Noodle Hard","name_zh":"\\u9762\\u786c","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:50"},{"id":"33","cousine_id":"47","name":"Add noodle-Dine In","name_zh":"\\u52a0\\u9762-\\u5802\\u98df","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:17"},{"id":"34","cousine_id":"34","name":"Add Noodle-Take out","name_zh":"\\u52a0\\u9762-\\u5916\\u5356","price":"2","status":"A","category_id":"2","created":"2016-10-27 16:42:24"},{"id":"55","cousine_id":"47","name":"Take out","name_zh":"\\u5916\\u5356","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:35"}]', NULL, 'N', '2016-12-05 15:22:37', 'N', 'N'),
(10, 1, 16, 'Chongqing-style Noodles', 'éº»è¾£å°é¢', 5, 6.5, 1, 13, 0.845, NULL, '[{"id":"1","cousine_id":"50","name":"No","name_zh":"\\u4e0d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:50:52"},{"id":"42","cousine_id":"34","name":"Seaweed","name_zh":"\\u6d77\\u5e26","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:47:27"},{"id":"43","cousine_id":"34","name":"Tofu Skin","name_zh":"\\u8c46\\u8150\\u76ae","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:50:13"},{"id":"45","cousine_id":"34","name":"Fish Tofu","name_zh":"\\u9c7c\\u8c46\\u8150","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:21"},{"id":"46","cousine_id":"46","name":"Lotus Root","name_zh":"\\u85d5\\u7247","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:43"},{"id":"52","cousine_id":"47","name":"Shredded Potato","name_zh":"\\u571f\\u8c46\\u4e1d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:15:00"},{"id":"3","cousine_id":"50","name":"Normal","name_zh":"\\u6b63\\u5e38","price":"0","status":"A","category_id":"1","created":"2016-10-28 11:04:36"},{"id":"53","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"1","created":"2016-10-29 15:02:39"},{"id":"2","cousine_id":"47","name":"Less","name_zh":"\\u5c11","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:56:45"},{"id":"38","cousine_id":"61","name":"Meat","name_zh":"\\u8089","price":"5.99","status":"A","category_id":"2","created":"2016-10-27 16:43:21"},{"id":"39","cousine_id":"34","name":"Egg","name_zh":"\\u86cb","price":"1","status":"A","category_id":"2","created":"2016-10-27 16:43:34"},{"id":"40","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:44:25"},{"id":"41","cousine_id":"34","name":"Black Fungus","name_zh":"\\u6728\\u8033","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:45:59"},{"id":"50","cousine_id":"34","name":"Quail Eggs","name_zh":"\\u9e4c\\u9e51\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:57:02"},{"id":"44","cousine_id":"34","name":"Oyster Mushroom","name_zh":"\\u5e73\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:50:36"},{"id":"54","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:23"},{"id":"47","cousine_id":"34","name":"Enoki Mushroom","name_zh":"\\u91d1\\u9488\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:13"},{"id":"48","cousine_id":"34","name":"Beef Tendon Ball","name_zh":"\\u725b\\u7b4b\\u4e38","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:43"},{"id":"49","cousine_id":"34","name":"Fish Ball","name_zh":"\\u9c7c\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:58"},{"id":"37","cousine_id":"61","name":"Vegetable-large","name_zh":"\\u9752\\u83dc-\\u5927","price":"4.99","status":"A","category_id":"2","created":"2016-10-28 11:47:53"},{"id":"36","cousine_id":"61","name":"Vegetable-small","name_zh":"\\u9752\\u83dc-\\u5c0f","price":"1.99","status":"A","category_id":"2","created":"2016-10-27 16:43:00"},{"id":"35","cousine_id":"34","name":"Less Oil","name_zh":"\\u5c11\\u6cb9","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:02"},{"id":"4","cousine_id":"50","name":"More","name_zh":"\\u591a","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:04:50"},{"id":"5","cousine_id":"50","name":"Extra","name_zh":"\\u7279","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:06"},{"id":"6","cousine_id":"50","name":"Spicy","name_zh":"\\u8fa3","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:20"},{"id":"7","cousine_id":"50","name":"Numb","name_zh":"\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:37"},{"id":"23","cousine_id":"34","name":"Peanut","name_zh":"\\u82b1\\u751f","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:35"},{"id":"24","cousine_id":"34","name":"Caraway","name_zh":"\\u9999\\u83dc","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:53"},{"id":"25","cousine_id":"34","name":"Green Onion","name_zh":"\\u8471","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:24:07"},{"id":"26","cousine_id":"34","name":"Garlic","name_zh":"\\u849c","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:22:12"},{"id":"27","cousine_id":"34","name":"Sesame","name_zh":"\\u829d\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:23:14"},{"id":"28","cousine_id":"34","name":"Sesame Paste","name_zh":"\\u829d\\u9ebb\\u9171","price":"0","status":"A","category_id":"2","created":"2016-10-15 02:48:28"},{"id":"29","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 15:38:26"},{"id":"30","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:16:01"},{"id":"31","cousine_id":"34","name":"Noodle Soft","name_zh":"\\u9762\\u8f6f","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:18"},{"id":"32","cousine_id":"34","name":"Noodle Hard","name_zh":"\\u9762\\u786c","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:50"},{"id":"33","cousine_id":"47","name":"Add noodle-Dine In","name_zh":"\\u52a0\\u9762-\\u5802\\u98df","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:17"},{"id":"34","cousine_id":"34","name":"Add Noodle-Take out","name_zh":"\\u52a0\\u9762-\\u5916\\u5356","price":"2","status":"A","category_id":"2","created":"2016-10-27 16:42:24"},{"id":"55","cousine_id":"47","name":"Take out","name_zh":"\\u5916\\u5356","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:35"}]', NULL, 'N', '2016-12-05 15:22:45', 'N', 'N'),
(11, 1, 31, 'Classic Noodles w/Minced Meat', 'ç»å…¸å¹¹æ‹Œé¢', 5, 9.99, 1, 13, 1.2987, NULL, '[{"id":"1","cousine_id":"50","name":"No","name_zh":"\\u4e0d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:50:52"},{"id":"42","cousine_id":"34","name":"Seaweed","name_zh":"\\u6d77\\u5e26","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:47:27"},{"id":"43","cousine_id":"34","name":"Tofu Skin","name_zh":"\\u8c46\\u8150\\u76ae","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:50:13"},{"id":"45","cousine_id":"34","name":"Fish Tofu","name_zh":"\\u9c7c\\u8c46\\u8150","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:21"},{"id":"46","cousine_id":"46","name":"Lotus Root","name_zh":"\\u85d5\\u7247","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:43"},{"id":"52","cousine_id":"47","name":"Shredded Potato","name_zh":"\\u571f\\u8c46\\u4e1d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:15:00"},{"id":"3","cousine_id":"50","name":"Normal","name_zh":"\\u6b63\\u5e38","price":"0","status":"A","category_id":"1","created":"2016-10-28 11:04:36"},{"id":"53","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"1","created":"2016-10-29 15:02:39"},{"id":"2","cousine_id":"47","name":"Less","name_zh":"\\u5c11","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:56:45"},{"id":"38","cousine_id":"61","name":"Meat","name_zh":"\\u8089","price":"5.99","status":"A","category_id":"2","created":"2016-10-27 16:43:21"},{"id":"39","cousine_id":"34","name":"Egg","name_zh":"\\u86cb","price":"1","status":"A","category_id":"2","created":"2016-10-27 16:43:34"},{"id":"40","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:44:25"},{"id":"41","cousine_id":"34","name":"Black Fungus","name_zh":"\\u6728\\u8033","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:45:59"},{"id":"50","cousine_id":"34","name":"Quail Eggs","name_zh":"\\u9e4c\\u9e51\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:57:02"},{"id":"44","cousine_id":"34","name":"Oyster Mushroom","name_zh":"\\u5e73\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:50:36"},{"id":"54","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:23"},{"id":"47","cousine_id":"34","name":"Enoki Mushroom","name_zh":"\\u91d1\\u9488\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:13"},{"id":"48","cousine_id":"34","name":"Beef Tendon Ball","name_zh":"\\u725b\\u7b4b\\u4e38","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:43"},{"id":"49","cousine_id":"34","name":"Fish Ball","name_zh":"\\u9c7c\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:58"},{"id":"37","cousine_id":"61","name":"Vegetable-large","name_zh":"\\u9752\\u83dc-\\u5927","price":"4.99","status":"A","category_id":"2","created":"2016-10-28 11:47:53"},{"id":"36","cousine_id":"61","name":"Vegetable-small","name_zh":"\\u9752\\u83dc-\\u5c0f","price":"1.99","status":"A","category_id":"2","created":"2016-10-27 16:43:00"},{"id":"35","cousine_id":"34","name":"Less Oil","name_zh":"\\u5c11\\u6cb9","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:02"},{"id":"4","cousine_id":"50","name":"More","name_zh":"\\u591a","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:04:50"},{"id":"5","cousine_id":"50","name":"Extra","name_zh":"\\u7279","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:06"},{"id":"6","cousine_id":"50","name":"Spicy","name_zh":"\\u8fa3","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:20"},{"id":"7","cousine_id":"50","name":"Numb","name_zh":"\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:37"},{"id":"23","cousine_id":"34","name":"Peanut","name_zh":"\\u82b1\\u751f","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:35"},{"id":"24","cousine_id":"34","name":"Caraway","name_zh":"\\u9999\\u83dc","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:53"},{"id":"25","cousine_id":"34","name":"Green Onion","name_zh":"\\u8471","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:24:07"},{"id":"26","cousine_id":"34","name":"Garlic","name_zh":"\\u849c","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:22:12"},{"id":"27","cousine_id":"34","name":"Sesame","name_zh":"\\u829d\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:23:14"},{"id":"28","cousine_id":"34","name":"Sesame Paste","name_zh":"\\u829d\\u9ebb\\u9171","price":"0","status":"A","category_id":"2","created":"2016-10-15 02:48:28"},{"id":"29","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 15:38:26"},{"id":"30","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:16:01"},{"id":"31","cousine_id":"34","name":"Noodle Soft","name_zh":"\\u9762\\u8f6f","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:18"},{"id":"32","cousine_id":"34","name":"Noodle Hard","name_zh":"\\u9762\\u786c","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:50"},{"id":"33","cousine_id":"47","name":"Add noodle-Dine In","name_zh":"\\u52a0\\u9762-\\u5802\\u98df","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:17"},{"id":"34","cousine_id":"34","name":"Add Noodle-Take out","name_zh":"\\u52a0\\u9762-\\u5916\\u5356","price":"2","status":"A","category_id":"2","created":"2016-10-27 16:42:24"},{"id":"55","cousine_id":"47","name":"Take out","name_zh":"\\u5916\\u5356","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:35"}]', NULL, 'N', '2016-12-05 15:22:46', 'N', 'N'),
(12, 2, 10, 'Noodles w/Beef Sirloin', 'å®‹å«‚ç‰›è‚‰é¢', 5, 10.99, 1, 13, 1.4287, NULL, '[{"id":"1","cousine_id":"50","name":"No","name_zh":"\\u4e0d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:50:52"},{"id":"42","cousine_id":"34","name":"Seaweed","name_zh":"\\u6d77\\u5e26","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:47:27"},{"id":"43","cousine_id":"34","name":"Tofu Skin","name_zh":"\\u8c46\\u8150\\u76ae","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:50:13"},{"id":"45","cousine_id":"34","name":"Fish Tofu","name_zh":"\\u9c7c\\u8c46\\u8150","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:21"},{"id":"46","cousine_id":"46","name":"Lotus Root","name_zh":"\\u85d5\\u7247","price":"0","status":"A","category_id":"1","created":"2016-10-27 16:51:43"},{"id":"52","cousine_id":"47","name":"Shredded Potato","name_zh":"\\u571f\\u8c46\\u4e1d","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:15:00"},{"id":"3","cousine_id":"50","name":"Normal","name_zh":"\\u6b63\\u5e38","price":"0","status":"A","category_id":"1","created":"2016-10-28 11:04:36"},{"id":"53","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"1","created":"2016-10-29 15:02:39"},{"id":"2","cousine_id":"47","name":"Less","name_zh":"\\u5c11","price":"0","status":"A","category_id":"1","created":"2016-10-28 10:56:45"},{"id":"38","cousine_id":"61","name":"Meat","name_zh":"\\u8089","price":"5.99","status":"A","category_id":"2","created":"2016-10-27 16:43:21"},{"id":"39","cousine_id":"34","name":"Egg","name_zh":"\\u86cb","price":"1","status":"A","category_id":"2","created":"2016-10-27 16:43:34"},{"id":"40","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:44:25"},{"id":"41","cousine_id":"34","name":"Black Fungus","name_zh":"\\u6728\\u8033","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:45:59"},{"id":"50","cousine_id":"34","name":"Quail Eggs","name_zh":"\\u9e4c\\u9e51\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:57:02"},{"id":"44","cousine_id":"34","name":"Oyster Mushroom","name_zh":"\\u5e73\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:50:36"},{"id":"54","cousine_id":"47","name":"\\uff1d\\uff1d\\uff1d","name_zh":"\\uff1d\\uff1d\\uff1d","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:23"},{"id":"47","cousine_id":"34","name":"Enoki Mushroom","name_zh":"\\u91d1\\u9488\\u83c7","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:13"},{"id":"48","cousine_id":"34","name":"Beef Tendon Ball","name_zh":"\\u725b\\u7b4b\\u4e38","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:43"},{"id":"49","cousine_id":"34","name":"Fish Ball","name_zh":"\\u9c7c\\u86cb","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:52:58"},{"id":"37","cousine_id":"61","name":"Vegetable-large","name_zh":"\\u9752\\u83dc-\\u5927","price":"4.99","status":"A","category_id":"2","created":"2016-10-28 11:47:53"},{"id":"36","cousine_id":"61","name":"Vegetable-small","name_zh":"\\u9752\\u83dc-\\u5c0f","price":"1.99","status":"A","category_id":"2","created":"2016-10-27 16:43:00"},{"id":"35","cousine_id":"34","name":"Less Oil","name_zh":"\\u5c11\\u6cb9","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:02"},{"id":"4","cousine_id":"50","name":"More","name_zh":"\\u591a","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:04:50"},{"id":"5","cousine_id":"50","name":"Extra","name_zh":"\\u7279","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:06"},{"id":"6","cousine_id":"50","name":"Spicy","name_zh":"\\u8fa3","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:20"},{"id":"7","cousine_id":"50","name":"Numb","name_zh":"\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-10-28 11:05:37"},{"id":"23","cousine_id":"34","name":"Peanut","name_zh":"\\u82b1\\u751f","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:35"},{"id":"24","cousine_id":"34","name":"Caraway","name_zh":"\\u9999\\u83dc","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:23:53"},{"id":"25","cousine_id":"34","name":"Green Onion","name_zh":"\\u8471","price":"0","status":"A","category_id":"2","created":"2016-07-01 11:24:07"},{"id":"26","cousine_id":"34","name":"Garlic","name_zh":"\\u849c","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:22:12"},{"id":"27","cousine_id":"34","name":"Sesame","name_zh":"\\u829d\\u9ebb","price":"0","status":"A","category_id":"2","created":"2016-07-13 11:23:14"},{"id":"28","cousine_id":"34","name":"Sesame Paste","name_zh":"\\u829d\\u9ebb\\u9171","price":"0","status":"A","category_id":"2","created":"2016-10-15 02:48:28"},{"id":"29","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 15:38:26"},{"id":"30","cousine_id":"34","name":"-","name_zh":"-","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:16:01"},{"id":"31","cousine_id":"34","name":"Noodle Soft","name_zh":"\\u9762\\u8f6f","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:18"},{"id":"32","cousine_id":"34","name":"Noodle Hard","name_zh":"\\u9762\\u786c","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:37:50"},{"id":"33","cousine_id":"47","name":"Add noodle-Dine In","name_zh":"\\u52a0\\u9762-\\u5802\\u98df","price":"0","status":"A","category_id":"2","created":"2016-10-27 16:42:17"},{"id":"34","cousine_id":"34","name":"Add Noodle-Take out","name_zh":"\\u52a0\\u9762-\\u5916\\u5356","price":"2","status":"A","category_id":"2","created":"2016-10-27 16:42:24"},{"id":"55","cousine_id":"47","name":"Take out","name_zh":"\\u5916\\u5356","price":"0","status":"A","category_id":"2","created":"2016-10-29 15:03:35"}]', NULL, 'N', '2016-12-05 16:37:57', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(105) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(105) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('A','I') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A=Active, I=Inactive',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `body`, `slug`, `status`, `created`, `modified`) VALUES
(1, 'About Us', '<span style="color:#800080"><span style="font-size:24px"><u><strong>uterm</strong></u></span></span><br />\r\n<br />\r\nThis page is all about uterm.<br />\r\nUterm is the mobile application for customer and vendor.<br />\r\n<br />\r\nOnline order for wine made by customer to nearest wine dealer.<br />\r\n<br />\r\n<strong>User finds the nearest dealer using application.</strong><br />\r\n<br />\r\n<br />\r\nand many more....<br />\r\n<br />\r\n&nbsp;', 'about-us', 'A', '2016-04-30 10:18:25', '2016-04-30 10:27:45'),
(2, 'Support', '<span style="color:#FF0000"><span style="font-size:16px">This section is under construction !</span></span>', 'support', 'I', '2016-04-30 10:36:46', '2016-04-30 10:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int(11) UNSIGNED NOT NULL,
  `restaurant_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '0',
  `valid_from` varchar(255) NOT NULL DEFAULT '0',
  `valid_to` varchar(255) NOT NULL DEFAULT '0',
  `discount_type` tinyint(8) NOT NULL DEFAULT '0',
  `discount_value` varchar(50) NOT NULL DEFAULT '0',
  `is_multiple` tinyint(8) NOT NULL DEFAULT '0',
  `status` tinyint(8) NOT NULL DEFAULT '1',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`id`, `restaurant_id`, `code`, `valid_from`, `valid_to`, `discount_type`, `discount_value`, `is_multiple`, `status`, `created`, `modified`) VALUES
(7, 5, 'NEW', '2016-10-08', '2016-10-26', 1, '10', 1, 0, 1475828058, 1475915342),
(8, 5, '0new', '2016-11-14', '2016-11-24', 1, '10', 0, 1, 1479162310, 1479227170);

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
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_cashiers_admins` (`restaurant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_locales`
--
ALTER TABLE `category_locales`
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
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `category_locales`
--
ALTER TABLE `category_locales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `cooks`
--
ALTER TABLE `cooks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cousines`
--
ALTER TABLE `cousines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `cousine_locals`
--
ALTER TABLE `cousine_locals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `extrascategories`
--
ALTER TABLE `extrascategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
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
-- Constraints for table `extras`
--
ALTER TABLE `extras`
  ADD CONSTRAINT `FK_extras_cousines` FOREIGN KEY (`cousine_id`) REFERENCES `cousines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `FK_orders_items_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
