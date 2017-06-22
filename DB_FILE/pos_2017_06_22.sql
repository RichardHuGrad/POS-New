ALTER TABLE `attendances`
DROP COLUMN `day`,
MODIFY COLUMN `checkin`  datetime NULL DEFAULT NULL AFTER `userid`,
MODIFY COLUMN `checkout`  datetime NULL DEFAULT NULL AFTER `checkin`;
