-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: kbmdb
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.9-MariaDB

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
-- Table structure for table `auth`
--

DROP TABLE IF EXISTS `auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varbinary(250) NOT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth`
--

LOCK TABLES `auth` WRITE;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` VALUES (25,'0b40d6b7b7c09b12a1ba1cc5a96c670d8fa691e62ffb96295cc873807d25106a',3,'2018-05-25 05:53:00');
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `code`
--

DROP TABLE IF EXISTS `code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_User_Type` (`user`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `code`
--

LOCK TABLES `code` WRITE;
/*!40000 ALTER TABLE `code` DISABLE KEYS */;
/*!40000 ALTER TABLE `code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `knowledge_base` int(11) NOT NULL,
  `folder` int(11) NOT NULL,
  `name` varchar(70) NOT NULL DEFAULT 'UNNAMED DOCUMENT',
  `description` varchar(140) NOT NULL DEFAULT 'No description',
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_Name_Folder` (`folder`,`name`),
  KEY `knowledge_base` (`knowledge_base`),
  CONSTRAINT `document_ibfk_1` FOREIGN KEY (`knowledge_base`) REFERENCES `knowledge_base` (`id`),
  CONSTRAINT `document_ibfk_2` FOREIGN KEY (`folder`) REFERENCES `folder` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
INSERT INTO `document` VALUES (1,18,55,'Loch Ness Monster','A brief description about Nessie','<p>The&nbsp;<strong>Loch Ness Monster</strong> or&nbsp;<strong>Nessie</strong> is a&nbsp;<a href=\"https://en.wikipedia.org/wiki/Cryptid\" title=\"Cryptid\">cryptid</a> of&nbsp;<a href=\"https://en.wikipedia.org/wiki/Scottish_folklore\" title=\"Scottish folklore\">Scottish folklore</a>, reputedly inhabiting&nbsp;<a href=\"https://en.wikipedia.org/wiki/Loch_Ness\" title=\"Loch Ness\">Loch Ness</a> in the&nbsp;<a href=\"https://en.wikipedia.org/wiki/Scottish_Highlands\" title=\"Scottish Highlands\">Scottish Highlands</a>. It is similar to other supposed&nbsp;<a href=\"https://en.wikipedia.org/wiki/Lake_monster\" title=\"Lake monster\">lake monsters</a> in Scotland and elsewhere, and is often described as being large in size, with a long neck and one or more humps protruding from the water. Popular interest and belief in the creature has varied since it was brought to worldwide attention in 1933. Evidence of its existence is anecdotal, with a few disputed photographs and&nbsp;<a href=\"https://en.wikipedia.org/wiki/Sonar\" title=\"Sonar\">sonar</a> readings.</p><p>The creature commonly appears in Western media where it manifests in a variety of ways. The scientific community regards the Loch Ness Monster as a phenomenon without biological basis, explaining sightings as <a href=\"https://en.wikipedia.org/wiki/Hoax\" title=\"Hoax\">hoaxes</a>, <a href=\"https://en.wikipedia.org/wiki/Wishful_thinking\" title=\"Wishful thinking\">wishful thinking</a>, and the misidentification of mundane objects.</p><p>The word \"monster\" was reportedly applied for the first time to the creature on 2 May 1933 by Alex Campbell, <a href=\"https://en.wikipedia.org/wiki/Water_bailiff\" title=\"Water bailiff\">water bailiff</a> for <a href=\"https://en.wikipedia.org/wiki/Loch_Ness\" title=\"Loch Ness\">Loch Ness</a> and a part-time journalist, in an <em><a href=\"https://en.wikipedia.org/wiki/The_Inverness_Courier\" title=\"The Inverness Courier\">Inverness Courier</a></em> report. On 4 August 1933 the <em>Courier</em> published a report by Londoner George Spicer that several weeks earlier, while they were driving around the loch, he and his wife saw \"the nearest approach to a dragon or pre-historic animal that I have ever seen in my life\" trundling across the road toward the loch with \"an animal\" in its mouth. Letters began appearing in the <em>Courier</em>, often anonymously, claiming land or water sightings by the writer, their family or acquaintances or remembered stories. The accounts reached the media, which described a \"monster fish\", \"sea serpent\", or \"dragon\" and eventually settled on \"Loch Ness monster\".</p><p>On 6 December 1933 the first purported photograph of the monster, taken by Hugh Gray, was published in the&nbsp;<em><a href=\"https://en.wikipedia.org/wiki/Daily_Express\" title=\"Daily Express\">Daily Express</a></em>;&nbsp;&nbsp;the&nbsp;<a href=\"https://en.wikipedia.org/wiki/Secretary_of_State_for_Scotland\" title=\"Secretary of State for Scotland\">Secretary of State for Scotland</a> soon ordered police to prevent any attacks on it.&nbsp;In 1934, interest was further piqued by the \"surgeon\'s photograph\". That year,&nbsp;<a href=\"https://en.wikipedia.org/wiki/R._T._Gould\" title=\"R. T. Gould\">R. T. Gould</a> published an account of the author\'s investigation and a record of reports predating 1933. Other authors have claimed sightings of the monster dating to the sixth century AD.</p>',1);
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_tag`
--

