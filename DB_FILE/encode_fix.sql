-- do not run this file twice
UPDATE `cousine_locals` SET `name`=convert(cast(convert(name using latin1) as binary) using utf8);
UPDATE `extras` SET `name_zh`=convert(cast(convert(name_zh using latin1) as binary) using utf8);
UPDATE `category_locales` SET `name`=convert(cast(convert(name using latin1) as binary) using utf8);
UPDATE `extrascategories` SET `name_zh`=convert(cast(convert(name_zh using latin1) as binary) using utf8);
UPDATE `order_items` SET `name_xh`=convert(cast(convert(name_xh using latin1) as binary) using utf8);

ALTER DATABASE pos CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE admins CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE admins_privlages CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE apis CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE cashiers CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE categories CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE category_locales CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE cooks CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE cousines CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE cousine_locals CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE extras CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE extrascategories CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE global_settings CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE languages CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE logs CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE orders CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE order_items CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE order_logs CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE order_splits CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE pages CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE promocodes CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
