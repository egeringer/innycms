-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: @@DB_NAME@@
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `@@DB_NAME@@`
--

/*!40000 DROP DATABASE IF EXISTS `@@DB_NAME@@`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `@@DB_NAME@@` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `@@DB_NAME@@`;

--
-- Table structure for table `bucket`
--

DROP TABLE IF EXISTS `bucket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bucket` (
  `id_bucket` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `size` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `mime` varchar(127) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `tags` text,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `usages` longtext,
  `hash` char(128) NOT NULL,
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_bucket`),
  UNIQUE KEY `id_bucket_UNIQUE` (`id_bucket`),
  FULLTEXT KEY `tags_INDEX` (`tags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bucket`
--

LOCK TABLES `bucket` WRITE;
/*!40000 ALTER TABLE `bucket` DISABLE KEYS */;
/*!40000 ALTER TABLE `bucket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bucket_chunk`
--

DROP TABLE IF EXISTS `bucket_chunk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bucket_chunk` (
  `id_bucket_chunk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_bucket` int(10) unsigned NOT NULL,
  `next_chunk` int(10) unsigned DEFAULT NULL,
  `data` mediumblob NOT NULL,
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_bucket_chunk`),
  KEY `id_bucket_index` (`id_bucket`) USING BTREE,
  CONSTRAINT `id_bucket_fk` FOREIGN KEY (`id_bucket`) REFERENCES `bucket` (`id_bucket`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bucket_chunk`
--

LOCK TABLES `bucket_chunk` WRITE;
/*!40000 ALTER TABLE `bucket_chunk` DISABLE KEYS */;
/*!40000 ALTER TABLE `bucket_chunk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collection`
--

DROP TABLE IF EXISTS `collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection` (
  `id_collection` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `public_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `site_name` varchar(40) NOT NULL,
  `metadata` longtext,
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_collection`),
  KEY `public_id_INDEX` (`public_id`),
  KEY `name_INDEX` (`name`),
  INDEX `site_name_index` (`site_name` ASC),
  INDEX `name_site_name_index` (`name` ASC, `site_name` ASC),
  CONSTRAINT `FK_site_name_collection` FOREIGN KEY (`site_name`) REFERENCES `site` (`public_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection`
--

LOCK TABLES `collection` WRITE;
/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dse_category`
--

DROP TABLE IF EXISTS `dse_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dse_category` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `id_view` int(10) unsigned DEFAULT NULL,
  `metadata` varchar(128) NOT NULL,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `index1` int(10) unsigned NOT NULL DEFAULT '0',
  `index2` int(10) unsigned NOT NULL DEFAULT '0',
  `index3` int(10) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_category`),
  KEY `INDEX_ID_VISTA` (`id_view`) USING BTREE,
  KEY `INDEX_CODE` (`code`(191)),
  KEY `INDEX_CATEGORY_INDEX1` (`index1`) USING BTREE,
  KEY `INDEX_CATEGORY_INDEX2` (`index1`),
  KEY `INDEX_CATEGORY_INDEX3` (`index2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dse_category`
--

LOCK TABLES `dse_category` WRITE;
/*!40000 ALTER TABLE `dse_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `dse_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dse_document_category`
--

DROP TABLE IF EXISTS `dse_document_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dse_document_category` (
  `id_document_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_category` int(10) unsigned NOT NULL,
  `id_document` int(10) unsigned NOT NULL,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `refs` int(10) unsigned NOT NULL DEFAULT '1',
  `index1` int(10) unsigned NOT NULL DEFAULT '0',
  `index2` int(10) unsigned NOT NULL DEFAULT '0',
  `index3` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_document_category`),
  KEY `ID_DOCUMENT_INDEX` (`id_document`),
  KEY `ID_CATEGORY_INDEX` (`id_category`),
  KEY `ID_PARENT_INDEX` (`id_parent`),
  KEY `INDEX_DOCUMENT_CATEGORY_INDEX0` (`index1`) USING BTREE,
  KEY `INDEX_DOCUMENT_CATEGORY_INDEX2` (`index2`),
  KEY `INDEX_DOCUMENT_CATEGORY_INDEX3` (`index3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dse_document_category`
--

LOCK TABLES `dse_document_category` WRITE;
/*!40000 ALTER TABLE `dse_document_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `dse_document_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dse_synonim`
--

DROP TABLE IF EXISTS `dse_synonim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dse_synonim` (
  `id_synonim` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(20) NOT NULL,
  `synonims` varchar(100) DEFAULT NULL,
  `is_synonim_of` int(10) unsigned DEFAULT NULL,
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_synonim`),
  UNIQUE KEY `WORD_INDEX` (`word`),
  KEY `WORD_TEXT_INDEX` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dse_synonim`
--

LOCK TABLES `dse_synonim` WRITE;
/*!40000 ALTER TABLE `dse_synonim` DISABLE KEYS */;
/*!40000 ALTER TABLE `dse_synonim` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field1` longtext,
  `field2` longtext,
  `field3` longtext,
  `field4` longtext,
  `field5` longtext,
  `field6` longtext,
  `field7` longtext,
  `field8` longtext,
  `field9` longtext,
  `field10` longtext,
  `field11` longtext,
  `field12` longtext,
  `field13` longtext,
  `field14` longtext,
  `field15` longtext,
  `field16` longtext,
  `field17` longtext,
  `field18` longtext,
  `field19` longtext,
  `field20` longtext,
  `files` mediumtext,
  `public_id` varchar(10) NOT NULL,
  `collection_name` varchar(100) NOT NULL,
  `site_name` varchar(40) NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '1',
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  `field_0` tinytext,
  `field_1` tinytext,
  `field_2` text,
  `category_ids` text,
  `category_names` text,
  `so_field_0` int(10) unsigned DEFAULT NULL,
  `so_field_1` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `public_id_INDEX` (`public_id`),
  KEY `public_id_collection_name_INDEX` (`public_id`, `collection_name`),
  KEY `collection_name_INDEX` (`collection_name`),
  INDEX `site_name_INDEX` (`site_name`),
  FULLTEXT KEY `FIELD_1_TEXT_INDEX` (`field_0`),
  FULLTEXT KEY `FIELD_2_TEXT_INDEX` (`field_1`),
  FULLTEXT KEY `FIELD_3_TEXT_INDEX` (`field_2`),
  FULLTEXT KEY `FULL_TEXT_INDEX` (`field_0`,`field_1`,`field_2`,`category_names`),
  FULLTEXT KEY `CATEGORY_IDS_INDEX` (`category_ids`),
  FULLTEXT KEY `CATEGORY_NAMES_INDEX` (`category_names`),
  CONSTRAINT `FK_collection_name_item` FOREIGN KEY (`collection_name`) REFERENCES `collection` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_site_name` FOREIGN KEY (`site_name`) REFERENCES `site` (`public_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site` (
  `id_site` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `public_id` varchar(40) NOT NULL,
  `url` varchar(200) NOT NULL,
  `metadata` longtext,
  `enabled` tinyint(1) NOT NULL,
  `maintenance` tinyint(1) DEFAULT '0',
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  `configs` longtext,
  `deleted` char(1) NOT NULL DEFAULT '0',
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  PRIMARY KEY (`id_site`),
  UNIQUE KEY `public_id_UNIQUE` (`public_id`),
  INDEX `public_id_INDEX` (`public_id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site`
--

LOCK TABLES `site` WRITE;
/*!40000 ALTER TABLE `site` DISABLE KEYS */;
/*!40000 ALTER TABLE `site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `enabled` char(1) NOT NULL DEFAULT '1',
  `role` varchar(10) NOT NULL DEFAULT 'siteadmin',
  `deleted` char(1) NOT NULL DEFAULT '0',
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_site`
--

DROP TABLE IF EXISTS `user_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_site` (
  `id_user` int(10) unsigned NOT NULL,
  `id_site` int(10) unsigned NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT '0',
  `permission` longtext NOT NULL,
  `aud_ins_date` datetime NOT NULL,
  `aud_upd_date` datetime NOT NULL,
  `aud_ins_user` varchar(100) NOT NULL,
  `aud_upd_user` varchar(100) NOT NULL,
  UNIQUE KEY `unique_relation` (`id_user`,`id_site`),
  KEY `FK_id_user_idx` (`id_user`),
  KEY `FK_id_site_idx` (`id_site`),
  CONSTRAINT `FK_id_site` FOREIGN KEY (`id_site`) REFERENCES `site` (`id_site`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_site`
--

LOCK TABLES `user_site` WRITE;
/*!40000 ALTER TABLE `user_site` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_site` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-22  9:42:53
