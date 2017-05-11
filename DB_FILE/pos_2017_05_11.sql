ALTER TABLE `admins`
  ADD COLUMN `no_of_online_tables` int(11) NULL DEFAULT NULL AFTER `no_of_waiting_tables`,
  ADD COLUMN `oc_store_id` int(11)  NULL AFTER `logo_path`,
  ADD COLUMN `oc_api_url`  varchar(255) NULL AFTER `oc_store_id`,
  ADD COLUMN `oc_api_key`  varchar(1000) NULL AFTER `oc_api_url`;

ALTER TABLE `orders` MODIFY COLUMN `order_type` enum('D','T','W','L') NULL DEFAULT NULL COMMENT 'D-Dinein, T-takeway, W-waiting, L-Online';

