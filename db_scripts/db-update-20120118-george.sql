-- tbdetect only
ALTER TABLE `phone` ADD `language` CHAR( 2 ) NOT NULL DEFAULT 'en' AFTER `comments`;