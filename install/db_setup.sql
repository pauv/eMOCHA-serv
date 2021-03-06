

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `alarms`
--

CREATE TABLE IF NOT EXISTS `alarms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `label` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarm_actions`
--

CREATE TABLE IF NOT EXISTS `alarm_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarm_conditions`
--

CREATE TABLE IF NOT EXISTS `alarm_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `c2dm_errors`
--

CREATE TABLE IF NOT EXISTS `c2dm_errors` (
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

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `description` text NOT NULL,
	`type` ENUM( 'android', 'server', 'platform' ) NOT NULL,
	`mandatory` tinyint(1) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) NOT NULL,
  `path` varchar(128) NOT NULL COMMENT 'path of a file in the sdcard folder',
  `ts` int(11) NOT NULL COMMENT 'timestamp of last modification',
  `size` int(11) NOT NULL COMMENT 'file size in bytes',
  `md5` varchar(32) NOT NULL COMMENT 'md5 hash of the file',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='content of the sdcard folder, files will be sent to phones';

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` enum('household_core','household_data','patient_core','patient_data','training','training_data') NOT NULL,
  `code` char(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `conditions` text NOT NULL,
  `label` varchar(100) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `archived` tinyint(1) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `form_files`
--

CREATE TABLE IF NOT EXISTS `form_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `type` enum('image') NOT NULL DEFAULT 'image',
  `label` varchar(100) NOT NULL,
  `config` text NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE IF NOT EXISTS `households` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(25) NOT NULL,
  `village_code` varbinary(100) NOT NULL,
  `gps` varbinary(200) NOT NULL,
  `gps_lat` varbinary(100) NOT NULL,
  `gps_long` varbinary(100) NOT NULL,
  `register_phone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `type` enum('library','courses','lectures') NOT NULL,
  `file_id` int(11) NOT NULL,
  `thumbnail_file_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `language` char(2) NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(25) NOT NULL,
  `household_code` char(25) NOT NULL,
  `first_name` varbinary(100) NOT NULL,
  `last_name` varbinary(100) NOT NULL,
  `age` varbinary(50) NOT NULL,
  `sex` varbinary(50) NOT NULL,
  `register_phone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `household` (`household_code`(15))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE IF NOT EXISTS `phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `imei` varchar(32) CHARACTER SET latin1 NOT NULL COMMENT 'IMEI code of the phone',
  `imei_md5` varchar(32) CHARACTER SET latin1 NOT NULL COMMENT 'MD5 hash of the IMEI code',
  `validated` tinyint(1) NOT NULL COMMENT 'phones are inactive until activated in the control panel',
  `last_connect_ts` int(11) NOT NULL COMMENT 'timestamp of last phone call to the server',
  `pwd` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT 'password using the mysql PASSWORD function, to be entered in the phone',
  `gps` varchar(128) CHARACTER SET latin1 NOT NULL COMMENT 'last phone position',
  `comments` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'maybe an alias or the name of the owner of the phone',
  `creation_ts` int(11) NOT NULL COMMENT 'timestamp of phone''s first call to the server',
  `creation_ip` varchar(15) CHARACTER SET latin1 NOT NULL COMMENT 'phone''s IP address when it was added to this table',
  `c2dm_registration_id` varchar(255) NOT NULL,
  `c2dm_disable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `imei` (`imei`),
  UNIQUE KEY `imei_md5` (`imei_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='list of phones wanting to connect to api.php';

-- --------------------------------------------------------

--
-- Table structure for table `phone_alerts`
--

CREATE TABLE IF NOT EXISTS `phone_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `message_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `form_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `time_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL,
  `response` text COLLATE utf8_unicode_ci NOT NULL,
  `received` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_alert_schedules`
--

CREATE TABLE IF NOT EXISTS `phone_alert_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `ts` int(11) NOT NULL,
  `sent` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_locations`
--

CREATE TABLE IF NOT EXISTS `phone_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `gps` varchar(100) NOT NULL,
  `gps_lat` decimal(10,6) NOT NULL,
  `gps_long` decimal(10,6) NOT NULL,
  `altitude` decimal(5,1) NOT NULL,
  `speed` decimal(3,2) NOT NULL,
  `ts` datetime NOT NULL,
  `bearing` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `referral_id` varchar(50) NOT NULL,
  `form_data_id` int(11) NOT NULL,
  `ts_logged` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_data`
--

CREATE TABLE IF NOT EXISTS `uploaded_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_phone_id` int(11) NOT NULL,
  `uploader_phone_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `household_code` char(25) NOT NULL,
  `patient_code` char(25) NOT NULL,
  `xml_content` blob NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `notified` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `rejected` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient` (`patient_code`(15)),
  KEY `household` (`household_code`(15))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='new test table. form data with directory info.';

-- --------------------------------------------------------

--
-- Table structure for table `form_data_files`
--

CREATE TABLE IF NOT EXISTS `form_data_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_data_id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL COMMENT 'path to the file',
  `type` varchar(50) NOT NULL,
  `xpath` varchar(100) COMMENT 'xpath node name the file points to (if any)',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='contains files related to form data (e.g: pictures, signatures, audio files, etc..)';

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL COMMENT 'contact''s name',
  `phone_number` varchar(15) NOT NULL COMMENT 'contact''s phone number',
	`public` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'whether it is visible to all devices (1) or not (0)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='basic contact details table';

-- --------------------------------------------------------

--
-- Table structure for table `contact_phone`: tells what contacts have each phone. When contact.public=1, contact should not be inserted.
--

CREATE TABLE IF NOT EXISTS `phone_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='relational table contacts per phone';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` char(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE IF NOT EXISTS `user_verifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('activation','password','email_change') NOT NULL,
  `code` varchar(100) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');


--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`label`, `content`, `description`, `type`, `mandatory`, `last_modified`) VALUES
('app_time_zone', 'America/New_York', 'Where the application runs from', 'platform', 1, CURRENT_TIMESTAMP),
('application_type', 'DO NOT LEAVE IT EMPTY', 'application type (app_type_households, app_type_patients_only, app_type_forms_only)', 'platform', 1, CURRENT_TIMESTAMP),
('authentication', 'usr_password', 'Authentication system: how data is uploaded/download to/from the server (usr_password, usr_only)', 'platform',1, CURRENT_TIMESTAMP),
('version_name', 'VERSION_NAME', 'Application''s name', 'server', 0, CURRENT_TIMESTAMP),
('admin_alerts_to', 'some@email.com', 'Admin alerts are sent to this address', 'server', 0, CURRENT_TIMESTAMP),
('device_password_on', '0', 'Is password enabled in the device?', 'android', 0, CURRENT_TIMESTAMP),
('device_password', 'emocha', 'Default password in case device_passord_on is true', 'android', 0, CURRENT_TIMESTAMP),
('interval_update_gps', '7200', 'Interval between GPS updates', 'android', 0, CURRENT_TIMESTAMP),
('interval_config_update', '60', 'Interval between config updates', 'android', 0, CURRENT_TIMESTAMP),
('interval_upd_download', '60', 'Interval between data uploading', 'android', 0, CURRENT_TIMESTAMP),
('self_signed_ssl', '0', 'Server has a self signed SSL certificate?', 'android', 0, CURRENT_TIMESTAMP),
('max_distance_from_household', '200', 'Max distance between households. See filter_by_gps_location', 'android', 0, CURRENT_TIMESTAMP),
('filter_by_gps_location', '0', 'Household list is filtered by gps location?', 'android', 0, CURRENT_TIMESTAMP),
('default_gps_location', '0 0 0 0', 'Default GPS location', 'android', 0, CURRENT_TIMESTAMP),
('patient_description_field', 'first_name,last_name', 'Patient description field', 'android', 0, CURRENT_TIMESTAMP),
('household_description_field', 'gps_description', 'House hold description field', 'android', 0, CURRENT_TIMESTAMP),
('user_session_timeout', '3600', 'User session timeout', 'android', 0, CURRENT_TIMESTAMP),
('contact_consultation', '+256777667444', 'Contact consultation', 'android', 0, CURRENT_TIMESTAMP),
('contact_hospital', '+256777667444', 'Contact hospital', 'android', 0, CURRENT_TIMESTAMP),
('pn_allowed_delay', '3600', 'Allowed delay to reply a push notification (form reminder)', 'android', 0, CURRENT_TIMESTAMP),
('c2dm_sender_id', 'DO NOT LEAVE IT EMPTY', 'C2DM''s sender id', 'android', 0, CURRENT_TIMESTAMP),
('records_per_page', '25', 'max records displayed per page', 'android', 0, CURRENT_TIMESTAMP),
('main_menu_list', 'Enter Data,Training,Settings,Help', 'main menu list to be displayed in the device. Allowed values are: Enter Data, Training, Settings, Help, Communication', 'android', 0, CURRENT_TIMESTAMP);
