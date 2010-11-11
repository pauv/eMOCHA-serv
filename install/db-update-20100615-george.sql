-- reduce length of code fields

ALTER TABLE `uploaded_data` CHANGE `household_code` `household_code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `uploaded_data` CHANGE `patient_code` `patient_code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `patients` CHANGE `code` `code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `patients` CHANGE `household_code` `household_code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `households` CHANGE `code` `code` CHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- drop old ids

ALTER TABLE `uploaded_data`
DROP `household_id`,
DROP `patient_id`;


-- indexes 15 long should suffice for codes

ALTER TABLE `uploaded_data` ADD INDEX `patient` ( `patient_code` ( 15 ) );
ALTER TABLE `uploaded_data` ADD INDEX `household` ( `household_code` ( 15 ) );

ALTER TABLE `patients` ADD INDEX `household` ( `household_code` ( 15 ) );