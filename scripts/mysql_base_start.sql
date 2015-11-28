CREATE TABLE `event` (
  `event_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `name` VARCHAR(80) NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`event_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `person` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` text,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `reputation_event` (
  `reputation_event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rep_network_code` char(4) NOT NULL,
  `change` int(11) NOT NULL DEFAULT '0',
  `event_id` int(10) unsigned DEFAULT NULL,
  `person_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`reputation_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
