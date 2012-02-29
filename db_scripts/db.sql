-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2010 at 05:07 AM
-- Server version: 5.0.90
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `admin_emocha_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(128) NOT NULL,
  `path` varchar(128) NOT NULL COMMENT 'path of a file in the sdcard folder',
  `ts` int(11) NOT NULL COMMENT 'timestamp of last modification',
  `size` int(11) NOT NULL COMMENT 'file size in bytes',
  `md5` varchar(32) NOT NULL COMMENT 'md5 hash of the file',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='content of the sdcard folder, files will be sent to phones';


--
-- Table structure for table `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `condition` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `archived` tinyint(1) NOT NULL,
  `file_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forms`
--



--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `type` enum('library','courses','lectures') NOT NULL,
  `file_id` int(11) NOT NULL,
  `thumbnail_file_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `media`
--


-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE IF NOT EXISTS `phone` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `imei` varchar(32) character set latin1 NOT NULL COMMENT 'IMEI code of the phone',
  `imei_md5` varchar(32) character set latin1 NOT NULL COMMENT 'MD5 hash of the IMEI code',
  `validated` tinyint(1) NOT NULL COMMENT 'phones are inactive until activated in the control panel',
  `last_connect_ts` int(11) NOT NULL COMMENT 'timestamp of last phone call to the server',
  `pwd` varchar(64) character set latin1 NOT NULL COMMENT 'password using the mysql PASSWORD function, to be entered in the phone',
  `gps` varchar(128) character set latin1 NOT NULL COMMENT 'last phone position',
  `comments` varchar(200) character set latin1 NOT NULL COMMENT 'maybe an alias or the name of the owner of the phone',
  `creation_ts` int(11) NOT NULL COMMENT 'timestamp of phone''s first call to the server',
  `creation_ip` varchar(15) character set latin1 NOT NULL COMMENT 'phone''s IP address when it was added to this table',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `imei` (`imei`),
  UNIQUE KEY `imei_md5` (`imei_md5`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='list of phones wanting to connect to api.php';

--
-- Dumping data for table `phone`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `uploaded_data`
--

CREATE TABLE IF NOT EXISTS `uploaded_data` (
  `ID` int(11) NOT NULL auto_increment,
  `creator_phone_id` int(11) NOT NULL,
  `uploader_phone_id` int(11) NOT NULL,
  `household_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `xml_content` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `last_modified` varchar(32) NOT NULL,
  `display_label` text NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `creator_phone_id` (`creator_phone_id`,`household_id`,`patient_id`,`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='new test table. form data with directory info.';

--
-- Dumping data for table `uploaded_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(127) NOT NULL,
  `username` varchar(32) NOT NULL default '',
  `password` char(50) NOT NULL,
  `logins` int(10) unsigned NOT NULL default '0',
  `last_login` int(10) unsigned default NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `activated` tinyint(1) NOT NULL default '0',
  `confirmed` tinyint(1) NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_tokens`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE IF NOT EXISTS `user_verifications` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `type` enum('activation','password','email_change') NOT NULL,
  `code` varchar(100) NOT NULL,
  `verified` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_verifications`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_users`
--
ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
