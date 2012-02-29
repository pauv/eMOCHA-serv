
--
-- Table structure for table `phone_form_reminders`
--

CREATE TABLE IF NOT EXISTS `phone_form_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `reminder_id` int(11) NOT NULL,
  `reminder_ts` datetime NOT NULL,
  `reply_ts` datetime NOT NULL,
  `form_id` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;