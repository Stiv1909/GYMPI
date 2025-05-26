-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: localhost    Database: gympiv2
-- ------------------------------------------------------
-- Server version	8.0.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `gym`
--

DROP TABLE IF EXISTS `gym`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gym` (
  `gym_id` int NOT NULL AUTO_INCREMENT,
  `correo` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `eslogan` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `contacto` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `horario` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_principal` mediumblob NOT NULL,
  `imag_1` mediumblob,
  `imag_2` mediumblob,
  `imag_3` mediumblob,
  `imag_4` mediumblob,
  `propietario_id` int NOT NULL,
  PRIMARY KEY (`gym_id`),
  KEY `propietario_id` (`propietario_id`),
  CONSTRAINT `gym_ibfk_1` FOREIGN KEY (`propietario_id`) REFERENCES `propietario` (`propietario_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gym`
--

LOCK TABLES `gym` WRITE;
/*!40000 ALTER TABLE `gym` DISABLE KEYS */;
/*!40000 ALTER TABLE `gym` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructores`
--

DROP TABLE IF EXISTS `instructores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instructores` (
  `id_instr` int NOT NULL AUTO_INCREMENT,
  `nombre_instr` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `foto_instr` blob NOT NULL,
  `gym_id` int NOT NULL,
  PRIMARY KEY (`id_instr`),
  KEY `gym_id` (`gym_id`),
  CONSTRAINT `instructores_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gym` (`gym_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructores`
--

LOCK TABLES `instructores` WRITE;
/*!40000 ALTER TABLE `instructores` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propietario`
--

DROP TABLE IF EXISTS `propietario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `propietario` (
  `propietario_id` int NOT NULL AUTO_INCREMENT,
  `nombre_gym` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol_id` int NOT NULL,
  PRIMARY KEY (`propietario_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `propietario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propietario`
--

LOCK TABLES `propietario` WRITE;
/*!40000 ALTER TABLE `propietario` DISABLE KEYS */;
/*!40000 ALTER TABLE `propietario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `rol_id` int NOT NULL,
  `rol_desc` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Cliente que ingresa para encontrar un gimnasio al cual asistir.'),(2,'Usuario propietario de un gimnasio interesado en dar a conocer su establecimiento.');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_entrenamiento`
--

DROP TABLE IF EXISTS `tipos_entrenamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_entrenamiento` (
  `id_entren` int NOT NULL AUTO_INCREMENT,
  `nombre_entrena` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `descripción` text COLLATE utf8mb4_general_ci NOT NULL,
  `foto_entren` mediumblob NOT NULL,
  `gym_id` int NOT NULL,
  PRIMARY KEY (`id_entren`),
  KEY `gym_id` (`gym_id`),
  CONSTRAINT `tipos_entrenamiento_ibfk_1` FOREIGN KEY (`gym_id`) REFERENCES `gym` (`gym_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_entrenamiento`
--

LOCK TABLES `tipos_entrenamiento` WRITE;
/*!40000 ALTER TABLE `tipos_entrenamiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipos_entrenamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `usu_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `celular` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genero` varchar(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `peso` decimal(10,0) DEFAULT NULL,
  `altura` decimal(10,0) DEFAULT NULL,
  `imagen_principal` mediumblob,
  `rol_id` int NOT NULL,
  PRIMARY KEY (`usu_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-26  0:36:00
