CREATE TABLE `event` (
  `event_id`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`         VARCHAR(80)      NOT NULL,
  `time`        VARCHAR(40)               DEFAULT NULL,
  `name`        VARCHAR(80)      NOT NULL,
  `description` TEXT,
  PRIMARY KEY (`event_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `group` (
  `group_id`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`         VARCHAR(80)      NOT NULL,
  `name`        VARCHAR(80)      NOT NULL,
  `description` TEXT,
  `base_id`     INT(10) UNSIGNED,
  PRIMARY KEY (`group_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `person` (
  `person_id`   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`         VARCHAR(80)      NOT NULL,
  `name`        VARCHAR(80)      NOT NULL,
  `description` TEXT,
  `weight`      INT(10) UNSIGNED NOT NULL DEFAULT '10'
  COMMENT 'This is a temporary measure - importance of the character',
  PRIMARY KEY (`person_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `reputation_event` (
  `reputation_event_id` INT(10) UNSIGNED                                                     NOT NULL AUTO_INCREMENT,
  `rep_network_code`    CHAR(16)                                                             NOT NULL,
  `change`              INT(11)                                                              NOT NULL DEFAULT '0',
  `event_id`            INT(10) UNSIGNED                                                              DEFAULT NULL,
  `person_id`           INT(10) UNSIGNED                                                     NOT NULL,
  `participation`       ENUM('command', 'action', 'assistance', 'presence', 'counteraction') NOT NULL DEFAULT 'action'
  COMMENT 'How much was the person involved in the situation; this should influence change',
  PRIMARY KEY (`reputation_event_id`),
  KEY `event_id` (`event_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `reputation_event_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  CONSTRAINT `reputation_event_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `group_members` (
  `group_id`  INT(10) UNSIGNED NOT NULL,
  `person_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`group_id`, `person_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
