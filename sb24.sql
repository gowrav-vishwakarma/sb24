-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: sb24
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `sb24`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sb24` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sb24`;

--
-- Table structure for table `ad`
--

DROP TABLE IF EXISTS `ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adblock_id` int(11) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `last_renewal_on` date DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `add_charge` varchar(255) DEFAULT NULL,
  `ad_image_id` int(11) DEFAULT NULL,
  `click_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_adblock_id` (`adblock_id`),
  KEY `fk_ad_image_id` (`ad_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ad`
--

LOCK TABLES `ad` WRITE;
/*!40000 ALTER TABLE `ad` DISABLE KEYS */;
INSERT INTO `ad` VALUES (1,1,'2013-10-19','2013-10-31','2013-10-19','Xavoc International','Gowrav','9783807100','100',2,'http://www.xavoc.com'),(2,1,'2013-10-19',NULL,'2013-10-19','Test','11','11','11',4,NULL),(3,2,'2013-10-19',NULL,'2013-10-19','Test1','','','',6,NULL),(4,2,'2013-10-19',NULL,'2013-10-19','','','','',10,NULL),(5,3,'2013-10-19',NULL,'2013-10-19','','','','',12,NULL),(6,3,'2013-10-19',NULL,'2013-10-19','','','','',14,NULL),(7,4,'2013-10-22',NULL,'2013-10-22','','','','',22,'');
/*!40000 ALTER TABLE `ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adblock`
--

DROP TABLE IF EXISTS `adblock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adblock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `order` varchar(255) DEFAULT NULL,
  `rotation_time` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adblock`
--

LOCK TABLES `adblock` WRITE;
/*!40000 ALTER TABLE `adblock` DISABLE KEYS */;
INSERT INTO `adblock` VALUES (1,'Top Add Block','1','5','Top',1,'100'),(2,'Left Block 1','1','5','Left',1,'100'),(3,'Left Block 2','2','5','Right',1,'100'),(4,'Tourist Ad Block','2','5','Left',1,'200');
/*!40000 ALTER TABLE `adblock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adpayment`
--

DROP TABLE IF EXISTS `adpayment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adpayment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) DEFAULT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `received_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_staff_id` (`staff_id`),
  KEY `fk_ad_id` (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adpayment`
--

LOCK TABLES `adpayment` WRITE;
/*!40000 ALTER TABLE `adpayment` DISABLE KEYS */;
/*!40000 ALTER TABLE `adpayment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank_listing`
--

DROP TABLE IF EXISTS `bank_listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_listing`
--

LOCK TABLES `bank_listing` WRITE;
/*!40000 ALTER TABLE `bank_listing` DISABLE KEYS */;
INSERT INTO `bank_listing` VALUES (1,'YES Bank'),(2,'SBBJ');
/*!40000 ALTER TABLE `bank_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blooddoner`
--

DROP TABLE IF EXISTS `blooddoner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blooddoner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `want_to_donate` tinyint(1) DEFAULT NULL,
  `update_code` varchar(255) DEFAULT NULL,
  `code_valid_till` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blooddoner`
--

LOCK TABLES `blooddoner` WRITE;
/*!40000 ALTER TABLE `blooddoner` DISABLE KEYS */;
INSERT INTO `blooddoner` VALUES (1,1,NULL,0,'BLD-89337','2013-10-23',1);
/*!40000 ALTER TABLE `blooddoner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_listing`
--

DROP TABLE IF EXISTS `business_listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `company_address` text,
  `company_ph_no` varchar(255) DEFAULT NULL,
  `type_of_work` text,
  `email_id` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` text,
  `company_logo` int(11) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `image1` int(11) DEFAULT NULL,
  `image2` int(11) DEFAULT NULL,
  `image3` int(11) DEFAULT NULL,
  `image4` int(11) DEFAULT NULL,
  `image5` int(11) DEFAULT NULL,
  `about_us` text,
  `created_on` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `renewed_on` date DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `company_logo_id` int(11) DEFAULT NULL,
  `search_string` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_area_id` (`area_id`),
  KEY `fk_company_logo` (`company_logo`),
  KEY `fk_image1` (`image1`),
  KEY `fk_image2` (`image2`),
  KEY `fk_image3` (`image3`),
  KEY `fk_image4` (`image4`),
  KEY `fk_image5` (`image5`),
  KEY `fk_member_id` (`member_id`),
  KEY `fk_company_logo_id` (`company_logo_id`),
  KEY `fk_category_id` (`category_id`),
  KEY `fk_subcategory_id` (`subcategory_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_listing`
--

LOCK TABLES `business_listing` WRITE;
/*!40000 ALTER TABLE `business_listing` DISABLE KEYS */;
INSERT INTO `business_listing` VALUES (1,1,1,NULL,'Xavoc ','address','8875191258','IT Company','xavoc@xavoc.c','xavoc.com','xavoc','authorized-person','8875191258','kljlkj',16,0,NULL,NULL,NULL,NULL,NULL,'','2013-10-19',NULL,NULL,'8875191258',1,18,' Rajasthan Udaipur  Xavoc  address xavoc',NULL,NULL,NULL),(2,1,1,NULL,'Agile','Agile','5245666','65+5+','hh@hhjk.c','bjbjk.lkhlkh','hello','partner','7894566223332','qwek',NULL,1,NULL,NULL,NULL,NULL,NULL,'','2013-10-20',NULL,NULL,'4578933',1,20,' Rajasthan Udaipur  Agile Agile hello',NULL,NULL,NULL),(3,NULL,NULL,NULL,'','','','','','','',NULL,'','',NULL,0,NULL,NULL,NULL,NULL,NULL,'','2013-10-20',NULL,NULL,'',1,NULL,'IT Company      ',1,NULL,NULL),(4,NULL,NULL,NULL,'','','','','','','',NULL,'','',NULL,0,NULL,NULL,NULL,NULL,NULL,'','2013-10-20',NULL,NULL,'',1,NULL,'      ',NULL,NULL,NULL);
/*!40000 ALTER TABLE `business_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `businessdirectory_registeredcategory`
--

DROP TABLE IF EXISTS `businessdirectory_registeredcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `businessdirectory_registeredcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_id` (`category_id`),
  KEY `fk_subcategory_id` (`subcategory_id`),
  KEY `fk_listing_id` (`listing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `businessdirectory_registeredcategory`
--

LOCK TABLES `businessdirectory_registeredcategory` WRITE;
/*!40000 ALTER TABLE `businessdirectory_registeredcategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `businessdirectory_registeredcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cast`
--

DROP TABLE IF EXISTS `cast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `religion_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_religion_id` (`religion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cast`
--

LOCK TABLES `cast` WRITE;
/*!40000 ALTER TABLE `cast` DISABLE KEYS */;
INSERT INTO `cast` VALUES (1,'soni',1),(2,'JainCast',1),(3,'JainCast2',2);
/*!40000 ALTER TABLE `cast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'IT Company'),(2,'Tours');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--

LOCK TABLES `city` WRITE;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
INSERT INTO `city` VALUES (1,1,'Udaipur'),(4,1,'Jaipur'),(3,1,'Bhilwara');
/*!40000 ALTER TABLE `city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `directory`
--

DROP TABLE IF EXISTS `directory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `occupation` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `ph_no` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `religion_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `subcast_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_religion_id` (`religion_id`),
  KEY `fk_cast_id` (`cast_id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_tehsil_id` (`tehsil_id`),
  KEY `fk_area_id` (`area_id`),
  KEY `fk_subcast_id` (`subcast_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directory`
--

LOCK TABLES `directory` WRITE;
/*!40000 ALTER TABLE `directory` DISABLE KEYS */;
INSERT INTO `directory` VALUES (2,'IT','khushi','kk soni','313001','124588','xavoc@xavoc.x',1,1,1,1,NULL,NULL,1);
/*!40000 ALTER TABLE `directory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distance_listing`
--

DROP TABLE IF EXISTS `distance_listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distance_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_1_id` int(11) DEFAULT NULL,
  `city_2_id` int(11) DEFAULT NULL,
  `distance_bus` varchar(255) DEFAULT NULL,
  `distance_train` varchar(255) DEFAULT NULL,
  `distance_plane` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_1_id` (`city_1_id`),
  KEY `fk_city_2_id` (`city_2_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distance_listing`
--

LOCK TABLES `distance_listing` WRITE;
/*!40000 ALTER TABLE `distance_listing` DISABLE KEYS */;
INSERT INTO `distance_listing` VALUES (2,3,1,'4','8','9'),(5,1,4,'40','80','90');
/*!40000 ALTER TABLE `distance_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency`
--

DROP TABLE IF EXISTS `emergency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_tehsil_id` (`tehsil_id`),
  KEY `fk_category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency`
--

LOCK TABLES `emergency` WRITE;
/*!40000 ALTER TABLE `emergency` DISABLE KEYS */;
INSERT INTO `emergency` VALUES (1,1,1,1,'Rajasthan Patrika','159487263',1);
/*!40000 ALTER TABLE `emergency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency_category`
--

DROP TABLE IF EXISTS `emergency_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergency_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_category`
--

LOCK TABLES `emergency_category` WRITE;
/*!40000 ALTER TABLE `emergency_category` DISABLE KEYS */;
INSERT INTO `emergency_category` VALUES (1,'News Paper'),(2,'Police Station');
/*!40000 ALTER TABLE `emergency_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filestore_file`
--

DROP TABLE IF EXISTS `filestore_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filestore_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filestore_type_id` int(11) NOT NULL DEFAULT '0',
  `filestore_volume_id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `original_filename` varchar(255) DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `filenum` int(11) NOT NULL DEFAULT '0',
  `deleted` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filestore_file`
--

LOCK TABLES `filestore_file` WRITE;
/*!40000 ALTER TABLE `filestore_file` DISABLE KEYS */;
INSERT INTO `filestore_file` VALUES (1,1,1,'0/20131019221530_1_thumb-1ms-212660.jpg','thumb_1ms-212660.jpg',5047,0,''),(2,1,1,'0/20131019221530__1ms-212660.jpg','1ms-212660.jpg',246953,0,''),(3,4,1,'0/20131019225227_1_thumb-100-job.png','thumb_100_job.png',3798,0,''),(4,4,1,'0/20131019225227__100-job.png','100_job.png',4149,0,''),(5,1,1,'0/20131019230828_1_thumb-934-ashlee-simpson-wallpapers-hot-1231877913.jpg','thumb_934_ashlee-simpson-wallpapers-hot-1231877913.jpg',4576,0,''),(6,1,1,'0/20131019230828__934-ashlee-simpson-wallpapers-hot-1231877913.jpg','934_ashlee-simpson-wallpapers-hot-1231877913.jpg',98924,0,''),(7,1,1,'0/20131019230909_1_thumb-edit-6.jpg','thumb_edit-6.jpg',13083,0,''),(8,1,1,'0/20131019230909__edit-6.jpg','edit-6.jpg',563909,0,''),(9,1,1,'0/20131019230923_1_thumb-edit-6.jpg','thumb_edit-6.jpg',13083,0,''),(10,1,1,'0/20131019230923__edit-6.jpg','edit-6.jpg',563909,0,''),(11,1,1,'0/20131019231033_1_thumb-74727-374316649325100-214148300-n.jpg','thumb_74727_374316649325100_214148300_n.jpg',4916,0,''),(12,1,1,'0/20131019231033__74727-374316649325100-214148300-n.jpg','74727_374316649325100_214148300_n.jpg',32948,0,''),(13,1,1,'0/20131019231051_1_thumb-girl.jpeg','thumb_girl.jpeg',6803,0,''),(14,1,1,'0/20131019231051__girl.jpeg','girl.jpeg',449731,0,''),(15,1,1,'0/20131020124358_1_thumb-1.jpg','thumb_1.jpg',8524,0,''),(16,1,1,'0/20131020124357__1.jpg','1.jpg',107739,0,''),(17,1,1,'0/20131020124519_1_thumb-1.jpg','thumb_1.jpg',8524,0,''),(18,1,1,'0/20131020124519__1.jpg','1.jpg',107739,0,''),(19,4,1,'0/20131020134742_1_thumb-100-job.png','thumb_100_job.png',3798,0,''),(20,4,1,'0/20131020134742__100-job.png','100_job.png',4149,0,''),(21,1,1,'0/20131022103030_1_thumb-31332-640x480.jpg','thumb_31332-640x480.jpg',11807,0,''),(22,1,1,'0/20131022103030__31332-640x480.jpg','31332-640x480.jpg',152111,0,'');
/*!40000 ALTER TABLE `filestore_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filestore_image`
--

DROP TABLE IF EXISTS `filestore_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filestore_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `original_file_id` int(11) NOT NULL DEFAULT '0',
  `thumb_file_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filestore_image`
--

LOCK TABLES `filestore_image` WRITE;
/*!40000 ALTER TABLE `filestore_image` DISABLE KEYS */;
INSERT INTO `filestore_image` VALUES (1,NULL,2,1),(2,NULL,4,3),(3,NULL,6,5),(4,NULL,8,7),(5,NULL,10,9),(6,NULL,12,11),(7,NULL,14,13),(8,NULL,16,15),(9,NULL,18,17),(10,NULL,20,19),(11,NULL,22,21);
/*!40000 ALTER TABLE `filestore_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filestore_type`
--

DROP TABLE IF EXISTS `filestore_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filestore_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(64) NOT NULL DEFAULT '',
  `extension` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filestore_type`
--

LOCK TABLES `filestore_type` WRITE;
/*!40000 ALTER TABLE `filestore_type` DISABLE KEYS */;
INSERT INTO `filestore_type` VALUES (1,'jpg','image/jpeg','jpg'),(2,'jpeg','image/jpeg','jpeg'),(3,'gif','image/gif','gif'),(4,'png','image/png','png');
/*!40000 ALTER TABLE `filestore_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filestore_volume`
--

DROP TABLE IF EXISTS `filestore_volume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filestore_volume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `dirname` varchar(255) NOT NULL DEFAULT '',
  `total_space` bigint(20) NOT NULL DEFAULT '0',
  `used_space` bigint(20) NOT NULL DEFAULT '0',
  `stored_files_cnt` int(11) NOT NULL DEFAULT '0',
  `enabled` enum('Y','N') DEFAULT 'Y',
  `last_filenum` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filestore_volume`
--

LOCK TABLES `filestore_volume` WRITE;
/*!40000 ALTER TABLE `filestore_volume` DISABLE KEYS */;
INSERT INTO `filestore_volume` VALUES (1,'upload','upload',1000000000,0,3434,'Y',NULL);
/*!40000 ALTER TABLE `filestore_volume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobandvacancy_listing`
--

DROP TABLE IF EXISTS `jobandvacancy_listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobandvacancy_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `vacancy` varchar(255) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_id` (`category_id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobandvacancy_listing`
--

LOCK TABLES `jobandvacancy_listing` WRITE;
/*!40000 ALTER TABLE `jobandvacancy_listing` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobandvacancy_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `is_staff` tinyint(1) DEFAULT NULL,
  `update_code` varchar(255) DEFAULT NULL,
  `code_valid_till` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `address` text,
  `search_string` varchar(255) DEFAULT NULL,
  `religion_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `subcast_id` int(11) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `joined_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_religion_id` (`religion_id`),
  KEY `fk_cast_id` (`cast_id`),
  KEY `fk_subcast_id` (`subcast_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'Admin Member','khushi','378','8875191258',1,'SB24-75153','2013-10-23',1,1,1,'Address','Admin Member 8875191258 Udaipur Rajasthan Address ',1,1,NULL,'Admin Father',NULL,NULL);
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mirc_Listing`
--

DROP TABLE IF EXISTS `mirc_Listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mirc_Listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `mirc` varchar(255) DEFAULT NULL,
  `ifsc` varchar(255) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_bank_id` (`bank_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mirc_Listing`
--

LOCK TABLES `mirc_Listing` WRITE;
/*!40000 ALTER TABLE `mirc_Listing` DISABLE KEYS */;
INSERT INTO `mirc_Listing` VALUES (1,1,1,'123','456',1,'Panchwati'),(2,1,1,'102030','405060',2,'Univercity Road'),(3,1,4,'159','951',1,'Gandhi Nagar'),(4,1,4,'753','357',1,'Panchwati'),(5,1,4,'158741','254865',2,'SBBJ Jaipur Branch');
/*!40000 ALTER TABLE `mirc_Listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pincode_Listing`
--

DROP TABLE IF EXISTS `pincode_Listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pincode_Listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `pin_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pincode_Listing`
--

LOCK TABLES `pincode_Listing` WRITE;
/*!40000 ALTER TABLE `pincode_Listing` DISABLE KEYS */;
INSERT INTO `pincode_Listing` VALUES (1,1,1,'MAIN','313001'),(2,1,1,'SECTORS','313002');
/*!40000 ALTER TABLE `pincode_Listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `place`
--

DROP TABLE IF EXISTS `place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `placetype_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_area_id` (`area_id`),
  KEY `fk_placetype_id` (`placetype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `place`
--

LOCK TABLES `place` WRITE;
/*!40000 ALTER TABLE `place` DISABLE KEYS */;
INSERT INTO `place` VALUES (1,1,2,NULL,2,'Jagdish Mandir','near my home\r\nsdfdfsd');
/*!40000 ALTER TABLE `place` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placetype`
--

DROP TABLE IF EXISTS `placetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `placetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placetype`
--

LOCK TABLES `placetype` WRITE;
/*!40000 ALTER TABLE `placetype` DISABLE KEYS */;
INSERT INTO `placetype` VALUES (1,'Temple'),(2,'Village');
/*!40000 ALTER TABLE `placetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `religion`
--

DROP TABLE IF EXISTS `religion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `religion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `religion`
--

LOCK TABLES `religion` WRITE;
/*!40000 ALTER TABLE `religion` DISABLE KEYS */;
INSERT INTO `religion` VALUES (1,'Hindu'),(2,'Rajpoot'),(3,'Jain'),(4,'Jain2'),(5,'jain3');
/*!40000 ALTER TABLE `religion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rtocode_Listing`
--

DROP TABLE IF EXISTS `rtocode_Listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rtocode_Listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rtocode_Listing`
--

LOCK TABLES `rtocode_Listing` WRITE;
/*!40000 ALTER TABLE `rtocode_Listing` DISABLE KEYS */;
INSERT INTO `rtocode_Listing` VALUES (1,1,1,'RJ-27');
/*!40000 ALTER TABLE `rtocode_Listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_listing`
--

DROP TABLE IF EXISTS `social_listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `religion_id` int(11) DEFAULT NULL,
  `cast_id` int(11) DEFAULT NULL,
  `subcast_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `tehsil_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `ph_no` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `created_on` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `renewed_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_religion_id` (`religion_id`),
  KEY `fk_cast_id` (`cast_id`),
  KEY `fk_subcast_id` (`subcast_id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`),
  KEY `fk_tehsil_id` (`tehsil_id`),
  KEY `fk_area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_listing`
--

LOCK TABLES `social_listing` WRITE;
/*!40000 ALTER TABLE `social_listing` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'admin','admin');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
INSERT INTO `state` VALUES (1,'Rajasthan');
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `std_Listing`
--

DROP TABLE IF EXISTS `std_Listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `std_Listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `STD_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_state_id` (`state_id`),
  KEY `fk_city_id` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `std_Listing`
--

LOCK TABLES `std_Listing` WRITE;
/*!40000 ALTER TABLE `std_Listing` DISABLE KEYS */;
INSERT INTO `std_Listing` VALUES (1,1,1,'Girwa','0294'),(2,1,1,'Mavli','0295'),(3,1,3,'PUR Road','0148');
/*!40000 ALTER TABLE `std_Listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_category`
--

DROP TABLE IF EXISTS `sub_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_category`
--

LOCK TABLES `sub_category` WRITE;
/*!40000 ALTER TABLE `sub_category` DISABLE KEYS */;
INSERT INTO `sub_category` VALUES (1,1,'web Designing'),(2,1,'Hardware'),(3,2,'Travel'),(4,2,'Hotels'),(5,1,'Software'),(6,1,'Dealership');
/*!40000 ALTER TABLE `sub_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcast`
--

DROP TABLE IF EXISTS `subcast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cast_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cast_id` (`cast_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcast`
--

LOCK TABLES `subcast` WRITE;
/*!40000 ALTER TABLE `subcast` DISABLE KEYS */;
INSERT INTO `subcast` VALUES (1,3,'aaaaa');
/*!40000 ALTER TABLE `subcast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tehsil`
--

DROP TABLE IF EXISTS `tehsil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tehsil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_city_id` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tehsil`
--

LOCK TABLES `tehsil` WRITE;
/*!40000 ALTER TABLE `tehsil` DISABLE KEYS */;
INSERT INTO `tehsil` VALUES (1,1,'Girwa'),(2,1,'Mavli');
/*!40000 ALTER TABLE `tehsil` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-10-22 15:11:38
