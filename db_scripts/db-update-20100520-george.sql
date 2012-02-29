CREATE TABLE `households` (
  `id` int(11) NOT NULL auto_increment,
  `code` char(50) NOT NULL,
  `village_code` varchar(20) NOT NULL,
  `gps_lat` decimal(18,14) NOT NULL,
  `gps_long` decimal(18,14) NOT NULL,
  `label` varchar(250) NOT NULL,
  `comments` text NOT NULL,
  `register_phone_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 
 
CREATE TABLE `patients` (
  `id` int(11) NOT NULL auto_increment,
  `code` char(50) NOT NULL,
  `household_code` char(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `age` smallint(6) NOT NULL,
  `sex` enum('m','f','') NOT NULL,
  `register_phone_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
 
 
ALTER TABLE `uploaded_data` ADD `household_code` CHAR( 50 ) NOT NULL AFTER `study_id`;

ALTER TABLE `uploaded_data` CHANGE `study_id` `patient_code` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;