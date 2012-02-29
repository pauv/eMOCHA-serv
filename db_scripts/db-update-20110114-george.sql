
CREATE TABLE `phone_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `gps` varchar(100) NOT NULL,
  `gps_lat` decimal(10,6) NOT NULL,
  `gps_long` decimal(10,6) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `uploaded_data` CHANGE `last_modified` `last_modified` DATETIME NOT NULL;