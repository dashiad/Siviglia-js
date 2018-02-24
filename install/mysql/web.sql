DROP DATABASE IF EXISTS adtopy;
CREATE DATABASE adtopy;
use adtopy;

 CREATE TABLE `_permissions` (
  `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `aro_type` smallint(1) DEFAULT NULL,
  `aco_type` smallint(1) DEFAULT NULL,
  `aro_id` smallint(8) DEFAULT NULL,
  `aco_id` smallint(8) DEFAULT NULL,
  `axo_type` smallint(1) DEFAULT '0',
  `axo_id` smallint(8) DEFAULT '0',
  `allow` smallint(1) DEFAULT '1',
  `enabled` smallint(1) DEFAULT '1',
  `ACLDATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aro_type` (`aro_type`,`aro_id`,`aco_type`,`aco_id`,`axo_type`,`axo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `_permission_items` (
  `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `item_type` smallint(2) DEFAULT NULL,
  `item_name` varchar(20) DEFAULT NULL,
  `item_value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_type` (`item_type`,`item_name`,`item_value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `_permission_groups` (
  `id` smallint(8) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) DEFAULT NULL,
  `group_type` smallint(2) DEFAULT NULL,
  `group_parent` smallint(8) DEFAULT '0',
  `group_path` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_type` (`group_type`,`group_name`,`group_parent`),
  KEY `group_name` (`group_name`,`group_path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 CREATE TABLE `_permission_group_items` (
  `group_id` smallint(8) DEFAULT NULL,
  `item_id` smallint(8) DEFAULT NULL,
  KEY `group_id` (`group_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `websites` (
  `id_site` int(11) NOT NULL AUTO_INCREMENT,
  `host` char(40) DEFAULT NULL,
  `canonical_url` char(255) DEFAULT NULL,
  `hasSSL` tinyint(1) DEFAULT NULL,
  `namespace` char(45) DEFAULT NULL,
  `websiteName` char(45) DEFAULT NULL,
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 CREATE TABLE `Websites` (
  `id_website` int(11) NOT NULL AUTO_INCREMENT,
  `websiteName` char(20) DEFAULT NULL,
  `namespace` char(20) DEFAULT NULL,
  `id_home` int(8) DEFAULT NULL,
  `id_lang` int(11) DEFAULT NULL,
  `mail_user` char(100) DEFAULT '',
  `mail_passwd` char(30) DEFAULT '',
  `mail_server` char(100) DEFAULT '',
  `id_default_country` int(11) DEFAULT NULL,
  `id_default_zone` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_website`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
CREATE TABLE `WebsiteEditors` (
  `id_editor` int(11) NOT NULL AUTO_INCREMENT,
  `login` char(20) DEFAULT NULL,
  `passwd` char(50) DEFAULT NULL,
  `enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_editor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `WebsiteEditorPermissions` (
  `id_editor` int(11) DEFAULT NULL,
  `id_website` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Translations` (
  `id_translation` int(11) NOT NULL AUTO_INCREMENT,
  `id_string` char(40) CHARACTER SET latin1 DEFAULT NULL,
  `lang` char(10) CHARACTER SET latin1 DEFAULT NULL,
  `dirty` tinyint(4) DEFAULT '0',
  `value` text CHARACTER SET utf8 COLLATE utf8_bin,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `realm` char(8) DEFAULT NULL,
  PRIMARY KEY (`id_translation`),
  UNIQUE KEY `i1` (`id_string`,`lang`),
  KEY `realm` (`realm`),
  KEY `realm_2` (`realm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `CustomSections` (
  `id_section` int(11) NOT NULL AUTO_INCREMENT,
  `id_website` int(3) DEFAULT NULL,
  `NAME` char(30) DEFAULT NULL,
  `tag` char(30) DEFAULT NULL,
  `DATE_ADD` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `id_type` int(5) DEFAULT NULL,
  `isPrivate` tinyint(1) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `title` char(200) DEFAULT NULL,
  `tags` char(255) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  PRIMARY KEY (`id_section`),
  KEY `a1` (`id_website`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 CREATE TABLE `CustomSectionResources` (
  `id_resource` int(11) NOT NULL AUTO_INCREMENT,
  `id_section` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `title` char(100) DEFAULT NULL,
  `description` char(100) DEFAULT NULL,
  PRIMARY KEY (`id_resource`),
  KEY `a1` (`id_section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `CustomSectionTypes` (
  `id_sectionType` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` char(30) DEFAULT NULL,
  `allowResources` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_sectionType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO CustomSectionTypes (id_sectionType,typeName,allowResources) VALUES (1,"Section",1),(2,"Email",0),(3,"Widget",0);

 CREATE TABLE `WebUser` (
  `LOGIN` varchar(15) DEFAULT '',
  `PASSWORD` varchar(16) DEFAULT '',
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `lastLogin` datetime DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT '',
  `ROLE` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `WebUser` (
  `LOGIN` varchar(15) DEFAULT '''',
  `PASSWORD` varchar(16) DEFAULT '''',
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT ''0'',
  `lastLogin` datetime DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT '''',
  `ROLE` varchar(50) NOT NULL DEFAULT '''',
  `FAILEDLOGINATTEMPTS` int(4) DEFAULT ''0'',
  `date_add` datetime DEFAULT NULL,
  `date_upd` datetime DEFAULT NULL,
  `firstname` char(128) DEFAULT NULL,
  `lastname` char(128) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT ''0'',
  `last_passwd_gen` datetime DEFAULT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8
