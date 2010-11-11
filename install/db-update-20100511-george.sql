 CREATE TABLE `configs` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`label` VARCHAR( 50 ) NOT NULL ,
`content` TEXT NOT NULL
) ENGINE = InnoDB;


 ALTER TABLE `forms` CHANGE `condition` `conditions` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;