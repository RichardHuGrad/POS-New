ALTER TABLE `orders`
  CHANGE COLUMN `reorder_no` `reorder_no` varchar(15) NULL DEFAULT '0';

ALTER TABLE `order_splits`
  CHANGE COLUMN `order_no` `order_no` varchar(15) NOT NULL DEFAULT '0';

ALTER TABLE `order_logs`
  CHANGE COLUMN `order_no` `order_no` varchar(15) NOT NULL DEFAULT '0';
