-- convert all tables to MyISAM
-- for simpler binary backups
-- under mysql sandbox

-- do these separately at first
ALTER TABLE roles_users DROP FOREIGN KEY `roles_users_ibfk_1`;
ALTER TABLE roles_users DROP FOREIGN KEY `roles_users_ibfk_2`;
ALTER TABLE user_tokens DROP FOREIGN KEY `user_tokens_ibfk_1`;

ALTER TABLE `alarms`  ENGINE = MYISAM;
ALTER TABLE `alarm_actions`  ENGINE = MYISAM;
ALTER TABLE `alarm_conditions`  ENGINE = MYISAM;
ALTER TABLE `configs`  ENGINE = MYISAM;
ALTER TABLE `files`  ENGINE = MYISAM;
ALTER TABLE `forms`  ENGINE = MYISAM;
ALTER TABLE `households`  ENGINE = MYISAM;
ALTER TABLE `media`  ENGINE = MYISAM;
ALTER TABLE `patients`  ENGINE = MYISAM;
ALTER TABLE `phone`  ENGINE = MYISAM;
ALTER TABLE `referrals`  ENGINE = MYISAM;
ALTER TABLE `roles`  ENGINE = MYISAM;
ALTER TABLE `roles_users`  ENGINE = MYISAM;
ALTER TABLE `uploaded_data`  ENGINE = MYISAM;
ALTER TABLE `users`  ENGINE = MYISAM;
ALTER TABLE `user_tokens`  ENGINE = MYISAM;
ALTER TABLE `user_verifications`  ENGINE = MYISAM;
