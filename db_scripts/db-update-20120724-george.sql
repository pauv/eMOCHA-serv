-- create active field for patient deletion
-- EXACT only
ALTER TABLE `patients` ADD `active` TINYINT( 1 ) NOT NULL DEFAULT '1';