DROP TABLE `random_emails` ,
`random_emails_sent` ;


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
