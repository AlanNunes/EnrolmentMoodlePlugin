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
  `nome` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `shortname` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (23,'Pós-Graduação em Educação Especial - EAD','POS-EES-EAD'),(24,'Pós-Graduação em Educação Infantil e Letramento - EAD','POS-EIL-EAD'),(25,'Pós-Graduação em Finanças - EAD','POS-FIN-EAD'),(26,'Pós-Graduação em Gestão de Projetos - EAD','POS-GPR-EAD'),(27,'Pós-Graduação em Gestão Integrada, Supervisão e Administração Escolar - EAD','POS-GSA-EAD'),(28,'Pós-Graduação em Gestão de Tecnologia da Informação - EAD','POS-GTI-EAD'),(29,'Pós-Graduação em Pedagogia Empresarial - EAD','POS-PEM-EAD'),(30,'Graduação em Administração - EAD','ADM-EAD'),(31,'Graduação em Ciências Contábeis - EAD','CCO-EAD'),(32,'Graduação em Pedagogia - EAD','PED-EAD'),(33,'Graduação em Serviço Social - EAD','SER-EAD'),(34,'Graduação em Análise de Sistemas - EAD','ANS-EAD'),(35,'Graduação em Logística - EAD','LOG-EAD'),(36,'Graduação em Recursos Humanos - EAD','RH-EAD'),(37,'Graduação em Redes de Computadores - EAD','RDC-EAD'),(38,'Graduação em Engenharia de Produção - EAD','EPD-EAD'),(39,'Graduação Presencial em Administração - VR','VR-ADM-N'),(40,'Graduação Presencial em Arquitetura e Urbanismo - VR','VR-ARQ-N'),(41,'Graduação Presencial em Ciências Biológicas - VR','VR-CBI-N'),(42,'Graduação Presencial em Direito (Matutino) - VR','VR-DIR-M'),(43,'Graduação Presencial em Direito (Noturno) - VR','VR-DIR-N'),(44,'Graduação Presencial em Engenharia Civil - VR','VR-ECV-N'),(45,'Graduação Presencial em Engenharia de Produção- VR','VR-EPD-N'),(46,'Graduação Presencial em Engenharia Mecânica - VR','VR-EMC-N'),(47,'Graduação Presencial em Gestão de Recursos Humanos - VR','VR-GRH-N'),(48,'Graduação Presencial em História - VR','VR-HIS-N'),(49,'Graduação Presencial em Letras Português e Inglês - VR','VR-LET-N'),(50,'Graduação Presencial em Pedagogia - VR','VR-PED-N'),(51,'Graduação Presencial em Serviço Social - VR','VR-SER-N'),(52,'Graduação Presencial em Sistema de Informação - VR','VR-SIS-N'),(53,'Graduação Presencial em Administração - BP','BP-ADM-N'),(54,'Graduação Presencial em Biomedicina - BP','BP-BIO-N'),(55,'Graduação Presencial em Direito - BP','BP-DIR-N'),(56,'Graduação Presencial em Educação Física (Bacharelado) - BP','BP-EDU-B-N'),(57,'Graduação Presencial em Educação Física (Licenciatura) - BP','BP-EDU-L-N'),(58,'Graduação Presencial em Engenharia Civil - BP','BP-ECV-N'),(59,'Graduação Presencial em Engenharia Mecânica - BP','BP-EMC-N'),(60,'Graduação Presencial em Engenharia de Produção - BP','BP-EPD-N'),(61,'Graduação Presencial em Nutrição - BP','BP-NUT-N'),(62,'Graduação Presencial em Engenharia Mecânica - NI','NI-EMC-N'),(63,'Graduação Presencial em Engenharia Civil - NI','NI-ECV-N'),(64,'Graduação Presencial em Engenharia de Produção','NI-EPD-N');
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
  `username` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `shortnamerole` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'student',
  `matriz` int(11) NOT NULL,
  `timecreated` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matriz` (`matriz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `error` tinyint(1) NOT NULL,
  `timecreated` bigint(20) NOT NULL,
  `more` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `shortnamecourse` varchar(45) DEFAULT NULL,
  `periodo` int(2) DEFAULT NULL,
  `matriz` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matrizes`
--

LOCK TABLES `matrizes` WRITE;
/*!40000 ALTER TABLE `matrizes` DISABLE KEYS */;
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
  `shortnamecourse` varchar(255) NOT NULL,
  `periodo` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modulos_ibfk_1` (`matriz`),
  CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`matriz`) REFERENCES `matrizes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
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

-- Dump completed on 2018-07-02 18:39:23
