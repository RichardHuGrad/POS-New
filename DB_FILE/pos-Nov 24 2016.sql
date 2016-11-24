-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2016 at 11:01 PM
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
(5, 1, 'restaurant', 'panel', 'rest@pos.com', 'e10adc3949ba59abbe56e057f20f883e', 'N', '2016-06-30 08:31:12', '2016-11-16 17:48:13', 'A', '213131321', 'yahoo hsshshsh', 'Hiko Izakay', 13, 19, 9, 9, '12,5,4,6,5,2,4,5,4,5,1,2,4,2,2,6,6,6,7', '["position: absolute; left: 63.9477%; top: 0%;","position: absolute; left: 63.9984%; top: 19.4453%;","position: absolute; left: 64.0927%; top: 39.1016%;","position: absolute; left: 64.1555%; top: 59.25%;","position: absolute; left: 64.4029%; top: 78.125%;","position: absolute; left: 11.8796%; top: 0%;","position: absolute; left: 11.8735%; top: 19.7917%;","position: absolute; left: 11.8867%; top: 39.1667%;","position: absolute; left: 12.0185%; top: 59.375%;","position: absolute; left: 38.382%; top: 0%;","position: absolute; left: 31.9597%; top: 10.2083%;","position: absolute; left: 38.1225%; top: 24.1953%;","position: absolute; left: 32.3824%; top: 36.6641%;","position: absolute; left: 37.94%; top: 48.3229%;","position: absolute; left: 32.2374%; top: 63.3281%;","position: absolute; left: 38.1438%; top: 78.125%;","position: absolute; left: 0%; top: 78.125%;","position: absolute; left: 8.12044%; top: 78.125%;","position: absolute; left: 17.0022%; top: 78.125%;"]', '1,2,5,7,8,9,5,4,2', '8,5,6,8,2,2,2,3,2', 'Y', '192.168.192.168', 'device1');

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
(7, 5, 'cashier', '03', '1112223333', '03@pos.com', '96e79218965eb72c92a549dd5a330112', NULL, 'Y', 'A', '2016-10-29 14:56:25', '2016-10-29 14:56:25', '111111', '111111'),
(8, 5, 'Masood', 'Rahaman', '12345678', 'mash@mailinator.com', '827ccb0eea8a706c4c34a16891f84e7b', NULL, 'Y', 'A', '2016-11-16 12:49:38', '2016-11-16 17:50:16', '', '');

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
(10, 'A', 1479242764, 1479250716, 'K'),
(11, 'A', 1479242776, 1479250705, 'K'),
(12, 'A', 1479242791, 1479250828, 'K'),
(13, 'A', 1479242815, 1479250810, 'K'),
(14, 'A', 1479242849, 1479250799, 'K'),
(15, 'I', 1479242897, 1479250685, 'K'),
(16, 'I', 1479242910, 1479250789, 'K'),
(17, 'I', 1479242921, 1479250762, 'K'),
(18, 'A', 1479242932, 1479250751, 'K'),
(19, 'A', 1479326407, 1479326439, 'C'),
(20, 'A', 1479326428, 1479326450, 'C'),
(21, 'A', 1479326474, 1479326474, 'C'),
(22, 'A', 1479328183, 1479328183, 'C'),
(23, 'A', 1479328204, 1479328204, 'C');

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
(11, 10, 'Appetizer', 'en', 1479242764, 1479250716),
(12, 10, 'Appetizer', 'zh', 1479242764, 1479250716),
(13, 11, 'Deep Fried', 'en', 1479242776, 1479250705),
(14, 11, 'Deep Fried', 'zh', 1479242776, 1479250705),
(15, 12, 'Yakitori', 'en', 1479242791, 1479250828),
(16, 12, 'Yakitori', 'zh', 1479242791, 1479250828),
(17, 13, 'Oyster', 'en', 1479242815, 1479250810),
(18, 13, 'Oyster', 'zh', 1479242815, 1479250810),
(19, 14, 'Mains', 'en', 1479242849, 1479250799),
(20, 14, 'Mains', 'zh', 1479242849, 1479250799),
(21, 15, 'Grilled Fish', 'en', 1479242897, 1479250685),
(22, 15, 'Grilled Fish', 'zh', 1479242897, 1479250685),
(23, 16, 'Grilled Saba', 'en', 1479242910, 1479250789),
(24, 16, 'Grilled Saba', 'zh', 1479242910, 1479250789),
(25, 17, 'Grilled black cod', 'en', 1479242921, 1479250762),
(26, 17, 'Grilled black cod', 'zh', 1479242921, 1479250762),
(27, 18, 'Dessert', 'en', 1479242932, 1479250751),
(28, 18, 'Dessert', 'zh', 1479242932, 1479250751),
(29, 19, 'Cocktail', 'en', 1479326407, 1479326439),
(30, 19, 'Cocktail', 'zh', 1479326407, 1479326439),
(31, 20, 'Sake', 'en', 1479326428, 1479326450),
(32, 20, 'Sake', 'zh', 1479326428, 1479326450),
(33, 21, 'Warm', 'en', 1479326474, 1479326474),
(34, 21, 'Warm', 'zh', 1479326474, 1479326474),
(35, 22, 'Plum wine', 'en', 1479328183, 1479328183),
(36, 22, 'æ¢…é…’', 'zh', 1479328183, 1479328183),
(37, 23, 'Yuzu', 'en', 1479328204, 1479328204),
(38, 23, 'æŸšå­', 'zh', 1479328204, 1479328204);

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
(5, 5, '111', '222', ' 11111111', 'abc@hotmail.com', '50207fa2814e81a067bd2662ba10b0f1', 'Y', 'A', '2016-11-14 17:17:35', '2016-11-14 17:17:35'),
(6, 5, 'rahaman', 'mash', '11111111', 'masoodhur@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Y', 'A', '2016-11-16 14:01:33', '2016-11-16 14:01:33');

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
(68, 5, 0, 8.9, 10, NULL, 'A', 1479244272, 7, 'Y', 1479244272),
(69, 5, 0, 4.5, 10, NULL, 'A', 1479244295, 5, 'Y', 1479244295),
(70, 5, 0, 3.5, 10, NULL, 'A', 1479244315, 1, 'Y', 1479244315),
(71, 5, 0, 4.5, 10, NULL, 'A', 1479244335, 4, 'Y', 1479244335),
(72, 5, 0, 2.5, 10, NULL, 'A', 1479244358, 2, 'Y', 1479244358),
(73, 5, 0, 10.9, 10, NULL, 'A', 1479244378, 1, 'Y', 1479244378),
(74, 5, 0, 7.5, 10, NULL, 'A', 1479244402, 5, 'Y', 1479244402),
(75, 5, 0, 3.8, 10, NULL, 'A', 1479244422, 2, 'Y', 1479244422),
(76, 5, 0, 5.5, 11, NULL, 'A', 1479244448, 2, 'Y', 1479244448),
(77, 5, 0, 7.9, 11, NULL, 'A', 1479244473, 1, 'Y', 1479244473),
(78, 5, 0, 6.9, 11, NULL, 'A', 1479244494, 1, 'Y', 1479244494),
(79, 5, 0, 8.9, 11, NULL, 'A', 1479244516, 3, 'Y', 1479244516),
(80, 5, 0, 6, 11, NULL, 'A', 1479244541, 3, 'Y', 1479244541),
(81, 5, 0, 7.6, 11, NULL, 'A', 1479244564, 1, 'Y', 1479244564),
(82, 5, 0, 5.9, 11, NULL, 'A', 1479244595, 1, 'Y', 1479244595),
(83, 5, 0, 5.2, 11, NULL, 'A', 1479244618, 1, 'Y', 1479244618),
(84, 5, 0, 4.2, 11, NULL, 'A', 1479244642, 1, 'Y', 1479244642),
(85, 5, 0, 3, 12, NULL, 'A', 1479244686, 3, 'Y', 1479244686),
(86, 5, 0, 6, 12, NULL, 'A', 1479244705, 1, 'Y', 1479244705),
(87, 5, 0, 8, 12, NULL, 'A', 1479244728, 1, 'Y', 1479244728),
(88, 5, 0, 12.9, 12, NULL, 'A', 1479244750, 1, 'Y', 1479244750),
(89, 5, 0, 25, 12, NULL, 'A', 1479244772, 1, 'Y', 1479244772),
(90, 5, 0, 5.8, 12, NULL, 'A', 1479244794, 1, 'Y', 1479244794),
(91, 5, 0, 4.5, 12, NULL, 'A', 1479244817, 1, 'Y', 1479244817),
(92, 5, 0, 7.2, 13, NULL, 'A', 1479244843, 6, 'Y', 1479244843),
(93, 5, 0, 8.8, 13, NULL, 'A', 1479244865, 1, 'Y', 1479244865),
(94, 5, 0, 9, 14, NULL, 'A', 1479244897, 3, 'Y', 1479244897),
(95, 5, 0, 24, 14, NULL, 'A', 1479244919, 4, 'Y', 1479244919),
(96, 5, 0, 9.5, 14, NULL, 'A', 1479244942, 1, 'Y', 1479248851),
(97, 5, 0, 10, 14, NULL, 'A', 1479244971, 3, 'Y', 1479324483),
(98, 5, 0, 3.5, 18, NULL, 'A', 1479245003, 6, 'Y', 1479245003),
(99, 5, 0, 5.5, 18, NULL, 'A', 1479245026, 1, 'Y', 1479245026),
(100, 5, 0, 5.5, 18, NULL, 'A', 1479245057, 1, 'Y', 1479245057),
(101, 5, 0, 5.2, 18, NULL, 'A', 1479245091, 1, 'Y', 1479245091),
(102, 5, 0, 9.5, 14, NULL, 'A', 1479246132, 4, 'Y', 1479248676),
(103, 5, 0, 10.5, 14, NULL, 'A', 1479246766, 1, 'Y', 1479248926),
(104, 5, 0, 8.5, 14, NULL, 'A', 1479248980, 1, 'Y', 1479248980),
(105, 5, 0, 9.9, 14, NULL, 'A', 1479249023, 1, 'Y', 1479249023),
(106, 5, 0, 9.9, 14, NULL, 'A', 1479249063, 1, 'Y', 1479249063),
(107, 5, 0, 9.2, 14, NULL, 'A', 1479249100, 1, 'Y', 1479249100),
(108, 5, 0, 8.5, 14, NULL, 'A', 1479249140, 2, 'Y', 1479249140),
(109, 5, 0, 14.9, 14, NULL, 'A', 1479249263, 1, 'Y', 1479249263),
(110, 5, 0, 8.9, 14, NULL, 'A', 1479249330, 1, 'Y', 1479249330),
(111, 5, 0, 22, 14, NULL, 'A', 1479249374, 1, 'Y', 1479249374),
(112, 5, 0, 9.9, 11, NULL, 'A', 1479249426, 1, 'Y', 1479249426),
(113, 5, 0, 10.9, 14, NULL, 'A', 1479249486, 1, 'Y', 1479249486),
(114, 5, 0, 10.9, 14, NULL, 'A', 1479249533, 1, 'Y', 1479249533),
(115, 5, 0, 9.9, 14, NULL, 'A', 1479249567, 1, 'Y', 1479249567),
(116, 5, 0, 9.5, 14, NULL, 'A', 1479249602, 1, 'Y', 1479249602),
(117, 5, 0, 8.9, 14, NULL, 'A', 1479249635, 1, 'Y', 1479249635),
(118, 5, 0, 9.9, 14, NULL, 'A', 1479249674, 1, 'Y', 1479249674),
(120, 5, 0, 6, 19, NULL, 'A', 1479326543, 0, 'Y', 1479326543),
(121, 5, 0, 6, 19, NULL, 'A', 1479326574, 0, 'Y', 1479326574),
(122, 5, 0, 6, 19, NULL, 'A', 1479326597, 0, 'Y', 1479326597),
(123, 5, 0, 6.5, 19, NULL, 'A', 1479326622, 0, 'Y', 1479326622),
(124, 5, 0, 6.7, 19, NULL, 'A', 1479326646, 0, 'Y', 1479326646),
(125, 5, 0, 6, 19, NULL, 'A', 1479326670, 0, 'Y', 1479326670),
(126, 5, 0, 6.2, 19, NULL, 'A', 1479326752, 0, 'Y', 1479326752),
(127, 5, 0, 6.2, 19, NULL, 'A', 1479326783, 0, 'Y', 1479326783),
(128, 5, 0, 6.2, 19, NULL, 'A', 1479326808, 0, 'Y', 1479326808),
(129, 5, 0, 27, 20, NULL, 'A', 1479326884, 0, 'Y', 1479327960),
(130, 5, 0, 57, 20, NULL, 'A', 1479326915, 0, 'Y', 1479328031),
(131, 5, 0, 29, 20, NULL, 'A', 1479326941, 0, 'Y', 1479327789),
(132, 5, 0, 67, 20, NULL, 'A', 1479326967, 0, 'Y', 1479327887),
(133, 5, 0, 27, 20, NULL, 'A', 1479326990, 0, 'Y', 1479328058),
(134, 5, 0, 39, 20, NULL, 'A', 1479327015, 0, 'Y', 1479327761),
(135, 5, 0, 77, 20, NULL, 'A', 1479327055, 0, 'Y', 1479327859),
(136, 5, 0, 45, 20, NULL, 'A', 1479327081, 0, 'Y', 1479328532),
(137, 5, 0, 85, 20, NULL, 'A', 1479327115, 0, 'Y', 1479328623),
(138, 5, 0, 59, 20, NULL, 'A', 1479327194, 0, 'Y', 1479328092),
(139, 5, 0, 42, 20, NULL, 'A', 1479327234, 0, 'Y', 1479327737),
(140, 5, 0, 85, 20, NULL, 'A', 1479327342, 0, 'Y', 1479327342),
(141, 5, 0, 43, 20, NULL, 'A', 1479327391, 0, 'Y', 1479329047),
(142, 5, 0, 49, 20, NULL, 'A', 1479327436, 0, 'Y', 1479327436),
(143, 5, 0, 105, 20, NULL, 'A', 1479327478, 0, 'Y', 1479327478),
(144, 5, 0, 20, 20, NULL, 'A', 1479327531, 0, 'Y', 1479327834),
(146, 5, 0, 12, 21, NULL, 'A', 1479327680, 0, 'Y', 1479327680),
(147, 5, 0, 69, 22, NULL, 'A', 1479328240, 0, 'Y', 1479328240),
(148, 5, 0, 39, 22, NULL, 'A', 1479328262, 0, 'Y', 1479328262),
(149, 5, 0, 65, 23, NULL, 'A', 1479328297, 0, 'Y', 1479328297),
(150, 5, 0, 105, 20, NULL, 'A', 1479329123, 0, 'Y', 1479329123);

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
(131, 68, 'Kaidou Salad ', 'en', 1479244272, 1479244272),
(132, 68, 'Kaidou Salad ', 'zh', 1479244272, 1479244272),
(133, 69, 'Wakame Salad ', 'en', 1479244295, 1479244295),
(134, 69, 'Wakame Salad ', 'zh', 1479244295, 1479244295),
(135, 70, 'Edamame', 'en', 1479244315, 1479244315),
(136, 70, 'Edamame', 'zh', 1479244315, 1479244315),
(137, 71, 'Takowasa', 'en', 1479244335, 1479244335),
(138, 71, 'Takowasa', 'zh', 1479244335, 1479244335),
(139, 72, 'Miso Soup ', 'en', 1479244358, 1479244358),
(140, 72, 'Miso Soup ', 'zh', 1479244358, 1479244358),
(141, 73, 'Sashimi Salad ', 'en', 1479244378, 1479244378),
(142, 73, 'Sashimi Salad ', 'zh', 1479244378, 1479244378),
(143, 74, 'Tamagoyaki', 'en', 1479244402, 1479244402),
(144, 74, 'Tamagoyaki', 'zh', 1479244402, 1479244402),
(145, 75, 'Tamago tofu ', 'en', 1479244422, 1479244422),
(146, 75, 'Tamago tofu ', 'zh', 1479244422, 1479244422),
(147, 76, 'Takoyaki', 'en', 1479244448, 1479244448),
(148, 76, 'Takoyaki', 'zh', 1479244448, 1479244448),
(149, 77, 'Cheese Fry ', 'en', 1479244473, 1479244473),
(150, 77, 'Cheese Fry ', 'zh', 1479244473, 1479244473),
(151, 78, 'Chichen Karaage ', 'en', 1479244494, 1479244494),
(152, 78, 'Chichen Karaage ', 'zh', 1479244494, 1479244494),
(153, 79, 'Ebi Mayo ', 'en', 1479244516, 1479244516),
(154, 79, 'Ebi Mayo ', 'zh', 1479244516, 1479244516),
(155, 80, 'Sweet Potato Fry ', 'en', 1479244541, 1479244541),
(156, 80, 'Sweet Potato Fry ', 'zh', 1479244541, 1479244541),
(157, 81, 'Chicken Wings ', 'en', 1479244564, 1479244564),
(158, 81, 'Chicken Wings ', 'zh', 1479244564, 1479244564),
(159, 82, 'Agedashi Tofu ', 'en', 1479244595, 1479244595),
(160, 82, 'Agedashi Tofu ', 'zh', 1479244595, 1479244595),
(161, 83, 'Corn Cream Corquette ', 'en', 1479244618, 1479244618),
(162, 83, 'Corn Cream Corquette ', 'zh', 1479244618, 1479244618),
(163, 84, 'Mentaiko Mochi Cheese ', 'en', 1479244642, 1479244642),
(164, 84, 'Mentaiko Mochi Cheese ', 'zh', 1479244642, 1479244642),
(165, 85, 'Shrimp', 'en', 1479244686, 1479244686),
(166, 85, 'Shrimp', 'zh', 1479244686, 1479244686),
(167, 86, 'Unagi ', 'en', 1479244705, 1479244705),
(168, 86, 'Unagi ', 'zh', 1479244705, 1479244705),
(169, 87, 'Scallop ', 'en', 1479244728, 1479244728),
(170, 87, 'Scallop ', 'zh', 1479244728, 1479244728),
(171, 88, 'Salmom oshi-Sushi ', 'en', 1479244750, 1479244750),
(172, 88, 'Salmom oshi-Sushi ', 'zh', 1479244750, 1479244750),
(173, 89, 'Unagi Oshi-sushi ', 'en', 1479244772, 1479244772),
(174, 89, 'Unagi Oshi-sushi ', 'zh', 1479244772, 1479244772),
(175, 90, 'Tempura Set ', 'en', 1479244794, 1479244794),
(176, 90, 'Tempura Set ', 'zh', 1479244794, 1479244794),
(177, 91, 'Garlic Butter Scallops ', 'en', 1479244817, 1479244817),
(178, 91, 'Garlic Butter Scallops ', 'zh', 1479244817, 1479244817),
(179, 92, 'Hiro Special Oyster ', 'en', 1479244843, 1479244843),
(180, 92, 'Hiro Special Oyster ', 'zh', 1479244843, 1479244843),
(181, 93, 'Baked Oyster With Cheese ', 'en', 1479244865, 1479244865),
(182, 93, 'Baked Oyster With Cheese ', 'zh', 1479244865, 1479244865),
(183, 94, 'Yaki Udon ', 'en', 1479244897, 1479244897),
(184, 94, 'Yaki Udon ', 'zh', 1479244897, 1479244897),
(185, 95, 'Unagi Don ', 'en', 1479244919, 1479244919),
(186, 95, 'Unagi Don ', 'zh', 1479244919, 1479244919),
(187, 96, 'ã‚«ãƒ„ä¸¼ ', 'en', 1479244942, 1479248851),
(188, 96, 'Katsu Don ', 'zh', 1479244942, 1479248851),
(189, 97, 'Gyu-Don', 'en', 1479244971, 1479324483),
(190, 97, 'Gyu-Don', 'zh', 1479244971, 1479324483),
(191, 98, 'Ice Cream ', 'en', 1479245003, 1479245003),
(192, 98, 'Ice Cream ', 'zh', 1479245003, 1479245003),
(193, 99, 'Mochi ice cream ', 'en', 1479245026, 1479245026),
(194, 99, 'Mochi ice cream ', 'zh', 1479245026, 1479245026),
(195, 100, 'Cheese cake ', 'en', 1479245057, 1479245057),
(196, 100, 'Cheese cake ', 'zh', 1479245057, 1479245057),
(197, 101, 'Cream brute ', 'en', 1479245091, 1479245091),
(198, 101, 'Cream brute ', 'zh', 1479245091, 1479245091),
(199, 102, 'ãƒãƒ£ãƒ¼ã‚·ãƒ¥ãƒ¼ä¸¼  (extra 2 pcs $3.50)', 'en', 1479246132, 1479248676),
(200, 102, 'Pork Cha-shu Don (extra 2 pcs $3.50)', 'zh', 1479246132, 1479248676),
(201, 103, 'ã‚«ãƒ„ã‚«ãƒ¬ãƒ¼ ', 'en', 1479246766, 1479248926),
(202, 103, 'Katsu Curry', 'zh', 1479246766, 1479248926),
(203, 104, 'é†¤æ²¹ãƒ©ãƒ¼ãƒ¡ãƒ³ ', 'en', 1479248980, 1479248980),
(204, 104, 'Soy Sauce Ramen', 'zh', 1479248980, 1479248980),
(205, 105, 'å‘³å™Œãƒ©ãƒ¼ãƒ¡ãƒ³', 'en', 1479249023, 1479249023),
(206, 105, 'Miso Ramen', 'zh', 1479249023, 1479249023),
(207, 106, 'è±šéª¨ãƒ©ãƒ¼ãƒ¡ãƒ³ ', 'en', 1479249063, 1479249063),
(208, 106, 'Pork Bone Ramen', 'zh', 1479249063, 1479249063),
(209, 107, 'å¤©ã·ã‚‰ã†ã©ã‚“ ', 'en', 1479249100, 1479249100),
(210, 107, 'Tempura Udon', 'zh', 1479249100, 1479249100),
(211, 108, 'ã‚ã‹ã‚ã†ã©ã‚“ ', 'en', 1479249140, 1479249140),
(212, 108, 'Seaweed Udon', 'zh', 1479249140, 1479249140),
(213, 109, 'ã‚µãƒ¼ãƒ¢ãƒ³ï¼†ãƒžã‚°ãƒ­ä¸¼ ', 'en', 1479249263, 1479249263),
(214, 109, 'Salmon&Tuna Don', 'zh', 1479249263, 1479249263),
(215, 110, 'ã‚µãƒ¼ãƒ¢ãƒ³ï¼†ã‚¢ãƒœã‚«ãƒ‰ä¸¼ ', 'en', 1479249330, 1479249330),
(216, 110, 'Salmon&Avocado Don', 'zh', 1479249330, 1479249330),
(217, 111, 'ã†ãªé‡ ', 'en', 1479249374, 1479249374),
(218, 111, 'Grilled Eel Don', 'zh', 1479249374, 1479249374),
(219, 112, 'ã‚¨ãƒ“ãƒ•ãƒ©ã‚¤ã‚«ãƒ¬ãƒ¼ ', 'en', 1479249426, 1479249426),
(220, 112, 'Deep Fry Shrimp with Curry', 'zh', 1479249426, 1479249426),
(221, 113, 'åˆºèº«ã‚µãƒ©ãƒ€ ', 'en', 1479249486, 1479249486),
(222, 113, 'Sashimi Salda', 'zh', 1479249486, 1479249486),
(223, 114, 'ãƒã‚­ãƒ³å”æšã’ã‚«ãƒ¬ãƒ¼ ', 'en', 1479249533, 1479249533),
(224, 114, 'Chicken Karaage Curry', 'zh', 1479249533, 1479249533),
(225, 115, 'ç…§ã‚Šç„¼ããƒã‚­ãƒ³ ', 'en', 1479249567, 1479249567),
(226, 115, 'Teriyaki Chicken', 'zh', 1479249567, 1479249567),
(227, 116, 'ã‚¨ãƒ“å¤©ä¸¼ ', 'en', 1479249602, 1479249602),
(228, 116, 'Shrimp Tempura Don', 'zh', 1479249602, 1479249602),
(229, 117, 'é‡Žèœå¤©ã·ã‚‰ä¸¼ ', 'en', 1479249635, 1479249635),
(230, 117, 'Veg Tempura Don', 'zh', 1479249635, 1479249635),
(231, 118, 'ç‰›ä¸¼ ', 'en', 1479249674, 1479249674),
(232, 118, 'Sukiyaki beef Don', 'zh', 1479249674, 1479249674),
(233, 119, 'Grilled fish Bonaza', 'en', 1479319710, 1479319710),
(234, 119, 'wee', 'zh', 1479319710, 1479319710),
(235, 120, 'Cassis orange (cassis 30ml and Orange juice 120 ml) ', 'en', 1479326543, 1479326543),
(236, 120, 'Cassis orange (cassis 30ml and Orange juice 120 ml) ', 'zh', 1479326543, 1479326543),
(237, 121, 'Cassis oolong (cassis 30ml and oolong tea 100 ml) ', 'en', 1479326574, 1479326574),
(238, 121, 'Cassis oolong (cassis 30ml and oolong tea 100 ml) ', 'zh', 1479326574, 1479326574),
(239, 122, 'Cassis Soda (cassis 45ml and soda 105 ml) ', 'en', 1479326597, 1479326597),
(240, 122, 'Cassis Soda (cassis 45ml and soda 105 ml) ', 'zh', 1479326597, 1479326597),
(241, 123, 'kahlua Milk (kahlua 40ml and milk 120 ml) ', 'en', 1479326622, 1479326622),
(242, 123, 'kahlua Milk (kahlua 40ml and milk 120 ml) ', 'zh', 1479326622, 1479326622),
(243, 124, 'Green tea Milk ', 'en', 1479326646, 1479326646),
(244, 124, 'Green tea Milk ', 'zh', 1479326646, 1479326646),
(245, 125, 'Shandy Gaff (beer 50% and Canada dry 50%) ', 'en', 1479326670, 1479326670),
(246, 125, 'Shandy Gaff (beer 50% and Canada dry 50%) ', 'zh', 1479326670, 1479326670),
(247, 126, 'Gin tonic (gin 45 ml , tonic water 100ml and lime)', 'en', 1479326752, 1479326752),
(248, 126, 'Gin tonic (gin 45 ml , tonic water 100ml and lime)', 'zh', 1479326752, 1479326752),
(249, 127, 'Mojito mint (rum 45 ml , soda 120ml , sugar 15-30g ,mint and lime)', 'en', 1479326783, 1479326783),
(250, 127, 'Mojito mint (rum 45 ml , soda 120ml , sugar 15-30g ,mint and lime)', 'zh', 1479326783, 1479326783),
(251, 128, 'Calpis cassis soda (calpis 15ml,cassis 30ml and soda 100ml ) ', 'en', 1479326808, 1479326808),
(252, 128, 'Calpis cassis soda (calpis 15ml,cassis 30ml and soda 100ml ) ', 'zh', 1479326808, 1479326808),
(253, 129, 'Izumi nama-nama 300ml', 'en', 1479326884, 1479327960),
(254, 129, 'æ³‰ nama-nama 300ml', 'zh', 1479326884, 1479327960),
(255, 130, 'Izumi nama-nama 750ml', 'en', 1479326915, 1479328031),
(256, 130, 'æ³‰ nama-nama 750ml', 'zh', 1479326915, 1479328031),
(257, 131, 'Izumi genshu 300ml', 'en', 1479326941, 1479327789),
(258, 131, 'æ³‰ genshu 300ml', 'zh', 1479326941, 1479327789),
(259, 132, 'Izumi genshu 750ml', 'en', 1479326967, 1479327887),
(260, 132, 'æ³‰ genshu 750ml', 'zh', 1479326967, 1479327887),
(261, 133, 'Izumi Nigori 300ml ', 'en', 1479326990, 1479328058),
(262, 133, 'æ³‰ Nigori 300ml ', 'zh', 1479326990, 1479328058),
(263, 134, 'Izumi Arabashir 375ml', 'en', 1479327015, 1479327761),
(264, 134, 'æ³‰ Arabashir 375ml', 'zh', 1479327015, 1479327761),
(265, 135, 'Izumi Arabashir 750ml', 'en', 1479327055, 1479327859),
(266, 135, 'æ³‰ Arabashir 750ml', 'zh', 1479327055, 1479327859),
(267, 136, 'onikoroshi junmai ginjo 300ml', 'en', 1479327081, 1479328532),
(268, 136, 'é¬¼ã“ã‚ã— junmai ginjo 300ml', 'zh', 1479327081, 1479328532),
(269, 137, 'onikoroshi junmai ginjo 720ml', 'en', 1479327115, 1479328623),
(270, 137, 'é¬¼ã“ã‚ã— junmai ginjo 720ml', 'zh', 1479327115, 1479328623),
(271, 138, 'otokoyama junmai 500ml ', 'en', 1479327194, 1479328092),
(272, 138, 'ç”·å±± junmai 500ml ', 'zh', 1479327194, 1479328092),
(273, 139, 'hakkaisan tokubetsu honjozo 300ml', 'en', 1479327234, 1479327737),
(274, 139, 'å…«æµ·å±± tokubetsu honjozo 300ml', 'zh', 1479327234, 1479327737),
(275, 140, 'hakkaisan tokubetsu honjozo 720ml', 'en', 1479327342, 1479327342),
(276, 140, 'å…«æµ·å±± tokubetsu honjozo 720ml', 'zh', 1479327342, 1479327342),
(277, 141, 'hakkaisan dai ginjo 300ml', 'en', 1479327391, 1479329047),
(278, 141, 'å…«æµ·å±± dai ginjo 300ml', 'zh', 1479327391, 1479329047),
(279, 142, 'nanbu bijin junmai ginjo 300ml', 'en', 1479327436, 1479327436),
(280, 142, 'å—éƒ¨ç¾Žäºº junmai ginjo 300ml', 'zh', 1479327436, 1479327436),
(281, 143, 'nanbu bijin junmai ginjo 720ml', 'en', 1479327478, 1479327478),
(282, 143, 'å—éƒ¨ç¾Žäºº junmai ginjo 720ml', 'zh', 1479327478, 1479327478),
(283, 144, 'sho-chiku-bai Nigori 375ml ', 'en', 1479327531, 1479327834),
(284, 144, 'æ¾ç«¹æ¢… Nigori 375ml ', 'zh', 1479327531, 1479327834),
(285, 145, 'æ¾ç«¹æ¢… sake bottle glass ', 'en', 1479327583, 1479327583),
(286, 145, 'æ¾ç«¹æ¢… sake bottle glass ', 'zh', 1479327583, 1479327583),
(287, 146, 'sake bottle glass ', 'en', 1479327680, 1479327680),
(288, 146, 'æ¾ç«¹æ¢… ', 'zh', 1479327680, 1479327680),
(289, 147, 'cho-ya 700ml ', 'en', 1479328240, 1479328240),
(290, 147, 'cho-ya 700ml ', 'zh', 1479328240, 1479328240),
(291, 148, 'Takara 750ml ', 'en', 1479328262, 1479328262),
(292, 148, 'Takara 750ml ', 'zh', 1479328262, 1479328262),
(293, 149, '720 ml ', 'en', 1479328297, 1479328297),
(294, 149, '720 ml ', 'zh', 1479328297, 1479328297),
(295, 150, 'hakkaisan dai ginjo 720ml ', 'en', 1479329123, 1479329123),
(296, 150, 'å…«æµ·å±± dai ginjo 720ml ', 'zh', 1479329123, 1479329123);

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
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `extras`
--

