ALTER TABLE `households` ADD `gps` VARCHAR( 100 ) NOT NULL AFTER `village_code`;
ALTER TABLE `households` CHANGE `gps_lat` `gps_lat` DECIMAL( 10, 6 ) NOT NULL;
ALTER TABLE `households` CHANGE `gps_long` `gps_long` DECIMAL( 10, 6 ) NOT NULL;
