CREATE TABLE `event` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `time` varchar(40) DEFAULT NULL,
  `name` varchar(80) NOT NULL,
  `description` text,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` text,
  `base_id` int(10) unsigned,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `person` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` text,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `reputation_event` (
  `reputation_event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rep_network_code` char(16) NOT NULL,
  `change` int(11) NOT NULL DEFAULT '0',
  `event_id` int(10) unsigned DEFAULT NULL,
  `person_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`reputation_event_id`),
  KEY `event_id` (`event_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `reputation_event_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  CONSTRAINT `reputation_event_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `group_members` (
  `group_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`person_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
