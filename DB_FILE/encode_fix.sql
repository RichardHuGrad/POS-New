-- Do not use this sentence directly
-- replace UTF8_TABLE, UTF8_FIELD, LATIN1_TABLE, LATIN1_FIELD as you need
INSERT INTO UTF8_TABLE (UTF8_FIELD)
SELECT convert(cast(convert(LATIN1_FIELD using latin1) as binary) using utf8)
  FROM LATIN1_TABLE;

UPDATE `cousine_locals` SET `name`=convert(cast(convert(name using latin1) as binary) using utf8);
UPDATE `extras` SET `name_zh`=convert(cast(convert(name_zh using latin1) as binary) using utf8);
UPDATE `category_locales` SET `name`=convert(cast(convert(name using latin1) as binary) using utf8);
UPDATE `extrascategories` SET `name_zh`=convert(cast(convert(name_zh using latin1) as binary) using utf8);
UPDATE `order_items` SET `name_xh`=convert(cast(convert(name_xh using latin1) as binary) using utf8);
