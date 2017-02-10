ALTER TABLE `admins` ADD COLUMN `logo_path` varchar(100) DEFAULT '../webroot/img/logo.bmp';
UPDATE `admins` SET `logo_path` = IFNULL(logo_path, '../webroot/img/logo.bmp');


CREATE TABLE apis (
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(100) NOT NULL,
  token VARCHAR(32) DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  created DATETIME DEFAULT NULL,
  modified DATETIME DEFAULT NULL,
  PRIMARY KEY(id)
);