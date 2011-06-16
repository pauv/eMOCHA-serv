-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 16, 2011 at 10:37 AM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `alarms`
--

CREATE TABLE `alarms` (
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

CREATE TABLE `alarm_actions` (
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

CREATE TABLE `alarm_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
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

CREATE TABLE `forms` (
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

CREATE TABLE `form_files` (
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

CREATE TABLE `households` (
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

CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `type` enum('library','courses','lectures') NOT NULL,
  `file_id` int(11) NOT NULL,
  `thumbnail_file_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
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

CREATE TABLE `phone` (
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `imei` (`imei`),
  UNIQUE KEY `imei_md5` (`imei_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='list of phones wanting to connect to api.php';

-- --------------------------------------------------------

--
-- Table structure for table `phone_locations`
--

CREATE TABLE `phone_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `gps` varchar(100) NOT NULL,
  `gps_lat` decimal(10,6) NOT NULL,
  `gps_long` decimal(10,6) NOT NULL,
  `altitude` decimal(5,1) NOT NULL,
  `speed` decimal(3,2) NOT NULL,
  `ts` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
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

CREATE TABLE `roles` (
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

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_data`
--

CREATE TABLE `uploaded_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_phone_id` int(11) NOT NULL,
  `uploader_phone_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `household_code` char(25) NOT NULL,
  `patient_code` char(25) NOT NULL,
  `xml_content` blob NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient` (`patient_code`(15)),
  KEY `household` (`household_code`(15))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='new test table. form data with directory info.';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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

CREATE TABLE `user_tokens` (
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

CREATE TABLE `user_verifications` (
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

INSERT INTO `roles` VALUES(1, 'login', 'Login privileges, granted after account confirmation');
INSERT INTO `roles` VALUES(2, 'admin', 'Administrative user, has access to everything.');
