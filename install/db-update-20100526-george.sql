ALTER TABLE `uploaded_data`  ENGINE = MYISAM;

ALTER TABLE `uploaded_data` ADD FULLTEXT `xml_content` (
`xml_content`
);

CREATE TABLE `referrals` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`patient_id` INT NOT NULL ,
`referral_id` VARCHAR( 50 ) NOT NULL ,
`form_data_id` INT NOT NULL ,
`ts_logged` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;