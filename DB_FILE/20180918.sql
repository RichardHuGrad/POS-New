CREATE TABLE `remote_order_syncs` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `order_type` varchar(32) CHARACTER SET latin1 NOT NULL,
 `order_id` int(11) NOT NULL,
 `synced` TINYINT(1) NOT NULL,
 `last_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `record` TEXT NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `admins` ADD `singlecut` TINYINT NOT NULL;