DROP TABLE IF EXISTS `document_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL,
  `tag` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_Document_Tag` (`document`,`tag`),
  KEY `tag` (`tag`),
  CONSTRAINT `document_tag_ibfk_1` FOREIGN KEY (`document`) REFERENCES `document` (`id`),
  CONSTRAINT `document_tag_ibfk_2` FOREIGN KEY (`tag`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_tag`
--

LOCK TABLES `document_tag` WRITE;
/*!40000 ALTER TABLE `document_tag` DISABLE KEYS */;
INSERT INTO `document_tag` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4);
/*!40000 ALTER TABLE `document_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folder`
--

DROP TABLE IF EXISTS `folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `knowledge_base` int(11) NOT NULL,
  `parent_folder` int(11) DEFAULT NULL,
  `name` varchar(20) NOT NULL DEFAULT 'UNNAMED FOLDER',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_Name_Folder` (`parent_folder`,`name`),
  KEY `knowledge_base` (`knowledge_base`),
  CONSTRAINT `folder_ibfk_1` FOREIGN KEY (`knowledge_base`) REFERENCES `knowledge_base` (`id`),
  CONSTRAINT `folder_ibfk_2` FOREIGN KEY (`parent_folder`) REFERENCES `folder` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folder`
--

LOCK TABLES `folder` WRITE;
/*!40000 ALTER TABLE `folder` DISABLE KEYS */;
INSERT INTO `folder` VALUES (1,1,NULL,'root-1',1),(2,2,NULL,'root-2',1),(3,3,NULL,'root-3',1),(4,3,3,'First Folder',1),(5,3,3,'Second Folder',1),(6,3,3,'Third Folder',1),(7,2,2,'A folder',1),(8,2,2,'B folder',1),(9,2,2,'C folder',1),(10,1,1,'R1 Folder',1),(11,1,1,'R2 Folder',1),(12,1,1,'R3 Folder',1),(13,4,NULL,'root-4',1),(15,4,13,'Yohto',1),(16,4,13,'Kanto',1),(17,4,13,'Hoen',1),(18,4,13,'Sinho',1),(19,4,13,'Totodile',1),(20,5,NULL,'root-5',1),(21,5,20,'Before',1),(22,6,NULL,'root-6',1),(23,7,NULL,'root-7',1),(24,7,23,'Summoners Rift',1),(25,7,23,'Twisted Treeline',1),(26,7,23,'Targon Mount',1),(27,7,23,'Piltover',1),(28,7,23,'Demacia',1),(29,7,23,'Noxus',1),(30,8,NULL,'root-8',1),(31,8,30,'Number 1',1),(32,8,30,'Number 2',1),(33,8,30,'Number 3',1),(34,8,30,'Number 4',1),(35,9,NULL,'root-9',1),(36,9,35,'Light Pink',1),(37,9,35,'Regular Pink',1),(38,9,35,'Dark Pink',1),(39,10,NULL,'root-10',1),(40,10,39,'Iron Man',1),(41,10,39,'Hulk',1),(42,10,39,'Captain America',1),(43,11,NULL,'root-11',1),(44,11,43,'Superman',1),(45,11,43,'Batman',1),(46,12,NULL,'root-12',1),(47,13,NULL,'root-13',1),(48,14,NULL,'root-14',1),(49,14,48,'Structure',1),(50,14,48,'Content',1),(51,14,48,'Title',1),(52,15,NULL,'root-15',1),(53,16,NULL,'root-16',1),(54,17,NULL,'root-17',1),(55,18,NULL,'root-18',1),(56,19,NULL,'root-19',1);
/*!40000 ALTER TABLE `folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knowledge_base`
--

DROP TABLE IF EXISTS `knowledge_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knowledge_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `description` varchar(140) NOT NULL DEFAULT 'No description',
  `privacy` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knowledge_base`
--

LOCK TABLES `knowledge_base` WRITE;
/*!40000 ALTER TABLE `knowledge_base` DISABLE KEYS */;
INSERT INTO `knowledge_base` VALUES (1,'Random KB Title','Description of a Random Knowledge Base',1,1),(2,'Public Knowledge Base','This is a public Knowledge Base',1,1),(3,'Lorem Ipsum','Lorem Ipsum Dolor Sit Amet',2,1),(4,'Pikachu Knowledge Base','The most amazing knowledge base about pikachu',1,1),(5,'AngularJS','All You need to know about AngularJS',1,1),(6,'NodeJS','Why is it so good',2,1),(7,'League of Legends','Talking about the koreans getting over the world',1,1),(8,'How to be Amazing','The short Guide',1,1),(9,'Why Pink','The ultimate guide about pink',2,1),(10,'MARVEL','Why id it better than DC',1,1),(11,'DC','Why is it worst than MARVEL',1,1),(12,'McLaren','The long documentation',2,1),(13,'Making use or both Names','All reasons why I like my name',2,1),(14,'How to build a Knowledge Base','The nevertold guide',1,1),(15,'Private Workgroups','How to stablish priorities',2,1),(16,'Top Secret','No description available',2,1),(17,'Proper Makeup','The final documentation',2,1),(18,'Unknown Knowledge Base','Unknown description',1,1),(19,'McLaren','Inspiring history of Bruce McLaren',1,1);
/*!40000 ALTER TABLE `knowledge_base` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `knowledge_base` int(11) NOT NULL,
  `role` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_Permission` (`user`,`knowledge_base`),
  KEY `knowledge_base` (`knowledge_base`),
  CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  CONSTRAINT `permission_ibfk_2` FOREIGN KEY (`knowledge_base`) REFERENCES `knowledge_base` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,4,1,1),(2,1,1,2),(3,2,1,3),(4,7,1,3),(5,4,2,1),(6,4,3,1),(7,2,4,1),(8,4,4,2),(9,6,5,1),(10,6,6,1),(11,2,6,3),(12,1,7,1),(13,2,7,3),(14,7,7,2),(15,4,7,3),(16,6,7,2),(17,5,8,1),(18,5,9,1),(19,7,9,2),(20,3,10,1),(21,6,10,2),(22,5,10,2),(23,3,11,1),(24,8,12,1),(25,1,12,3),(26,4,12,3),(27,9,12,2),(28,7,13,1),(29,7,14,1),(30,9,15,1),(31,9,16,1),(32,10,17,1),(33,7,17,3),(34,5,17,3),(35,2,17,3),(36,12,18,1),(37,4,18,2),(38,7,18,3),(39,5,18,3),(40,1,18,3),(41,9,18,3),(42,4,19,1);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `knowledge_base` int(11) NOT NULL,
  `folder` int(11) DEFAULT NULL,
  `document` int(11) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `knowledge_base` (`knowledge_base`),
  KEY `folder` (`folder`),
  KEY `document` (`document`),
  KEY `user` (`user`),
  CONSTRAINT `register_ibfk_1` FOREIGN KEY (`knowledge_base`) REFERENCES `knowledge_base` (`id`),
  CONSTRAINT `register_ibfk_2` FOREIGN KEY (`folder`) REFERENCES `folder` (`id`),
  CONSTRAINT `register_ibfk_3` FOREIGN KEY (`document`) REFERENCES `document` (`id`),
  CONSTRAINT `register_ibfk_4` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `register`
--

LOCK TABLES `register` WRITE;
/*!40000 ALTER TABLE `register` DISABLE KEYS */;
INSERT INTO `register` VALUES (1,1,NULL,NULL,4,'2018-05-21 08:28:37','Create Knowledge Base'),(2,1,1,NULL,4,'2018-05-21 08:28:37','Create Knowledge Base'),(3,1,NULL,NULL,4,'2018-05-21 08:28:57','Updated Permissions'),(4,1,NULL,NULL,4,'2018-05-21 08:28:59','Updated Permissions'),(5,1,NULL,NULL,4,'2018-05-21 08:29:04','Updated Permissions'),(6,1,NULL,NULL,4,'2018-05-21 08:29:09','Updated Permissions'),(7,2,NULL,NULL,4,'2018-05-21 08:29:44','Create Knowledge Base'),(8,2,2,NULL,4,'2018-05-21 08:29:44','Create Knowledge Base'),(9,3,NULL,NULL,4,'2018-05-21 08:30:21','Create Knowledge Base'),(10,3,3,NULL,4,'2018-05-21 08:30:21','Create Knowledge Base'),(11,3,4,NULL,4,'2018-05-21 08:30:37','Create Folder'),(12,3,3,NULL,4,'2018-05-21 08:30:37','Create Folder'),(13,3,5,NULL,4,'2018-05-21 08:30:48','Create Folder'),(14,3,3,NULL,4,'2018-05-21 08:30:48','Create Folder'),(15,3,6,NULL,4,'2018-05-21 08:32:17','Create Folder'),(16,3,3,NULL,4,'2018-05-21 08:32:17','Create Folder'),(17,2,7,NULL,4,'2018-05-21 08:32:49','Create Folder'),(18,2,2,NULL,4,'2018-05-21 08:32:49','Create Folder'),(19,2,8,NULL,4,'2018-05-21 08:32:56','Create Folder'),(20,2,2,NULL,4,'2018-05-21 08:32:56','Create Folder'),(21,2,9,NULL,4,'2018-05-21 08:33:02','Create Folder'),(22,2,2,NULL,4,'2018-05-21 08:33:02','Create Folder'),(23,1,10,NULL,4,'2018-05-21 08:34:27','Create Folder'),(24,1,1,NULL,4,'2018-05-21 08:34:27','Create Folder'),(25,1,11,NULL,4,'2018-05-21 08:34:34','Create Folder'),(26,1,1,NULL,4,'2018-05-21 08:34:34','Create Folder'),(27,1,12,NULL,4,'2018-05-21 08:34:40','Create Folder'),(28,1,1,NULL,4,'2018-05-21 08:34:40','Create Folder'),(29,4,NULL,NULL,2,'2018-05-21 08:37:54','Create Knowledge Base'),(30,4,13,NULL,2,'2018-05-21 08:37:54','Create Knowledge Base'),(32,4,13,NULL,2,'2018-05-21 08:38:05','Create Folder'),(33,4,15,NULL,2,'2018-05-21 08:38:17','Create Folder'),(34,4,13,NULL,2,'2018-05-21 08:38:17','Create Folder'),(35,4,16,NULL,2,'2018-05-21 08:38:26','Create Folder'),(36,4,13,NULL,2,'2018-05-21 08:38:26','Create Folder'),(37,4,17,NULL,2,'2018-05-21 08:38:33','Create Folder'),(38,4,13,NULL,2,'2018-05-21 08:38:33','Create Folder'),(39,4,18,NULL,2,'2018-05-21 08:38:40','Create Folder'),(40,4,13,NULL,2,'2018-05-21 08:38:40','Create Folder'),(41,4,NULL,NULL,2,'2018-05-21 08:39:12','Updated Permissions'),(42,4,NULL,NULL,2,'2018-05-21 08:39:19','Updated Permissions'),(43,4,19,NULL,4,'2018-05-21 08:39:44','Create Folder'),(44,4,13,NULL,4,'2018-05-21 08:39:44','Create Folder'),(45,5,NULL,NULL,6,'2018-05-21 08:43:06','Create Knowledge Base'),(46,5,20,NULL,6,'2018-05-21 08:43:06','Create Knowledge Base'),(47,5,21,NULL,6,'2018-05-21 08:43:13','Create Folder'),(48,5,20,NULL,6,'2018-05-21 08:43:13','Create Folder'),(49,6,NULL,NULL,6,'2018-05-21 08:43:40','Create Knowledge Base'),(50,6,22,NULL,6,'2018-05-21 08:43:40','Create Knowledge Base'),(51,6,NULL,NULL,6,'2018-05-21 08:43:49','Updated Permissions'),(52,7,NULL,NULL,1,'2018-05-21 08:46:01','Create Knowledge Base'),(53,7,23,NULL,1,'2018-05-21 08:46:01','Create Knowledge Base'),(54,7,24,NULL,1,'2018-05-21 08:46:09','Create Folder'),(55,7,23,NULL,1,'2018-05-21 08:46:09','Create Folder'),(56,7,25,NULL,1,'2018-05-21 08:46:17','Create Folder'),(57,7,23,NULL,1,'2018-05-21 08:46:17','Create Folder'),(58,7,26,NULL,1,'2018-05-21 08:46:38','Create Folder'),(59,7,23,NULL,1,'2018-05-21 08:46:38','Create Folder'),(60,7,27,NULL,1,'2018-05-21 08:46:44','Create Folder'),(61,7,23,NULL,1,'2018-05-21 08:46:44','Create Folder'),(62,7,28,NULL,1,'2018-05-21 08:46:48','Create Folder'),(63,7,23,NULL,1,'2018-05-21 08:46:48','Create Folder'),(64,7,29,NULL,1,'2018-05-21 08:46:53','Create Folder'),(65,7,23,NULL,1,'2018-05-21 08:46:53','Create Folder'),(66,7,NULL,NULL,1,'2018-05-21 08:47:08','Updated Permissions'),(67,7,NULL,NULL,1,'2018-05-21 08:47:14','Updated Permissions'),(68,7,NULL,NULL,1,'2018-05-21 08:47:20','Updated Permissions'),(69,7,NULL,NULL,1,'2018-05-21 08:47:26','Updated Permissions'),(70,7,NULL,NULL,1,'2018-05-21 08:47:28','Updated Permissions'),(71,7,NULL,NULL,1,'2018-05-21 08:47:32','Updated Permissions'),(72,8,NULL,NULL,5,'2018-05-21 08:50:40','Create Knowledge Base'),(73,8,30,NULL,5,'2018-05-21 08:50:40','Create Knowledge Base'),(74,8,31,NULL,5,'2018-05-21 08:50:48','Create Folder'),(75,8,30,NULL,5,'2018-05-21 08:50:48','Create Folder'),(76,8,32,NULL,5,'2018-05-21 08:50:54','Create Folder'),(77,8,30,NULL,5,'2018-05-21 08:50:54','Create Folder'),(78,8,33,NULL,5,'2018-05-21 08:50:59','Create Folder'),(79,8,30,NULL,5,'2018-05-21 08:50:59','Create Folder'),(80,8,34,NULL,5,'2018-05-21 08:51:05','Create Folder'),(81,8,30,NULL,5,'2018-05-21 08:51:05','Create Folder'),(82,9,NULL,NULL,5,'2018-05-21 08:53:30','Create Knowledge Base'),(83,9,35,NULL,5,'2018-05-21 08:53:30','Create Knowledge Base'),(84,9,36,NULL,5,'2018-05-21 08:53:37','Create Folder'),(85,9,35,NULL,5,'2018-05-21 08:53:37','Create Folder'),(86,9,37,NULL,5,'2018-05-21 08:53:43','Create Folder'),(87,9,35,NULL,5,'2018-05-21 08:53:43','Create Folder'),(88,9,38,NULL,5,'2018-05-21 08:53:48','Create Folder'),(89,9,35,NULL,5,'2018-05-21 08:53:48','Create Folder'),(90,9,NULL,NULL,5,'2018-05-21 08:54:01','Updated Permissions'),(91,9,NULL,NULL,5,'2018-05-21 08:54:05','Updated Permissions'),(92,10,NULL,NULL,3,'2018-05-21 08:55:21','Create Knowledge Base'),(93,10,39,NULL,3,'2018-05-21 08:55:21','Create Knowledge Base'),(94,10,40,NULL,3,'2018-05-21 08:55:29','Create Folder'),(95,10,39,NULL,3,'2018-05-21 08:55:29','Create Folder'),(96,10,41,NULL,3,'2018-05-21 08:55:36','Create Folder'),(97,10,39,NULL,3,'2018-05-21 08:55:36','Create Folder'),(98,10,42,NULL,3,'2018-05-21 08:55:43','Create Folder'),(99,10,39,NULL,3,'2018-05-21 08:55:43','Create Folder'),(100,10,NULL,NULL,3,'2018-05-21 08:56:02','Updated Permissions'),(101,10,NULL,NULL,3,'2018-05-21 08:56:04','Updated Permissions'),(102,10,NULL,NULL,3,'2018-05-21 08:56:14','Updated Permissions'),(103,10,NULL,NULL,3,'2018-05-21 08:56:17','Updated Permissions'),(104,11,NULL,NULL,3,'2018-05-21 08:56:54','Create Knowledge Base'),(105,11,43,NULL,3,'2018-05-21 08:56:54','Create Knowledge Base'),(106,11,44,NULL,3,'2018-05-21 08:57:01','Create Folder'),(107,11,43,NULL,3,'2018-05-21 08:57:01','Create Folder'),(108,11,45,NULL,3,'2018-05-21 08:57:06','Create Folder'),(109,11,43,NULL,3,'2018-05-21 08:57:06','Create Folder'),(110,12,NULL,NULL,8,'2018-05-21 08:58:27','Create Knowledge Base'),(111,12,46,NULL,8,'2018-05-21 08:58:27','Create Knowledge Base'),(112,12,NULL,NULL,8,'2018-05-21 08:58:37','Updated Permissions'),(113,12,NULL,NULL,8,'2018-05-21 08:58:45','Updated Permissions'),(114,12,NULL,NULL,8,'2018-05-21 08:58:58','Updated Permissions'),(115,12,NULL,NULL,8,'2018-05-21 08:59:02','Updated Permissions'),(116,13,NULL,NULL,7,'2018-05-21 09:00:24','Create Knowledge Base'),(117,13,47,NULL,7,'2018-05-21 09:00:24','Create Knowledge Base'),(118,14,NULL,NULL,7,'2018-05-21 09:01:05','Create Knowledge Base'),(119,14,48,NULL,7,'2018-05-21 09:01:05','Create Knowledge Base'),(120,14,49,NULL,7,'2018-05-21 09:01:15','Create Folder'),(121,14,48,NULL,7,'2018-05-21 09:01:15','Create Folder'),(122,14,50,NULL,7,'2018-05-21 09:01:20','Create Folder'),(123,14,48,NULL,7,'2018-05-21 09:01:20','Create Folder'),(124,14,51,NULL,7,'2018-05-21 09:01:25','Create Folder'),(125,14,48,NULL,7,'2018-05-21 09:01:25','Create Folder'),(126,15,NULL,NULL,9,'2018-05-21 09:03:29','Create Knowledge Base'),(127,15,52,NULL,9,'2018-05-21 09:03:29','Create Knowledge Base'),(128,16,NULL,NULL,9,'2018-05-21 09:03:59','Create Knowledge Base'),(129,16,53,NULL,9,'2018-05-21 09:03:59','Create Knowledge Base'),(130,17,NULL,NULL,10,'2018-05-21 09:05:56','Create Knowledge Base'),(131,17,54,NULL,10,'2018-05-21 09:05:56','Create Knowledge Base'),(132,17,NULL,NULL,10,'2018-05-21 09:06:05','Updated Permissions'),(133,17,NULL,NULL,10,'2018-05-21 09:06:09','Updated Permissions'),(134,17,NULL,NULL,10,'2018-05-21 09:06:14','Updated Permissions'),(135,17,NULL,NULL,10,'2018-05-21 09:06:28','Change Privacy, Set Private'),(136,18,NULL,NULL,12,'2018-05-22 07:01:04','Create Knowledge Base'),(137,18,55,NULL,12,'2018-05-22 07:01:04','Create Knowledge Base'),(138,18,NULL,NULL,12,'2018-05-22 07:01:13','Updated Permissions'),(139,18,NULL,NULL,12,'2018-05-22 07:01:17','Updated Permissions'),(140,18,NULL,NULL,12,'2018-05-22 07:01:22','Updated Permissions'),(141,18,NULL,NULL,12,'2018-05-22 07:01:26','Updated Permissions'),(142,18,NULL,NULL,12,'2018-05-22 07:01:46','Updated Permissions'),(143,18,NULL,NULL,12,'2018-05-22 07:01:56','Updated Permissions'),(144,18,55,1,12,'2018-05-22 07:07:38','Create Document'),(145,19,NULL,NULL,4,'2018-05-23 12:37:12','Create Knowledge Base'),(146,19,56,NULL,4,'2018-05-23 12:37:12','Create Knowledge Base');
/*!40000 ALTER TABLE `register` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'monsters',1),(2,'history',1),(3,'wikipedia',1),(4,'unknown',1);
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varbinary(250) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `status` tinyint(4) DEFAULT '3',
  `profile_picture` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Gson18','gson@gmail.com','$2y$10$jm3L74cDRwF0MFhWyT1w0.uwTEb5QqkiQlhBm.7dQCq5okMnpPr1G','Gabe','Json',1,'1/imgV7SxPO7.png'),(2,'WebJane','jadoe@gmail.com','$2y$10$I85bsexyudC07m9.SIQ26.QOZ/UAbJxzeVKL5HM0q/IxkXIdYccO.','Jane','Doe',1,'2/imgoNNVnPw.png'),(3,'Rafael','rafael@gmail.com','$2y$10$IjRuKYGFmSsyXssyc5FmPObnnK3xGK8ctfRJ2bms17UXhwdNDvrk6','Rafael','Charle',1,'3/imgIh8Wgfw.png'),(4,'WebDoe','jdoe@gmail.com','$2y$10$0oJ61SLNISs2JyykcXLc9uq0F6qQg85TYSzHb5uejTAIDmMQd/CQ2','Jon','Doe',1,'4/imgDGYMM2l.png'),(5,'ADesign','annad@gmail.com','$2y$10$AHq.L4AHMMfABbIDHVFmNuWCCGzPcrbfqCb4ACJqgMSBD8zELqgCi','Anna','Designer',1,'5/imgY0MgX6Q.png'),(6,'EmLint','elint@gmail.com','$2y$10$5qDDYkUS7yT0NWrD/36y9eQTQkf8gQwAbW7Sm9quHr.zYnfoNZ6mO','Emet','Lint',1,'6/imgJY05BVd.png'),(7,'JJohnson','jackiej@gmail.com','$2y$10$LLX8KTAT8Vf/UE7o4NSIBut7WoEjTEIlt.gcxRO.3IF0zZxPITYRG','Jackie','Johnson',1,'7/imgCWvb1go.png'),(8,'Norville','nville@gmail.com','$2y$10$TFGF1OLq5/EgBzpcwW4Lpe66PntFxkK0CH0OOVt1zScUCnkB010Da','Norbert','Ville',1,'8/imgZjQG9zF.png'),(9,'Hackerman','hackerman@gmail.com','$2y$10$Ys4pfqRXte3q9zps8fN7c.z709QIzS8.C/L.9GyagB4oWioAjjidm','Lemar','Hackerman',1,'9/imgfOC81BY.png'),(10,'ACommon','acommon@gmail.com','$2y$10$RKsqWgz4sa.6SSAINdCaq.nC7RLR5/nsIxiTafQSeN6/RgPsDlOx2','Andrea','Common',1,'10/imgEgyS8km.png'),(12,'Bymavc','miicasel@gmail.com','$2y$10$G0/6C4mjMTdeud1LfW08aemhzqV57VnknMp4UIJvSJ/4RXI5fbIZW','Miguel','Villalobos',1,'12/imgyK4Z9EO.png');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_register`
--

DROP TABLE IF EXISTS `user_register`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `description` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `user_register_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_register`
--

LOCK TABLES `user_register` WRITE;
/*!40000 ALTER TABLE `user_register` DISABLE KEYS */;
INSERT INTO `user_register` VALUES (1,1,'2018-05-21 07:59:47','User created'),(2,2,'2018-05-21 07:59:47','User created'),(3,3,'2018-05-21 07:59:47','User created'),(4,4,'2018-05-21 07:59:47','User created'),(5,5,'2018-05-21 07:59:47','User created'),(6,6,'2018-05-21 07:59:47','User created'),(7,1,'2018-05-21 07:59:47','User updated'),(8,3,'2018-05-21 07:59:47','User updated'),(9,4,'2018-05-21 07:59:47','User updated'),(10,5,'2018-05-21 07:59:47','User updated'),(11,6,'2018-05-21 07:59:47','User updated'),(12,2,'2018-05-21 07:59:47','User updated'),(13,7,'2018-05-21 07:59:47','User created'),(14,8,'2018-05-21 07:59:47','User created'),(15,9,'2018-05-21 07:59:47','User created'),(16,7,'2018-05-21 07:59:47','User updated'),(17,8,'2018-05-21 07:59:47','User updated'),(18,10,'2018-05-21 07:59:47','User created'),(19,9,'2018-05-21 07:59:47','User updated'),(20,10,'2018-05-21 07:59:47','User updated'),(21,4,'2018-05-21 08:27:46','User updated'),(22,2,'2018-05-21 08:37:15','User updated'),(23,6,'2018-05-21 08:40:50','User updated'),(24,1,'2018-05-21 08:45:03','User updated'),(25,5,'2018-05-21 08:49:51','User updated'),(26,3,'2018-05-21 08:54:54','User updated'),(27,8,'2018-05-21 08:58:00','User updated'),(28,7,'2018-05-21 09:02:23','User updated'),(29,9,'2018-05-21 09:04:23','User updated'),(30,10,'2018-05-21 09:05:31','User updated'),(32,12,'2018-05-21 09:07:32','User created'),(33,12,'2018-05-21 09:07:43','User updated'),(34,12,'2018-05-21 09:08:24','User updated'),(35,12,'2018-05-25 05:49:37','User updated'),(36,4,'2018-05-25 05:50:23','User updated'),(37,10,'2018-05-25 05:51:07','User updated'),(38,1,'2018-05-25 05:52:09','User updated'),(39,5,'2018-05-25 05:52:55','User updated'),(40,9,'2018-05-25 05:49:15','User updated'),(41,7,'2018-05-25 05:50:02','User updated'),(42,2,'2018-05-25 05:50:44','User updated'),(43,6,'2018-05-25 05:51:47','User updated'),(44,8,'2018-05-25 05:52:37','User updated'),(45,3,'2018-05-25 05:53:30','User updated');
/*!40000 ALTER TABLE `user_register` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-25 11:58:54
