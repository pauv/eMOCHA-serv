ALTER TABLE `patients` MODIFY COLUMN first_name VARBINARY( 100 ) NOT NULL;
ALTER TABLE `patients` MODIFY COLUMN last_name VARBINARY (100) NOT NULL;

UPDATE patients SET first_name = AES_ENCRYPT(first_name,'key');
UPDATE patients SET last_name = AES_ENCRYPT(last_name,'key');

ALTER TABLE `patients` MODIFY COLUMN age VARBINARY(50) NOT NULL;
ALTER TABLE `patients` MODIFY COLUMN sex VARBINARY (50) NOT NULL;

UPDATE patients SET age = AES_ENCRYPT(age,'key');
UPDATE patients SET sex = AES_ENCRYPT(sex,'key');


ALTER TABLE `households` MODIFY COLUMN village_code VARBINARY( 100 ) NOT NULL;
ALTER TABLE `households` MODIFY COLUMN gps VARBINARY( 200 ) NOT NULL;
ALTER TABLE `households` MODIFY COLUMN gps_lat VARBINARY (100) NOT NULL;
ALTER TABLE `households` MODIFY COLUMN gps_long VARBINARY( 100 ) NOT NULL;

-- gps long and lat were previously decimal(10,6)

UPDATE households SET village_code = AES_ENCRYPT(village_code,'key');
UPDATE households SET gps = AES_ENCRYPT(gps,'key');
UPDATE households SET gps_lat = AES_ENCRYPT(gps_lat,'key');
UPDATE households SET gps_long = AES_ENCRYPT(gps_long,'key');

not necessary for UW
-- ALTER TABLE `households` DROP `label` ,
-- DROP `comments` ;


ALTER TABLE `uploaded_data` DROP INDEX `xml_content`; 

ALTER TABLE `uploaded_data` MODIFY COLUMN xml_content BLOB NOT NULL;

UPDATE uploaded_data SET xml_content = AES_ENCRYPT(xml_content,'key');







