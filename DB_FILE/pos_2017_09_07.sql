ALTER TABLE `admins`
ADD COLUMN `default_tip_rate`  int NOT NULL DEFAULT 0 AFTER `tax`;

ALTER TABLE `orders`
MODIFY COLUMN `tax`  int NULL DEFAULT NULL AFTER `table_status`,
MODIFY COLUMN `tax_amount`  decimal(8,2) NULL DEFAULT 0 AFTER `tax`,
MODIFY COLUMN `total`  decimal(8,2) NULL DEFAULT 0 AFTER `subtotal`,
MODIFY COLUMN `discount_value`  decimal(8,2) NULL DEFAULT 0 AFTER `percent_discount`,
ADD COLUMN `default_tip_rate`  int(10) NULL AFTER `tax_amount`,
ADD COLUMN `default_tip_amount`  decimal(8,2) NULL AFTER `default_tip_rate`;


ALTER TABLE `order_splits`
ADD COLUMN `default_tip_rate`  float NULL AFTER `tax_amount`,
ADD COLUMN `default_tip_amount`  float NULL AFTER `default_tip_rate`;
