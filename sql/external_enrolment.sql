-- MySQL dump 10.13  Distrib 5.6.24, for Win32 (x86)
--
-- Host: localhost    Database: external_enrolment
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `shortname` varchar(100) NOT NULL,
  `idnumber` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `shortname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'Sistemas de Informação','SIS'),(2,'Pedagogia Empresarial','PEM'),(3,'Engenharia de Produção','EPR'),(4,'Engenharia Civil','ENC'),(5,'Engenharia Robótica','ENR'),(6,'Gestão Empresarial','GPR');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrolments`
--

DROP TABLE IF EXISTS `enrolments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enrolments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortnamecourse` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `shortnamerole` varchar(100) NOT NULL DEFAULT 'student',
  `matriz` int(11) NOT NULL,
  `timecreated` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matriz` (`matriz`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrolments`
--

LOCK TABLES `enrolments` WRITE;
/*!40000 ALTER TABLE `enrolments` DISABLE KEYS */;
/*!40000 ALTER TABLE `enrolments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `error` tinyint(1) NOT NULL,
  `timecreated` bigint(20) NOT NULL,
  `more` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matrizes`
--

DROP TABLE IF EXISTS `matrizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matrizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `curso` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `modalidade` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`),
  KEY `curso` (`curso`),
  KEY `modalidade` (`modalidade`),
  CONSTRAINT `matrizes_ibfk_1` FOREIGN KEY (`curso`) REFERENCES `cursos` (`id`),
  CONSTRAINT `matrizes_ibfk_2` FOREIGN KEY (`modalidade`) REFERENCES `modalidades_de_cursos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrizes`
--

LOCK TABLES `matrizes` WRITE;
/*!40000 ALTER TABLE `matrizes` DISABLE KEYS */;
INSERT INTO `matrizes` VALUES (14,'Sistemas de Informação Presencial 2018',1,1,2),(15,'Engenharia Civil Presencial 2018',4,1,2),(18,'Gestão de Projetos EaD 2018',6,1,1),(19,'Sistemas de Informação Pós-EaD 2018',1,1,1);
/*!40000 ALTER TABLE `matrizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modalidades_de_cursos`
--

DROP TABLE IF EXISTS `modalidades_de_cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modalidades_de_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modalidades_de_cursos`
--

LOCK TABLES `modalidades_de_cursos` WRITE;
/*!40000 ALTER TABLE `modalidades_de_cursos` DISABLE KEYS */;
INSERT INTO `modalidades_de_cursos` VALUES (1,'EAD'),(2,'PRESENCIAL');
/*!40000 ALTER TABLE `modalidades_de_cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matriz` int(11) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `shortnamecourse` varchar(100) DEFAULT NULL,
  `periodo` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matriz` (`matriz`),
  CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`matriz`) REFERENCES `matrizes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` VALUES (23,14,0,'ADA',1),(24,14,1,'BDD',1),(25,14,2,'MD',1),(26,14,3,'PI',1),(27,14,4,'IA',1),(28,15,0,'FIS',1),(29,15,1,'MAT',1),(30,15,0,'FCC',2),(31,15,0,'SCO',3),(32,15,1,'DSN',3),(36,18,0,'UML',1),(37,18,1,'SPEI',1),(38,18,2,'SPEII',1),(39,19,0,'PI',1),(40,19,1,'MD',1),(41,19,2,'ADA',1),(42,19,3,'BDD',1),(43,19,4,'IA',1);
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-28 18:05:36
