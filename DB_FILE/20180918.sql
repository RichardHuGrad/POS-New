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
ALTER TABLE `admins` ADD `net_last_sync_tm` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time for access cloud server' AFTER `singlecut`, ADD `net_new_order` TINYINT NOT NULL COMMENT 'flag for has new order from cloud server' AFTER `net_last_sync_tm`;
ALTER TABLE `admins` ADD `net_order_kitchen` TINYINT NOT NULL COMMENT 'Net Order send Sent to Kitchen or not. 0 not, 1 yes' AFTER `net_new_order`;
ALTER TABLE `admins` ADD `net_takeout_kitchen` TINYINT NOT NULL AFTER `net_order_kitchen`, ADD `net_order_voice` TINYINT NOT NULL AFTER `net_takeout_kitchen`, ADD `touch_screen_sound` TINYINT NOT NULL AFTER `net_order_voice`, ADD `default_tip_after_tax` TINYINT NOT NULL AFTER `touch_screen_sound`, ADD `main_page_show_price` TINYINT NOT NULL AFTER `default_tip_after_tax`;
ALTER TABLE `admins` ADD `show2nd` TINYINT NOT NULL COMMENT 'Show send language or not. 0 not, 1 yes' AFTER `main_page_show_price`;