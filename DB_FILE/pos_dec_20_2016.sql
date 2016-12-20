CREATE TABLE `logs` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `created` datetime NOT NULL,
 `cashier_id` int(11) NOT NULL,
 `admin_id` int(11) NOT NULL,
 `logs` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

