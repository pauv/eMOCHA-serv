RENAME TABLE `c2dm_errors` TO `alerts_errors` ;

ALTER TABLE `phone` CHANGE `c2dm_registration_id` `alerts_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `phone` CHANGE `c2dm_disable` `enable_alerts` TINYINT( 4 ) NOT NULL;
ALTER TABLE `phone` CHANGE `enable_alerts` `enable_alerts` TINYINT( 4 ) NOT NULL DEFAULT '1';
UPDATE phone SET enable_alerts =0;
