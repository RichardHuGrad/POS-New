CREATE TABLE IF NOT EXISTS `order_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(10) NOT NULL DEFAULT '0',
  `json` text NOT NULL,
  `operation` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;