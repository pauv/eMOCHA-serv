-- add flag to leave special markers
-- e.g. 'late' data for the exact project
ALTER TABLE `uploaded_data` ADD `flag` VARCHAR( 50 ) NOT NULL;

-- exact only
ALTER TABLE `random_emails` CHANGE `code` `patient_code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE `random_emails_sent` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`patient_code` CHAR( 25 ) NOT NULL ,
`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`uploaded_data_id` INT NOT NULL
) ENGINE = MYISAM ;