INSERT INTO `extras` (`id`, `cousine_id`, `name`, `name_zh`, `price`, `status`, `created`) VALUES
(56, 103, 'extra egg', 'extra egg', 2, 'A', '2016-11-15 16:43:28'),
(57, 103, 'extra cha-shu', 'extra cha-shu', 3.5, 'A', '2016-11-15 17:07:04');

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
  `discount_value` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `reorder_no`, `hide_no`, `cashier_id`, `counter_id`, `table_no`, `table_status`, `tax`, `tax_amount`, `subtotal`, `total`, `card_val`, `cash_val`, `tip`, `tip_paid_by`, `paid`, `change`, `promocode`, `message`, `reason`, `order_type`, `is_kitchen`, `cooking_status`, `is_hide`, `created`, `is_completed`, `paid_by`, `fix_discount`, `percent_discount`, `discount_value`) VALUES
(184, '98184', '0', 8, 5, 3, 6, 'N', 13, 0.455, 3.5, 3.95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'D', 'N', 'UNCOOKED', 'Y', '2016-11-15 16:36:01', 'Y', NULL, NULL, NULL, NULL),
(185, '93185', '0', 7, 5, 3, 6, 'P', 13, 2.405, 24, 26.405, 26.41, NULL, 2, 'CARD', 26.41, 0, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'Y', '2016-11-15 16:44:17', 'Y', 'CARD', NULL, NULL, NULL),
(186, '90186', '0', 6, 5, 3, 7, 'P', 13, 4.888, 43.1, 47.988, 47.99, NULL, 2, 'CARD', 47.99, 0, NULL, '', '', 'D', 'Y', 'UNCOOKED', 'Y', '2016-11-15 18:06:14', 'Y', 'CARD', NULL, NULL, NULL),
(187, '91187', '0', 5, 5, 3, 1, 'P', 13, 1.17, 9, 9.153, NULL, 9.15, 1.5, 'CASH', 9.15, 0, '', '', NULL, 'T', 'Y', 'UNCOOKED', 'Y', '2016-11-16 10:19:55', 'Y', 'CASH', 0, 10, 1.017),
(188, '92188', '0', 4, 5, 3, 1, 'P', 13, 1.157, 8.9, 10.057, 10.06, NULL, 1, 'CARD', 10.06, 0, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'Y', '2016-11-16 10:26:36', 'Y', 'CARD', NULL, NULL, NULL),
(189, '93189', '0', 3, 5, 3, 6, 'P', 13, 3.809, 29.3, 33.109, NULL, 40, NULL, NULL, 40, 6.89, '', '', NULL, 'D', 'Y', 'UNCOOKED', 'Y', '2016-11-16 11:10:13', 'Y', 'CASH', 0, 0, 0),
(190, '95190', '0', 2, 5, 3, 2, 'P', 13, 57.109, 439.3, 446.768, 1000, NULL, 50, 'CARD', 1000, 553.23, '', '', NULL, 'D', 'Y', 'UNCOOKED', 'Y', '2016-11-16 11:42:08', 'Y', 'CARD', 0, 10, 49.6409),
(191, '93191', '0', 1, 5, 3, 3, 'P', 13, 0.819, 6.3, 7.119, NULL, 44.44, 88.88, 'CASH', 44.44, 37.32, NULL, '', NULL, 'T', 'Y', 'UNCOOKED', 'Y', '2016-11-16 12:14:28', 'Y', 'CASH', NULL, NULL, NULL),
(192, '88192', '0', 0, 5, 3, 8, 'P', 13, 1.742, 13.4, 15.142, NULL, 455.56, NULL, 'CASH', 455.56, 440.42, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 12:34:38', 'Y', 'CASH', NULL, NULL, NULL),
(193, '94193', '0', 0, 5, 3, 3, 'P', 13, 2.717, 20.9, 18.617, NULL, 20, NULL, 'CASH', 20, 1.38, '123', '', NULL, 'T', 'Y', 'UNCOOKED', 'P', '2016-11-16 13:05:25', 'Y', 'CASH', 5, 0, 5),
(194, '95194', '0', 0, 5, 3, 9, 'P', 13, 6.071, 50.2, 56.271, NULL, 80, 5, 'CASH', 80, 28.73, '', '', '', 'D', 'Y', 'COOKED', 'P', '2016-11-16 14:10:33', 'Y', 'CASH', 0, 0, 0),
(195, '91195', '0', 0, 5, 3, 10, 'P', 13, 1.3, 12, 13.3, NULL, 13.3, NULL, NULL, 13.3, 0, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 14:26:05', 'Y', 'CASH', NULL, NULL, NULL),
(196, '89196', '0', 0, 5, 3, 16, 'N', 13, 0.39, 3, 3.39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-16 14:55:19', 'Y', NULL, NULL, NULL, NULL),
(198, '89198', '0', 0, 5, 3, 7, 'P', 13, 2.327, 17.9, 20.227, 80.23, NULL, 60, 'CARD', 80.23, 60, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 15:21:29', 'Y', 'CARD', NULL, NULL, NULL),
(200, '94200', '0', 0, 5, 3, 11, 'P', 13, 3.848, 29.6, 33.448, NULL, 50, NULL, 'CASH', 50, 16.55, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 16:44:00', 'Y', 'CASH', NULL, NULL, NULL),
(201, '98201', '0', 0, 5, 3, 1, 'N', 13, 1.157, 8.9, 10.057, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'W', 'N', 'UNCOOKED', 'P', '2016-11-16 16:58:23', 'N', NULL, 0, 0, 0),
(202, '97202', '0', 0, 5, 3, 5, 'P', 13, 1.95, 15, 16.235, NULL, 20, NULL, 'CASH', 20, 3.77, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 17:08:58', 'Y', 'CASH', NULL, NULL, NULL),
(203, '97203', '0', 0, 5, 3, 11, 'N', 13, 0, 4, 8, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-16 17:51:32', 'Y', NULL, 0, 0, 0),
(204, '97199_204', '0', 0, 5, 3, 6, 'N', 13, 6.30231, 75.63, 81.93, 100, NULL, NULL, NULL, 100, 18.07, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 18:02:33', 'Y', 'CARD', NULL, NULL, NULL),
(205, '97199_205', '0', 0, 5, 3, 6, 'N', 13, 0.217692, 2.61, 2.83, 30, NULL, NULL, NULL, 30, 27.17, NULL, '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 18:02:51', 'Y', 'CARD', NULL, NULL, NULL),
(206, '96197_206', '0', 0, 5, 3, 8, 'N', 13, 1.16462, 13.98, 15.14, 100, NULL, NULL, NULL, 100, 84.86, '', '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 18:04:29', 'Y', 'CARD', NULL, NULL, 0),
(207, '96197_207', '0', 0, 5, 3, 8, 'N', 13, 0.721538, 8.66, 9.38, 200, NULL, 20, 'CASH', 200, 190.62, '', '', NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-16 18:04:54', 'Y', 'CARD', NULL, NULL, 0),
(208, '95208', '0', 0, 5, 3, 13, 'N', 13, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-17 11:12:33', 'Y', NULL, 0, 0, 0),
(209, '90209', '0', 0, 5, 3, 11, 'N', 13, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 14:53:02', 'Y', NULL, 0, 0, 0),
(210, '89210', '0', 0, 5, 3, 11, 'N', 13, 0, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 14:56:18', 'Y', NULL, 0, 0, 0),
(211, '89211', '0', 0, 5, 3, 11, 'N', 13, 0, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 14:58:45', 'Y', NULL, 0, 0, 0),
(212, '92212', '0', 0, 5, 3, 11, 'N', 13, 1.3, 10, 11.3, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 15:02:22', 'Y', NULL, 0, 0, 0),
(213, '91213', '0', 0, 5, 3, 12, 'N', 13, 1.25302, 9.63858, 10.8916, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 15:59:19', 'Y', NULL, 0, 0, 0),
(214, '94214', '0', 0, 5, 3, 16, 'N', 13, 1.3, 10, 11.3, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 16:09:17', 'N', NULL, 0, 0, 0),
(215, '91215', '0', 0, 5, 3, 12, 'P', 13, 0.702, 5.4, 6.102, NULL, 20, NULL, NULL, 13.902, 7.8, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-18 16:09:38', 'Y', 'CASH', 0, 10, 0.6),
(216, '94216', '0', 0, 5, 3, 2, 'N', 13, 2.327, 17.9, 20.227, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 16:18:59', 'Y', NULL, 0, 0, 0),
(217, '88217', '0', 0, 5, 3, 3, 'P', 13, 0.702, 5.4, 6.102, -215, -215, -215, NULL, 6.102, 0, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-18 17:24:18', 'Y', 'CASH', 0, 10, 0.6),
(218, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 17:40:28', 'N', NULL, 0, 0, 0),
(219, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 17:44:14', 'N', NULL, 0, 0, 0),
(220, '89220', '0', 0, 5, 3, 11, 'N', 13, 2.951, 22.7, 25.651, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 18:03:19', 'Y', NULL, 0, 0, 0),
(221, '87221', '0', 0, 5, 3, 12, 'N', 13, 0.715, 5.5, 6.215, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'D', 'N', 'UNCOOKED', 'P', '2016-11-18 18:03:36', 'Y', NULL, NULL, NULL, NULL),
(222, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 18:04:51', 'N', NULL, 0, 0, 0),
(223, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 21:43:27', 'N', NULL, 0, 0, 0),
(224, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 21:45:20', 'N', NULL, 0, 0, 0),
(225, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 21:50:37', 'N', NULL, 0, 0, 0),
(226, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:01:53', 'N', NULL, 0, 0, 0),
(227, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:02:43', 'N', NULL, 0, 0, 0),
(228, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:02:57', 'N', NULL, 0, 0, 0),
(229, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:31:01', 'N', NULL, 0, 0, 0),
(230, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:32:46', 'N', NULL, 0, 0, 0),
(231, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:32:55', 'N', NULL, 0, 0, 0),
(232, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-18 22:33:11', 'N', NULL, 0, 0, 0),
(234, '96233_234', '0', 0, 5, 3, 11, 'N', 13, 0.695385, 8.34, 9.04, NULL, 10, NULL, NULL, 10, 0.96, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:43:15', 'Y', 'CASH', NULL, NULL, 6.8),
(235, '96233_235', '0', 0, 5, 3, 11, 'N', 13, 1.66923, 20.03, 21.7, NULL, 30, NULL, NULL, 30, 8.3, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:43:44', 'Y', 'CASH', NULL, NULL, 6.8),
(237, '98236_237', '0', 0, 5, 3, 11, 'N', 13, 0.695385, 8.34, 9.04, NULL, 10, NULL, NULL, 10, 0.96, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:50:53', 'Y', 'CASH', NULL, NULL, 6.8),
(238, '98236_238', '0', 0, 5, 3, 11, 'N', 13, 1.66923, 20.03, 21.7, 30, NULL, NULL, NULL, 30, 8.3, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:51:13', 'Y', 'CARD', NULL, NULL, 6.8),
(240, '88239_240', '0', 0, 5, 3, 11, 'N', 13, 0.695385, 8.34, 9.04, NULL, 10, NULL, NULL, 10, 0.96, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:53:48', 'Y', 'CASH', NULL, NULL, 6.8),
(241, '88239_241', '0', 0, 5, 3, 11, 'N', 13, 1.66923, 20.03, 21.7, NULL, 200, NULL, NULL, 200, 178.3, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 00:54:07', 'Y', 'CASH', NULL, NULL, 6.8),
(243, '91242_243', '0', 0, 5, 3, 11, 'N', 13, 1.1752, 9.04, 10.2152, NULL, 20, NULL, NULL, 20, 10.96, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 01:03:16', 'Y', 'CASH', NULL, NULL, 2),
(244, '91242_244', '0', 0, 5, 3, 11, 'N', 13, 2.821, 21.7, 24.521, NULL, 25.5, NULL, NULL, 25.5, 3.8, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-19 01:03:46', 'Y', 'CASH', NULL, NULL, 4.8),
(246, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-19 12:33:52', 'N', NULL, 0, 0, 0),
(247, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-19 12:34:13', 'N', NULL, 0, 0, 0),
(248, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-19 12:35:24', 'N', NULL, 0, 0, 0),
(249, '0', '0', 0, NULL, NULL, NULL, 'N', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'N', 'UNCOOKED', 'P', '2016-11-19 12:35:42', 'N', NULL, 0, 0, 0),
(250, '97245_250', '0', 0, 5, 3, 11, 'N', 13, 1.4547, 11.19, 12.6447, NULL, 20, NULL, NULL, 20, 8.81, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-21 12:38:22', 'Y', 'CASH', NULL, NULL, 0),
(251, '97245_251', '0', 0, 5, 3, 11, 'N', 13, 2.9237, 22.49, 25.4137, NULL, 30, NULL, NULL, 30, 7.51, '', NULL, NULL, 'D', 'Y', 'UNCOOKED', 'P', '2016-11-21 12:38:40', 'Y', 'CASH', NULL, NULL, 0);

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
(1004, 184, 98, 'Ice Cream ', 'Ice Cream ', 18, 3.5, 1, 13, 0.455, NULL, '', NULL, 'N', '2016-11-15 16:36:01', 'N', 'N'),
(1008, 185, 94, 'Yaki Udon ', 'Yaki Udon ', 14, 9, 1, 13, 1.17, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:03:36', 'Y', 'N'),
(1009, 185, 102, 'ãƒãƒ£ãƒ¼ã‚·ãƒ¥ãƒ¼ä¸¼  (extra 2 pcs $3.50)', 'Pork Cha-shu Don (extra 2 pcs $3.50)', 14, 9.5, 1, 13, 1.235, '[{"id":"56","price":"2","name":"extra egg"},{"id":"57","price":"3.5","name":"extra cha-shu"}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 5.5, 'N', '2016-11-15 18:03:40', 'Y', 'N'),
(1010, 186, 102, 'ãƒãƒ£ãƒ¼ã‚·ãƒ¥ãƒ¼ä¸¼  (extra 2 pcs $3.50)', 'Pork Cha-shu Don (extra 2 pcs $3.50)', 14, 9.5, 1, 13, 1.235, '[{"id":"56","price":"2","name":"extra egg"},{"id":"57","price":"3.5","name":"extra cha-shu"}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 5.5, 'N', '2016-11-15 18:06:14', 'Y', 'N'),
(1011, 186, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:06:28', 'Y', 'N'),
(1012, 186, 76, 'Takoyaki', 'Takoyaki', 11, 5.5, 1, 13, 0.715, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:06:33', 'Y', 'N'),
(1013, 186, 85, 'Shrimp', 'Shrimp', 12, 3, 1, 13, 0.39, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:06:35', 'Y', 'N'),
(1014, 186, 92, 'Hiro Special Oyster ', 'Hiro Special Oyster ', 13, 7.2, 1, 13, 0.936, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:06:38', 'Y', 'N'),
(1015, 186, 98, 'Ice Cream ', 'Ice Cream ', 18, 3.5, 1, 13, 0.455, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-15 18:06:42', 'Y', 'N'),
(1018, 188, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 10:26:36', 'Y', 'N'),
(1019, 187, 94, 'Yaki Udon ', 'Yaki Udon ', 14, 9, 1, 13, 1.17, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:01:31', 'Y', 'N'),
(1020, 189, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:10:13', 'Y', 'N'),
(1024, 189, 79, 'Ebi Mayo ', 'Ebi Mayo ', 11, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:34:17', 'Y', 'N'),
(1025, 189, 85, 'Shrimp', 'Shrimp', 12, 3, 1, 13, 0.39, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:34:23', 'Y', 'N'),
(1026, 189, 108, 'ã‚ã‹ã‚ã†ã©ã‚“ ', 'Seaweed Udon', 14, 8.5, 1, 13, 1.105, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:34:52', 'Y', 'N'),
(1027, 190, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:42:08', 'Y', 'N'),
(1028, 190, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:06', 'Y', 'N'),
(1029, 190, 74, 'Tamagoyaki', 'Tamagoyaki', 10, 7.5, 1, 13, 0.975, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:08', 'Y', 'N'),
(1030, 190, 69, 'Wakame Salad ', 'Wakame Salad ', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:09', 'Y', 'N'),
(1031, 190, 72, 'Miso Soup ', 'Miso Soup ', 10, 2.5, 1, 13, 0.325, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:10', 'Y', 'N'),
(1032, 190, 75, 'Tamago tofu ', 'Tamago tofu ', 10, 3.8, 1, 13, 0.494, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:11', 'Y', 'N'),
(1033, 190, 70, 'Edamame', 'Edamame', 10, 3.5, 1, 13, 0.455, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:49', 'Y', 'N'),
(1034, 190, 73, 'Sashimi Salad ', 'Sashimi Salad ', 10, 10.9, 1, 13, 1.417, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:50', 'Y', 'N'),
(1035, 190, 76, 'Takoyaki', 'Takoyaki', 11, 5.5, 1, 13, 0.715, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:53', 'Y', 'N'),
(1036, 190, 79, 'Ebi Mayo ', 'Ebi Mayo ', 11, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:54', 'Y', 'N'),
(1037, 190, 82, 'Agedashi Tofu ', 'Agedashi Tofu ', 11, 5.9, 1, 13, 0.767, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:55', 'Y', 'N'),
(1038, 190, 112, 'ã‚¨ãƒ“ãƒ•ãƒ©ã‚¤ã‚«ãƒ¬ãƒ¼ ', 'Deep Fry Shrimp with Curry', 11, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:56', 'Y', 'N'),
(1039, 190, 77, 'Cheese Fry ', 'Cheese Fry ', 11, 7.9, 1, 13, 1.027, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:57', 'Y', 'N'),
(1040, 190, 80, 'Sweet Potato Fry ', 'Sweet Potato Fry ', 11, 6, 1, 13, 0.78, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:54:59', 'Y', 'N'),
(1041, 190, 83, 'Corn Cream Corquette ', 'Corn Cream Corquette ', 11, 5.2, 1, 13, 0.676, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:00', 'Y', 'N'),
(1042, 190, 78, 'Chichen Karaage ', 'Chichen Karaage ', 11, 6.9, 1, 13, 0.897, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:01', 'Y', 'N'),
(1043, 190, 81, 'Chicken Wings ', 'Chicken Wings ', 11, 7.6, 1, 13, 0.988, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:02', 'Y', 'N'),
(1044, 190, 84, 'Mentaiko Mochi Cheese ', 'Mentaiko Mochi Cheese ', 11, 4.2, 1, 13, 0.546, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:04', 'Y', 'N'),
(1045, 190, 85, 'Shrimp', 'Shrimp', 12, 3, 1, 13, 0.39, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:10', 'Y', 'N'),
(1046, 190, 88, 'Salmom oshi-Sushi ', 'Salmom oshi-Sushi ', 12, 12.9, 1, 13, 1.677, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:12', 'Y', 'N'),
(1047, 190, 91, 'Garlic Butter Scallops ', 'Garlic Butter Scallops ', 12, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:13', 'Y', 'N'),
(1048, 190, 86, 'Unagi ', 'Unagi ', 12, 6, 1, 13, 0.78, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:14', 'Y', 'N'),
(1049, 190, 89, 'Unagi Oshi-sushi ', 'Unagi Oshi-sushi ', 12, 25, 1, 13, 3.25, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:16', 'Y', 'N'),
(1050, 190, 87, 'Scallop ', 'Scallop ', 12, 8, 1, 13, 1.04, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:17', 'Y', 'N'),
(1051, 190, 90, 'Tempura Set ', 'Tempura Set ', 12, 5.8, 1, 13, 0.754, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:19', 'Y', 'N'),
(1052, 190, 92, 'Hiro Special Oyster ', 'Hiro Special Oyster ', 13, 7.2, 1, 13, 0.936, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:23', 'Y', 'N'),
(1053, 190, 93, 'Baked Oyster With Cheese ', 'Baked Oyster With Cheese ', 13, 8.8, 1, 13, 1.144, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:26', 'Y', 'N'),
(1054, 190, 94, 'Yaki Udon ', 'Yaki Udon ', 14, 9, 1, 13, 1.17, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:30', 'Y', 'N'),
(1055, 190, 97, 'Gyu-D', 'Gyu-D', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:32', 'Y', 'N'),
(1056, 190, 105, 'å‘³å™Œãƒ©ãƒ¼ãƒ¡ãƒ³', 'Miso Ramen', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:35', 'Y', 'N'),
(1057, 190, 108, 'ã‚ã‹ã‚ã†ã©ã‚“ ', 'Seaweed Udon', 14, 8.5, 1, 13, 1.105, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:36', 'Y', 'N'),
(1058, 190, 111, 'ã†ãªé‡ ', 'Grilled Eel Don', 14, 22, 1, 13, 2.86, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:39', 'Y', 'N'),
(1059, 190, 115, 'ç…§ã‚Šç„¼ããƒã‚­ãƒ³ ', 'Teriyaki Chicken', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:41', 'Y', 'N'),
(1060, 190, 118, 'ç‰›ä¸¼ ', 'Sukiyaki beef Don', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:43', 'Y', 'N'),
(1061, 190, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:45', 'Y', 'N'),
(1062, 190, 102, 'ãƒãƒ£ãƒ¼ã‚·ãƒ¥ãƒ¼ä¸¼  (extra 2 pcs $3.50)', 'Pork Cha-shu Don (extra 2 pcs $3.50)', 14, 9.5, 1, 13, 1.235, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:47', 'Y', 'N'),
(1063, 190, 106, 'è±šéª¨ãƒ©ãƒ¼ãƒ¡ãƒ³ ', 'Pork Bone Ramen', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:49', 'Y', 'N'),
(1064, 190, 109, 'ã‚µãƒ¼ãƒ¢ãƒ³ï¼†ãƒžã‚°ãƒ­ä¸¼ ', 'Salmon&Tuna Don', 14, 14.9, 1, 13, 1.937, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:50', 'Y', 'N'),
(1065, 190, 113, 'åˆºèº«ã‚µãƒ©ãƒ€ ', 'Sashimi Salda', 14, 10.9, 1, 13, 1.417, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:53', 'Y', 'N'),
(1066, 190, 116, 'ã‚¨ãƒ“å¤©ä¸¼ ', 'Shrimp Tempura Don', 14, 9.5, 1, 13, 1.235, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:55', 'Y', 'N'),
(1067, 190, 96, 'ã‚«ãƒ„ä¸¼ ', 'Katsu Don ', 14, 9.5, 1, 13, 1.235, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:57', 'Y', 'N'),
(1068, 190, 103, 'ã‚«ãƒ„ã‚«ãƒ¬ãƒ¼ ', 'Katsu Curry', 14, 10.5, 1, 13, 1.365, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:55:58', 'Y', 'N'),
(1069, 190, 104, 'é†¤æ²¹ãƒ©ãƒ¼ãƒ¡ãƒ³ ', 'Soy Sauce Ramen', 14, 8.5, 1, 13, 1.105, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:00', 'Y', 'N'),
(1070, 190, 107, 'å¤©ã·ã‚‰ã†ã©ã‚“ ', 'Tempura Udon', 14, 9.2, 1, 13, 1.196, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:02', 'Y', 'N'),
(1071, 190, 110, 'ã‚µãƒ¼ãƒ¢ãƒ³ï¼†ã‚¢ãƒœã‚«ãƒ‰ä¸¼ ', 'Salmon&Avocado Don', 14, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:04', 'Y', 'N'),
(1072, 190, 114, 'ãƒã‚­ãƒ³å”æšã’ã‚«ãƒ¬ãƒ¼ ', 'Chicken Karaage Curry', 14, 10.9, 1, 13, 1.417, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:06', 'Y', 'N'),
(1073, 190, 117, 'é‡Žèœå¤©ã·ã‚‰ä¸¼ ', 'Veg Tempura Don', 14, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:08', 'Y', 'N'),
(1074, 190, 98, 'Ice Cream ', 'Ice Cream ', 18, 3.5, 1, 13, 0.455, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:21', 'Y', 'N'),
(1075, 190, 101, 'Cream brute ', 'Cream brute ', 18, 5.2, 1, 13, 0.676, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:24', 'Y', 'N'),
(1076, 190, 99, 'Mochi ice cream ', 'Mochi ice cream ', 18, 5.5, 1, 13, 0.715, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:26', 'Y', 'N'),
(1077, 190, 100, 'Cheese cake ', 'Cheese cake ', 18, 5.5, 1, 13, 0.715, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 11:56:29', 'Y', 'N'),
(1078, 191, 72, 'Miso Soup ', 'Miso Soup ', 10, 2.5, 1, 13, 0.325, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 12:14:28', 'Y', 'N'),
(1079, 191, 75, 'Tamago tofu ', 'Tamago tofu ', 10, 3.8, 1, 13, 0.494, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 12:14:30', 'Y', 'N'),
(1080, 192, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 12:34:38', 'Y', 'N'),
(1081, 192, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 12:34:40', 'Y', 'N'),
(1082, 193, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 13:05:25', 'Y', 'N'),
(1083, 193, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 13:05:26', 'Y', 'N'),
(1084, 193, 74, 'Tamagoyaki', 'Tamagoyaki', 10, 7.5, 1, 13, 0.975, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 13:05:27', 'Y', 'N'),
(1086, 194, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, '[{"id":"57","price":"3.5","name":"extra cha-shu"},{"id":"","price":"0","name":""}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 3.5, 'N', '2016-11-16 14:11:51', 'Y', 'N'),
(1087, 194, 74, 'Tamagoyaki', 'Tamagoyaki', 10, 7.5, 1, 13, 0.975, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 14:19:41', 'Y', 'N'),
(1088, 194, 69, 'Wakame Salad ', 'Wakame Salad ', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 14:19:43', 'Y', 'N'),
(1089, 194, 92, 'Hiro Special Oyster ', 'Hiro Special Oyster ', 13, 7.2, 1, 13, 0.936, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 14:19:49', 'Y', 'N'),
(1090, 194, 98, 'Ice Cream ', 'Ice Cream ', 18, 3.5, 1, 13, 0.455, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 14:20:01', 'Y', 'N'),
(1091, 195, 97, 'Gyu-D', 'Gyu-D', 14, 10, 1, 13, 1.3, '[{"id":"56","price":"2","name":"extra egg"},{"id":"","price":"0","name":""}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 2, 'N', '2016-11-16 14:26:05', 'Y', 'N'),
(1093, 207, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 15:06:02', 'Y', 'N'),
(1094, 198, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 15:21:29', 'Y', 'N'),
(1095, 198, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 15:21:30', 'Y', 'N'),
(1096, 198, 69, 'Wakame Salad ', 'Wakame Salad ', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 15:21:31', 'Y', 'N'),
(1098, 204, 149, '720 ml ', '720 ml ', 23, 65, 1, 13, 8.45, '[{"id":"56","price":"2","name":"extra egg"},{"id":"","price":"0","name":""}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 2, 'N', '2016-11-16 16:26:39', 'Y', 'N'),
(1099, 204, 74, 'Tamagoyaki', 'Tamagoyaki', 10, 7.5, 1, 13, 0.975, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:43:37', 'Y', 'N'),
(1100, 205, 72, 'Miso Soup ', 'Miso Soup ', 10, 2.5, 1, 13, 0.325, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:43:39', 'Y', 'N'),
(1101, 200, 98, 'Ice Cream ', 'Ice Cream ', 18, 3.5, 1, 13, 0.455, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:44:00', 'Y', 'N'),
(1102, 200, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:44:03', 'Y', 'N'),
(1103, 200, 92, 'Hiro Special Oyster ', 'Hiro Special Oyster ', 13, 7.2, 1, 13, 0.936, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:44:07', 'Y', 'N'),
(1104, 200, 79, 'Ebi Mayo ', 'Ebi Mayo ', 11, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:44:11', 'Y', 'N'),
(1105, 201, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 16:58:23', 'Y', 'N'),
(1106, 202, 102, 'ãƒãƒ£ãƒ¼ã‚·ãƒ¥ãƒ¼ä¸¼  (extra 2 pcs $3.50)', 'Pork Cha-shu Don (extra 2 pcs $3.50)', 14, 9.5, 1, 13, 1.235, '[{"id":"56","price":"2","name":"extra egg"},{"id":"57","price":"3.5","name":"extra cha-shu"},{"id":"","price":"0","name":""}]', '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', 5.5, 'N', '2016-11-16 17:08:58', 'Y', 'N'),
(1107, 203, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 17:51:32', 'N', 'N'),
(1108, 206, 72, 'Miso Soup ', 'Miso Soup ', 10, 2.5, 1, 13, 0.325, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 18:03:47', 'N', 'N'),
(1109, 207, 75, 'Tamago tofu ', 'Tamago tofu ', 10, 3.8, 1, 13, 0.494, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 18:03:47', 'N', 'N'),
(1110, 206, 73, 'Sashimi Salad ', 'Sashimi Salad ', 10, 10.9, 1, 13, 1.417, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-16 18:03:48', 'N', 'N'),
(1130, 208, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-17 11:55:49', 'N', 'N'),
(1131, 209, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 14:53:02', 'Y', 'N'),
(1132, 210, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 14:56:18', 'N', 'N'),
(1134, 211, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 14:58:51', 'N', 'N'),
(1135, 212, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 15:02:22', 'N', 'N'),
(1138, 196, 85, 'Shrimp', 'Shrimp', 12, 3, 1, 13, 0.39, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:06:29', 'N', 'N'),
(1139, 213, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:06:47', 'N', 'N'),
(1141, 214, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:09:22', 'N', 'N'),
(1142, 215, 80, 'Sweet Potato Fry ', 'Sweet Potato Fry ', 11, 6, 1, 13, 0.78, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:09:38', 'N', 'N'),
(1143, 216, 71, 'Takowasa', 'Takowasa', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:18:59', 'N', 'N'),
(1145, 216, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:19:04', 'N', 'N'),
(1146, 216, 69, 'Wakame Salad ', 'Wakame Salad ', 10, 4.5, 1, 13, 0.585, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 16:19:07', 'N', 'N'),
(1148, 217, 80, 'Sweet Potato Fry ', 'Sweet Potato Fry ', 11, 6, 1, 13, 0.78, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 17:24:20', 'N', 'N'),
(1149, 220, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 18:03:20', 'N', 'N'),
(1150, 221, 99, 'Mochi ice cream ', 'Mochi ice cream ', 18, 5.5, 1, 13, 0.715, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 18:03:36', 'N', 'N'),
(1151, 220, 68, 'Kaidou Salad ', 'Kaidou Salad ', 10, 8.9, 1, 13, 1.157, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 22:33:55', 'N', 'N'),
(1152, 220, 75, 'Tamago tofu ', 'Tamago tofu ', 10, 3.8, 1, 13, 0.494, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 22:33:56', 'N', 'N'),
(1153, 234, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 22:54:02', 'N', 'N'),
(1154, 235, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-18 22:54:06', 'N', 'N'),
(1155, 237, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 00:45:59', 'N', 'N'),
(1156, 238, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 00:46:03', 'N', 'N'),
(1157, 240, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 00:51:47', 'N', 'N'),
(1158, 241, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 00:51:49', 'N', 'N'),
(1159, 243, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 01:02:14', 'N', 'N'),
(1160, 244, 95, 'Unagi Don ', 'Unagi Don ', 14, 24, 1, 13, 3.12, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 01:02:15', 'N', 'N'),
(1161, 250, 115, 'ç…§ã‚Šç„¼ããƒã‚­ãƒ³ ', 'Teriyaki Chicken', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 08:06:52', 'N', 'N'),
(1162, 251, 105, 'å‘³å™Œãƒ©ãƒ¼ãƒ¡ãƒ³', 'Miso Ramen', 14, 9.9, 1, 13, 1.287, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 08:06:56', 'N', 'N'),
(1163, 251, 97, 'Gyu-Don', 'Gyu-Don', 14, 10, 1, 13, 1.3, NULL, '[{"id":"56","cousine_id":"103","name":"extra egg","name_zh":"extra egg","price":"2","status":"A","created":"2016-11-15 16:43:28"},{"id":"57","cousine_id":"103","name":"extra cha-shu","name_zh":"extra cha-shu","price":"3.5","status":"A","created":"2016-11-15 17:07:04"}]', NULL, 'N', '2016-11-19 08:06:59', 'N', 'N');

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
(8, 5, '0new', '2016-11-14', '2016-11-24', 1, '10', 0, 1, 1479162310, 1479227170),
(9, 5, '123', '2016-11-16', '2016-11-20', 0, '5', 0, 1, 1479319466, 1479319493);

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `category_locales`
--
ALTER TABLE `category_locales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `cooks`
--
ALTER TABLE `cooks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cousines`
--
ALTER TABLE `cousines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
--
-- AUTO_INCREMENT for table `cousine_locals`
--
ALTER TABLE `cousine_locals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;
--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1164;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
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
