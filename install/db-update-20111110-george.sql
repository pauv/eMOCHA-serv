ALTER TABLE `configs` ADD `description` VARCHAR( 255 ) NOT NULL AFTER `content`;
ALTER TABLE `configs` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `phone` ADD `c2dm_disable` TINYINT NOT NULL;
ALTER TABLE `phone` CHANGE `c2dm_disable` `c2dm_disable` TINYINT( 1 ) NOT NULL;