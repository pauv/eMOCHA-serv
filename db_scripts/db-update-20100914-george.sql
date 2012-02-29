ALTER TABLE `forms` ADD `group` ENUM( 'household', 'patient' ) NOT NULL AFTER `id` ,
ADD `code` CHAR( 20 ) NOT NULL AFTER `group`;