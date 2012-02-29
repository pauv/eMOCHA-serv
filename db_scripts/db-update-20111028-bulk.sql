-- add flag to leave special markers
-- e.g. 'late' data for the exact project
ALTER TABLE `uploaded_data` ADD `flag` VARCHAR( 50 ) NOT NULL;

CREATE TABLE `phone_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `message_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `form_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_alert_schedules`
--

CREATE TABLE `phone_alert_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `ts` int(11) NOT NULL,
  `sent` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `phone` ADD `c2dm_registration_id` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `uploaded_data` ADD `notified` DATETIME NOT NULL AFTER `file_path`;

ALTER TABLE `uploaded_data` CHANGE `flag` `rejected` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `phone_locations` ENGINE = InnoDB;



CREATE TABLE `c2dm_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `curl_error` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `http_code` int(11) NOT NULL,
  `response` text COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `configs` ADD `description` VARCHAR( 255 ) NOT NULL AFTER `content`;
ALTER TABLE `configs` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `phone` ADD `c2dm_disable` TINYINT NOT NULL;
ALTER TABLE `phone` CHANGE `c2dm_disable` `c2dm_disable` TINYINT( 1 ) NOT NULL;