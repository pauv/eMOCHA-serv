ALTER TABLE `media` ADD `language` CHAR( 2 ) NOT NULL;
ALTER TABLE `media` CHANGE `language` `language` CHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'en';