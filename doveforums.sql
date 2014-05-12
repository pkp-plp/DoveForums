# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.34)
# Database: Forums
# Generation Time: 2014-05-12 19:45:05 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `description` text,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `name`, `permalink`, `description`, `order`)
VALUES
	(1,'General','general','This is the general category.',1),
	(2,'Test','test','This is a second test category.',2);

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text,
  `discussion_id` int(16) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `created_ip` varchar(255) DEFAULT NULL,
  `modified_by` varchar(255) DEFAULT NULL,
  `modified_reason` text,
  `modified_date` varchar(255) DEFAULT NULL,
  `modified_ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;

INSERT INTO `comments` (`comment_id`, `comment`, `discussion_id`, `created_by`, `created_date`, `created_ip`, `modified_by`, `modified_reason`, `modified_date`, `modified_ip`)
VALUES
	(1,'This is a test discussion.',1,'1','1399913559','::1',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table discussions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `discussions`;

CREATE TABLE `discussions` (
  `discussion_id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(16) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `created_ip` varchar(255) DEFAULT NULL,
  `last_comment_by` varchar(255) DEFAULT NULL,
  `last_comment_date` varchar(255) DEFAULT NULL,
  `last_comment_ip` varchar(255) DEFAULT NULL,
  `permalink` varchar(255) DEFAULT NULL,
  `answered` varchar(255) DEFAULT '0',
  `likes` int(16) DEFAULT '0',
  `announcement` int(11) DEFAULT '0',
  `closed` int(11) DEFAULT '0',
  PRIMARY KEY (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `discussions` WRITE;
/*!40000 ALTER TABLE `discussions` DISABLE KEYS */;

INSERT INTO `discussions` (`discussion_id`, `category_id`, `name`, `created_by`, `created_date`, `created_ip`, `last_comment_by`, `last_comment_date`, `last_comment_ip`, `permalink`, `answered`, `likes`, `announcement`, `closed`)
VALUES
	(1,1,'Test Discussion','1','1399913559','::1','1','1399913559','::1','test-discussion','0',0,0,0);

/*!40000 ALTER TABLE `discussions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `display_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `description`, `display_name`)
VALUES
	(1,'admin','Admin usergroup.','Administrator'),
	(2,'members','Members usergroup.','Members');

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `permission`, `key`, `category`)
VALUES
	(1,'Create Discussions','create_discussions','Discussions'),
	(2,'Edit Discussions','edit_discussions','Discussions'),
	(3,'Delete Discussions','delete_discussions','Discussions');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions_map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions_map`;

CREATE TABLE `permissions_map` (
  `group_id` int(16) NOT NULL,
  `permission_id` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permissions_map` WRITE;
/*!40000 ALTER TABLE `permissions_map` DISABLE KEYS */;

INSERT INTO `permissions_map` (`group_id`, `permission_id`)
VALUES
	(1,2),
	(1,1),
	(1,3),
	(2,1);

/*!40000 ALTER TABLE `permissions_map` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ranks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ranks`;

CREATE TABLE `ranks` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `rank` varchar(255) DEFAULT NULL,
  `min_xp` varchar(255) DEFAULT NULL,
  `max_xp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `ranks` WRITE;
/*!40000 ALTER TABLE `ranks` DISABLE KEYS */;

INSERT INTO `ranks` (`id`, `rank`, `min_xp`, `max_xp`)
VALUES
	(1,'Junior','0','10'),
	(2,'Newbie','11','20'),
	(3,'Nerd','21','50');

/*!40000 ALTER TABLE `ranks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` mediumtext NOT NULL,
  `option_group` varchar(55) NOT NULL DEFAULT 'site',
  `auto_load` enum('no','yes') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`,`option_name`),
  KEY `option_name` (`option_name`),
  KEY `auto_load` (`auto_load`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`option_id`, `option_name`, `option_value`, `option_group`, `auto_load`)
VALUES
	(1,'script_version','','script','yes'),
	(2,'script_build','','script','yes'),
	(3,'script_db_version','','script','yes'),
	(4,'site_name','Dove Forums','site','yes'),
	(5,'site_keywords','keywords, go, here','site','yes'),
	(6,'site_description','Demo Site description','site','yes'),
	(7,'site_email','noreply@example.com','site','yes'),
	(8,'sidebar_display','right','settings','yes'),
	(10,'frontend_theme','default','settings','yes'),
	(11,'admin_theme','default','settings','yes'),
	(12,'site_language','en','settings','yes'),
	(13,'site_author','Chris Baines','site','yes'),
	(14,'discussions_per_page','10','discussions','yes'),
	(15,'gravatar_rating','g','gravatar','yes'),
	(16,'default_image','mm','gravatar','yes'),
	(17,'comments_per_page','10','comments','yes');

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(16) DEFAULT '0',
  `ip_address` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `xp` varchar(255) DEFAULT '0',
  `last_active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `group_id`, `ip_address`, `username`, `password`, `email`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `signature`, `xp`, `last_active`)
VALUES
	(1,1,'0.0.0.0','Tester','$2a$08$3nm4p8naR4MZcaqRPDEXS.KbHFxFp1F0Mg6xVnXcW6WXeC0C9MgGO','tester@test.com',1398673255,1399913534,1,'Admin','istrator','Test Signature','2',NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
