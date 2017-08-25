ALTER TABLE `categories`
ADD COLUMN `group_id`  smallint UNSIGNED NULL DEFAULT 1 AFTER `printer`;

DROP TABLE IF EXISTS `category_groups`;
CREATE TABLE `category_groups` (
  `id` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of category_groups
-- ----------------------------
INSERT INTO `category_groups` VALUES ('1', 'Cashier');
INSERT INTO `category_groups` VALUES ('2', 'Kitchen Noodle');
INSERT INTO `category_groups` VALUES ('3', 'Kitchen Other');
