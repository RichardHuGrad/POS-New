ALTER TABLE `admins`
  MODIFY COLUMN `address`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `mobile_no`,
  ADD COLUMN `city`  varchar(50) NOT NULL AFTER `address`,
  ADD COLUMN `province`  varchar(50) NOT NULL AFTER `city`,
  ADD COLUMN `zipcode`  varchar(10) NOT NULL AFTER `province`,
  ADD COLUMN `print_offset`  varchar(100) NOT NULL COMMENT 'number seperated by comma' AFTER `zipcode`,
  ADD COLUMN `hst_number`  varchar(50) NOT NULL AFTER `zipcode`,
  ADD COLUMN `no_of_online_tables` int(11) NULL DEFAULT NULL AFTER `no_of_waiting_tables`,
  ADD COLUMN `oc_store_id` int(11)  NULL AFTER `logo_path`,
  ADD COLUMN `oc_api_url`  varchar(255) NULL AFTER `oc_store_id`,
  ADD COLUMN `oc_api_key`  varchar(1000) NULL AFTER `oc_api_url`;

ALTER TABLE `orders` MODIFY COLUMN `order_type` enum('D','T','W','L') NULL DEFAULT NULL COMMENT 'D-Dinein, T-takeway, W-waiting, L-Online';

ALTER TABLE `orders`
MODIFY COLUMN `table_status`  enum('P','N','A','V','R') NULL DEFAULT 'N' COMMENT 'P-paid, N-not paid, A-available, V-Void, R-Receipt Printed' AFTER `table_no`;


ALTER TABLE `logs`
MODIFY COLUMN `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ,
ADD COLUMN `operation`  varchar(50) NOT NULL AFTER `admin_id`,
ADD COLUMN `created`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `logs`;
