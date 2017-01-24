CREATE TABLE `cookies` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`key` varchar(100) NOT NULL,
	`value` text NOT NULL,
	`path` varchar(100) DEFAULT NULL,
	`created` datetime NOT NULL,
	`validate_days` int(10) DEFAULT 0,
	primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;