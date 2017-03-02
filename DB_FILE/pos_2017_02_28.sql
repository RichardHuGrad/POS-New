ALTER TABLE `orders` ADD COLUMN `sync` enum('Y', 'N') DEFAULT 'N';
ALTER TABLE `orders` ADD COLUMN `is_deleted` enum('Y', 'N') DEFAULT 'N';
