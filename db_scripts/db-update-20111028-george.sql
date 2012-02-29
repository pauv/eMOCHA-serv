ALTER TABLE `uploaded_data` ADD `notified` DATETIME NOT NULL AFTER `file_path`;

ALTER TABLE `uploaded_data` CHANGE `flag` `rejected` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;