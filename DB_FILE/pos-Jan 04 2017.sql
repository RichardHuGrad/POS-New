-- --------------------------------------------------------

--
-- Change Table structure for table `orders`
--

ALTER TABLE `orders` MODIFY `tip_paid_by` enum('CARD','CASH', 'MIXED', 'NO TIP') DEFAULT NULL;



-- --------------------------------------------------------

--
-- Table structure for table `order_split`
--

CREATE TABLE IF NOT EXISTS `order_splits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `table_no` int(10) DEFAULT NULL,
  `order_no` varchar(10) NOT NULL DEFAULT '0',
  `suborder_no` varchar(10) NOT NULL DEFAULT '0',
  `subtotal` float DEFAULT NULL,
  `discount_type` enum('UNKNOWN', 'FIXED', 'PERCENT') DEFAULT NULL,
  `discount_value` float DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `paid_card` float DEFAULT NULL,
  `paid_cash` float DEFAULT NULL,
  `tip_card` float DEFAULT NULL,
  `tip_cash` float DEFAULT NULL,
  `change` float DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `items` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;


