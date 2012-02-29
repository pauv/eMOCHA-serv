ALTER TABLE `phone_locations` ADD `altitude` DECIMAL( 5, 1 ) NOT NULL AFTER `gps_long` ,
ADD `speed` DECIMAL( 3, 2 ) NOT NULL AFTER `altitude`;