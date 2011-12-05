ALTER TABLE `phone_alerts` ADD `message` TEXT NOT NULL AFTER `form_code`;
ALTER TABLE `phone_alerts` ADD `received` TINYINT( 1 ) NOT NULL;
ALTER TABLE `phone_alerts` ADD `sent` TINYINT( 1 ) NOT NULL AFTER `time_sent`;