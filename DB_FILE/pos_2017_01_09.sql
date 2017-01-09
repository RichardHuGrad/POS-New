ALTER TABLE `order_items` ADD `is_waimai` ENUM('N','Y') NOT NULL DEFAULT 'N' AFTER `is_kitchen`;
