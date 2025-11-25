CREATE DATABASE  IF NOT EXISTS `viajar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `viajar`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: viajar
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `rela_usuario` int DEFAULT NULL,
  `accion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`),
  KEY `rela_usuario` (`rela_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`rela_usuario`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (70,66,'Alta de usuario','Se creó el usuario: poc7','2025-09-12 19:17:06'),(71,72,'Alta de perfil','Se creó el perfil: Prueba','2025-11-09 19:17:16'),(75,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-12 19:20:48'),(76,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 13) a \'Prueba2\'','2025-11-12 19:21:04'),(77,66,'Alta de tipo de contacto','Se creó el tipo de contacto: Prueba','2025-11-12 19:21:12'),(78,66,'Alta de provincia','Se creó la provincia: prueba','2025-11-12 19:21:17'),(79,66,'Baja lógica de perfil','Se desactivó el perfil (ID 26)','2025-11-13 14:19:53'),(80,NULL,'Envío de email','Se envió un email de restablecimiento de contraseña a mircoaguilar02@gmail.com','2025-11-13 14:41:56'),(81,NULL,'Cambio de contraseña','El usuario ID 74 cambió su contraseña','2025-11-13 14:43:06'),(100,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #154 por $15000 (Mercado Pago ID: 133108998693)','2025-11-13 16:34:14'),(101,NULL,'Envío de email','Se envió un email de restablecimiento de contraseña a Mircoaguilar02@gmail.com','2025-11-13 16:40:10'),(109,74,'Reserva creada','El usuario ID 74 creó la reserva #166 por $15000','2025-11-14 13:01:48'),(110,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #166 por $15000 (Mercado Pago ID: 133834933882)','2025-11-14 13:02:06'),(111,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 13) a \'Prueba\'','2025-11-14 13:34:32'),(112,66,'Baja lógica de tipo de pago','Se eliminó lógicamente el tipo de pago (ID: 13)','2025-11-14 13:34:34'),(113,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:35'),(114,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 14) a \'Prueba2\'','2025-11-14 13:34:38'),(115,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 14) a \'Prueba22\'','2025-11-14 13:34:40'),(116,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 14) a \'Prueba222\'','2025-11-14 13:34:41'),(117,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 14) a \'Prueba2222\'','2025-11-14 13:34:41'),(118,66,'Actualización de tipo de pago','Se actualizó el tipo de pago (ID: 14) a \'Prueba22222\'','2025-11-14 13:34:42'),(119,66,'Baja lógica de tipo de pago','Se eliminó lógicamente el tipo de pago (ID: 14)','2025-11-14 13:34:44'),(120,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:46'),(121,66,'Baja lógica de tipo de pago','Se eliminó lógicamente el tipo de pago (ID: 15)','2025-11-14 13:34:47'),(122,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:49'),(123,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:50'),(124,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:52'),(125,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:53'),(126,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:54'),(127,66,'Alta de tipo de pago','Se creó un nuevo tipo de pago: Prueba','2025-11-14 13:34:56'),(128,66,'Alta de perfil','Se creó el perfil: Prueba','2025-11-14 13:36:41'),(129,66,'Baja lógica de provincia','Se eliminó lógicamente la provincia (ID: 7)','2025-11-15 00:03:13'),(130,66,'Baja lógica de perfil','Se desactivó el perfil (ID 27)','2025-11-15 13:03:00'),(131,74,'Reserva creada','El usuario ID 74 creó la reserva #167 por $100000','2025-11-18 22:03:19'),(132,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #167 por $100000 (Mercado Pago ID: 133792754809)','2025-11-18 22:03:40'),(133,74,'Reserva creada','El usuario ID 74 creó la reserva #168 por $450000','2025-11-18 22:04:26'),(134,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #168 por $450000 (Mercado Pago ID: 133792785735)','2025-11-18 22:04:48'),(135,74,'Reserva creada','El usuario ID 74 creó la reserva #169 por $20000','2025-11-18 22:14:00'),(136,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #169 por $20000 (Mercado Pago ID: 134404424658)','2025-11-18 22:14:35'),(137,74,'Reserva creada','El usuario ID 74 creó la reserva #170 por $150000','2025-11-18 22:37:20'),(138,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #170 por $150000 (Mercado Pago ID: 133795765665)','2025-11-18 22:37:40'),(139,74,'Reserva creada','El usuario ID 74 creó la reserva #183 por $210000','2025-11-19 19:41:30'),(140,74,'Reserva creada','El usuario ID 74 creó la reserva #184 por $18000','2025-11-24 09:51:13'),(141,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #184 por $18000 (Mercado Pago ID: 134438576937)','2025-11-24 09:51:42'),(142,74,'Reserva creada','El usuario ID 74 creó la reserva #186 por $33000','2025-11-24 10:22:49'),(143,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #186 por $33000 (Mercado Pago ID: 135055858044)','2025-11-24 10:23:14'),(144,74,'Reserva creada','El usuario ID 74 creó la reserva #187 por $18000','2025-11-24 10:25:40'),(145,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #187 por $18000 (Mercado Pago ID: 134441849637)','2025-11-24 10:25:57'),(146,74,'Reserva creada','El usuario ID 74 creó la reserva #190 por $450000','2025-11-24 23:14:05'),(147,74,'Reserva creada','El usuario ID 74 creó la reserva #191 por $45000','2025-11-25 16:58:57'),(148,74,'Reserva creada','El usuario ID 74 creó la reserva #192 por $45000','2025-11-25 17:01:50'),(149,74,'Reserva creada','El usuario ID 74 creó la reserva #193 por $45000','2025-11-25 17:04:16'),(150,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #193 por $45000 (Mercado Pago ID: 135226550608)','2025-11-25 17:04:30'),(151,74,'Reserva creada','El usuario ID 74 creó la reserva #194 por $10000','2025-11-25 17:08:27'),(152,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #194 por $10000 (Mercado Pago ID: 135227184102)','2025-11-25 17:08:48'),(153,74,'Reserva creada','El usuario ID 74 creó la reserva #196 por $468000','2025-11-25 18:22:17'),(154,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #196 por $468000 (Mercado Pago ID: 135237172214)','2025-11-25 18:22:49'),(155,74,'Reserva creada','El usuario ID 74 creó la reserva #197 por $18000','2025-11-25 18:24:47'),(156,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #197 por $18000 (Mercado Pago ID: 134622565473)','2025-11-25 18:25:18');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cancelacion`
--

DROP TABLE IF EXISTS `cancelacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancelacion` (
  `id_cancelacion` int NOT NULL AUTO_INCREMENT,
  `cancelacion_fecha` date DEFAULT NULL,
  `rela_reservas` int NOT NULL,
  `rela_motivo_cancelacion` int NOT NULL,
  PRIMARY KEY (`id_cancelacion`),
  KEY `fk_cancelacion_reservas1_idx` (`rela_reservas`),
  KEY `fk_cancelacion_motivo` (`rela_motivo_cancelacion`),
  CONSTRAINT `fk_cancelacion_motivo` FOREIGN KEY (`rela_motivo_cancelacion`) REFERENCES `motivos_cancelacion` (`id_motivo_cancelacion`),
  CONSTRAINT `fk_cancelacion_reservas1` FOREIGN KEY (`rela_reservas`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancelacion`
--

LOCK TABLES `cancelacion` WRITE;
/*!40000 ALTER TABLE `cancelacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancelacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrito`
--

DROP TABLE IF EXISTS `carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito` (
  `id_carrito` int NOT NULL AUTO_INCREMENT,
  `rela_usuario` int NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_carrito`),
  KEY `carrito_ibfk_1` (`rela_usuario`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`rela_usuario`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrito_items`
--

DROP TABLE IF EXISTS `carrito_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrito_items` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `rela_carrito` int NOT NULL,
  `tipo_servicio` enum('hotel','transporte','tour') NOT NULL,
  `id_servicio` int NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `carrito_items_ibfk_1` (`rela_carrito`),
  CONSTRAINT `carrito_items_ibfk_1` FOREIGN KEY (`rela_carrito`) REFERENCES `carrito` (`id_carrito`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito_items`
--

LOCK TABLES `carrito_items` WRITE;
/*!40000 ALTER TABLE `carrito_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrito_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciudades` (
  `id_ciudad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `rela_provincia` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_ciudad`),
  KEY `ciudades_ibfk_1` (`rela_provincia`),
  CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`rela_provincia`) REFERENCES `provincias` (`id_provincia`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudades`
--

LOCK TABLES `ciudades` WRITE;
/*!40000 ALTER TABLE `ciudades` DISABLE KEYS */;
INSERT INTO `ciudades` VALUES (1,'Formosa capital',1,1),(3,'Clorinda',1,1),(4,'Pirané',1,1),(5,'Ingeniero Juárez',1,1),(6,'El Espinillo',1,1),(7,'Comandante Fontana',1,1),(8,'Laguna Yema',1,1),(9,'San Francisco de Laishí',1,1),(10,'Misión Tacaaglé',1,1),(13,'Fontana',4,0);
/*!40000 ALTER TABLE `ciudades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto`
--

DROP TABLE IF EXISTS `contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacto` (
  `id_contacto` int NOT NULL AUTO_INCREMENT,
  `contacto_descripcion` varchar(80) NOT NULL,
  `rela_personas` int NOT NULL,
  `rela_tipo_contacto` int NOT NULL,
  PRIMARY KEY (`id_contacto`),
  KEY `fk_contacto_personas1_idx` (`rela_personas`),
  KEY `fk_contacto_tipo_contacto1_idx` (`rela_tipo_contacto`),
  CONSTRAINT `fk_contacto_personas1` FOREIGN KEY (`rela_personas`) REFERENCES `personas` (`id_personas`),
  CONSTRAINT `fk_contacto_tipo_contacto1` FOREIGN KEY (`rela_tipo_contacto`) REFERENCES `tipo_contacto` (`id_tipo_contacto`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
INSERT INTO `contacto` VALUES (2,'3704687824',366,1),(4,'3705786843',367,1),(6,'3705000777',368,1),(8,'3705287463',369,1),(9,'3705786845',370,1),(10,'3705786845',371,1),(11,'3705786845',372,1),(12,'3704876934',373,1),(13,'3704859273',374,1),(14,'3704687834',375,1),(15,'3704000000',376,1),(16,'3704687824',377,1);
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_reserva_hotel`
--

DROP TABLE IF EXISTS `detalle_reserva_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_reserva_hotel` (
  `id_detalle_hotel` int NOT NULL AUTO_INCREMENT,
  `rela_detalle_reserva` int NOT NULL,
  `rela_habitacion` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `noches` int NOT NULL,
  `estado` enum('confirmada','pendiente','cancelada') DEFAULT 'confirmada',
  PRIMARY KEY (`id_detalle_hotel`),
  KEY `fk_hotel_detalle_reserva` (`rela_detalle_reserva`),
  KEY `fk_detalle_hotel_habitacion` (`rela_habitacion`),
  CONSTRAINT `fk_detalle_hotel_habitacion` FOREIGN KEY (`rela_habitacion`) REFERENCES `hotel_habitaciones` (`id_hotel_habitacion`),
  CONSTRAINT `fk_hotel_detalle_reserva` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_hotel`
--

LOCK TABLES `detalle_reserva_hotel` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_hotel` DISABLE KEYS */;
INSERT INTO `detalle_reserva_hotel` VALUES (110,284,16,'2025-11-25','2025-11-28',3,'cancelada');
/*!40000 ALTER TABLE `detalle_reserva_hotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_reserva_tour`
--

DROP TABLE IF EXISTS `detalle_reserva_tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_reserva_tour` (
  `id_detalle_tour` int NOT NULL AUTO_INCREMENT,
  `rela_detalle_reserva` int NOT NULL,
  `rela_tour` int NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('confirmada','pendiente','cancelada') DEFAULT 'confirmada',
  PRIMARY KEY (`id_detalle_tour`),
  KEY `fk_tour_detalle_reserva` (`rela_detalle_reserva`),
  KEY `fk_tour_detalle` (`rela_tour`),
  CONSTRAINT `fk_tour_detalle` FOREIGN KEY (`rela_tour`) REFERENCES `tours` (`id_tour`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tour_detalle_reserva` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_tour`
--

LOCK TABLES `detalle_reserva_tour` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_tour` DISABLE KEYS */;
INSERT INTO `detalle_reserva_tour` VALUES (100,281,6,'2025-12-01','cancelada'),(101,282,14,'2025-11-25','confirmada'),(102,283,6,'2025-12-01','confirmada');
/*!40000 ALTER TABLE `detalle_reserva_tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_reserva_transporte`
--

DROP TABLE IF EXISTS `detalle_reserva_transporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_reserva_transporte` (
  `id_detalle_transporte` int NOT NULL AUTO_INCREMENT,
  `rela_detalle_reserva` int NOT NULL,
  `id_viaje` int NOT NULL,
  `piso` int NOT NULL,
  `numero_asiento` int NOT NULL,
  `fila` int DEFAULT NULL,
  `columna` int DEFAULT NULL,
  `fecha_servicio` date DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `rela_pasajero` int DEFAULT NULL,
  `estado` enum('confirmada','pendiente','cancelada') COLLATE utf8mb4_general_ci DEFAULT 'confirmada',
  PRIMARY KEY (`id_detalle_transporte`),
  KEY `rela_detalle_reserva` (`rela_detalle_reserva`),
  KEY `id_viaje` (`id_viaje`),
  KEY `rela_pasajero` (`rela_pasajero`),
  CONSTRAINT `detalle_reserva_transporte_ibfk_1` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`),
  CONSTRAINT `detalle_reserva_transporte_ibfk_2` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id_viajes`),
  CONSTRAINT `detalle_reserva_transporte_ibfk_3` FOREIGN KEY (`rela_pasajero`) REFERENCES `pasajeros` (`id_pasajeros`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_transporte`
--

LOCK TABLES `detalle_reserva_transporte` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_transporte` DISABLE KEYS */;
INSERT INTO `detalle_reserva_transporte` VALUES (23,285,8,1,7,2,3,'2025-11-30',18000.00,NULL,'confirmada'),(24,286,8,1,12,3,4,'2025-11-30',18000.00,NULL,'cancelada');
/*!40000 ALTER TABLE `detalle_reserva_transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_reservas`
--

DROP TABLE IF EXISTS `detalle_reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_reservas` (
  `id_detalle_reserva` int NOT NULL AUTO_INCREMENT,
  `rela_reservas` int NOT NULL,
  `tipo_servicio` enum('transporte','hotel','tour') NOT NULL,
  `cantidad` int DEFAULT '1',
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_reserva`),
  KEY `rela_reservas` (`rela_reservas`),
  CONSTRAINT `detalle_reservas_ibfk_1` FOREIGN KEY (`rela_reservas`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reservas`
--

LOCK TABLES `detalle_reservas` WRITE;
/*!40000 ALTER TABLE `detalle_reservas` DISABLE KEYS */;
INSERT INTO `detalle_reservas` VALUES (281,193,'tour',3,15000.00,45000.00),(282,194,'tour',1,10000.00,10000.00),(283,195,'tour',1,15000.00,15000.00),(284,196,'hotel',1,150000.00,450000.00),(285,196,'transporte',1,18000.00,18000.00),(286,197,'transporte',1,18000.00,18000.00);
/*!40000 ALTER TABLE `detalle_reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domicilio`
--

DROP TABLE IF EXISTS `domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilio` (
  `id_domicilio` int NOT NULL AUTO_INCREMENT,
  `domicilio_descripcion` varchar(80) NOT NULL,
  `rela_personas` int NOT NULL,
  `rela_ciudad` int DEFAULT NULL,
  PRIMARY KEY (`id_domicilio`),
  KEY `fk_domicilio_personas1_idx` (`rela_personas`),
  KEY `fk_domicilio_ciudad` (`rela_ciudad`),
  CONSTRAINT `fk_domicilio_ciudad` FOREIGN KEY (`rela_ciudad`) REFERENCES `ciudades` (`id_ciudad`),
  CONSTRAINT `fk_domicilio_personas1` FOREIGN KEY (`rela_personas`) REFERENCES `personas` (`id_personas`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
INSERT INTO `domicilio` VALUES (5,'Avenida 25 de mayo 0000',366,1),(6,'Puchini 0000',367,1),(7,'prueba',368,1),(8,'Mi casa',369,1),(9,'Barrio san Martin',370,1),(10,'Barrio san Martin',371,1),(11,'Barrio san Martin',372,1),(12,'Avenida 10 de mayo 1111',373,NULL),(13,'Av italia 0000',374,NULL),(14,'Av italia 0000',375,NULL),(15,'Barrio san Martin 2',376,NULL),(16,'Barrio la paz casa 19 manzana 24 sector c',377,NULL);
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados_reserva`
--

DROP TABLE IF EXISTS `estados_reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_reserva` (
  `id_estado_reserva` int NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_estado_reserva`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados_reserva`
--

LOCK TABLES `estados_reserva` WRITE;
/*!40000 ALTER TABLE `estados_reserva` DISABLE KEYS */;
INSERT INTO `estados_reserva` VALUES (1,'Pendiente',1),(2,'Confirmada',1),(3,'Cancelada',1),(4,'En curso',1),(5,'Finalizada',1),(10,'Pruebaa',0),(11,'Pureba2',0);
/*!40000 ALTER TABLE `estados_reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura` (
  `id_factura` int NOT NULL AUTO_INCREMENT,
  `factura_numero_factura` varchar(50) NOT NULL,
  `factura_fecha_emision` date NOT NULL,
  `rela_reserva` int NOT NULL,
  PRIMARY KEY (`id_factura`),
  KEY `fk_factura_reserva` (`rela_reserva`),
  CONSTRAINT `fk_factura_reserva` FOREIGN KEY (`rela_reserva`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel`
--

DROP TABLE IF EXISTS `hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel` (
  `id_hotel` int NOT NULL AUTO_INCREMENT,
  `hotel_nombre` varchar(255) NOT NULL,
  `fecha_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `rela_provincia` int DEFAULT NULL,
  `rela_ciudad` int DEFAULT NULL,
  `rela_proveedor` int DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  `estado_revision` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `motivo_rechazo` text,
  `fecha_revision` datetime DEFAULT NULL,
  `revisado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_hotel`),
  KEY `fk_hotel_proveedor_idx` (`rela_proveedor`),
  KEY `fk_hotel_provincia` (`rela_provincia`),
  KEY `fk_hotel_ciudad` (`rela_ciudad`),
  KEY `fk_hotel_revisado_por` (`revisado_por`),
  CONSTRAINT `fk_hotel_ciudad` FOREIGN KEY (`rela_ciudad`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_hotel_proveedor` FOREIGN KEY (`rela_proveedor`) REFERENCES `proveedores` (`id_proveedores`),
  CONSTRAINT `fk_hotel_provincia` FOREIGN KEY (`rela_provincia`) REFERENCES `provincias` (`id_provincia`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_hotel_revisado_por` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel`
--

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;
INSERT INTO `hotel` VALUES (2,'Hotel Internacional de Turismo','2025-08-05 19:31:13','internacional1.jpg',1,1,1,1,'aprobado',NULL,'2025-10-29 10:14:20',66),(58,'Howard Johnson Formosa','2025-09-25 14:24:15','hotel_58.jpg',1,1,3,1,'aprobado',NULL,'2025-10-29 10:14:20',66),(59,'Hotel regina','2025-10-21 17:43:17','6918ac8c0eb83_190136777.jpg',1,1,3,1,'aprobado',NULL,'2025-10-29 10:47:43',66),(70,'Esto es una prueba1','2025-11-14 23:03:38','6918a8cf9afb6_gallery-14.jpg',1,5,3,1,'pendiente',NULL,NULL,NULL);
/*!40000 ALTER TABLE `hotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_habitaciones`
--

DROP TABLE IF EXISTS `hotel_habitaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_habitaciones` (
  `id_hotel_habitacion` int NOT NULL AUTO_INCREMENT,
  `rela_hotel` int NOT NULL,
  `rela_tipo_habitacion` int NOT NULL,
  `capacidad_maxima` int NOT NULL,
  `precio_base_noche` decimal(10,2) NOT NULL,
  `descripcion` text,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fotos` text,
  PRIMARY KEY (`id_hotel_habitacion`),
  KEY `rela_hotel` (`rela_hotel`),
  KEY `rela_tipo_habitacion` (`rela_tipo_habitacion`),
  CONSTRAINT `hotel_habitaciones_ibfk_1` FOREIGN KEY (`rela_hotel`) REFERENCES `hotel` (`id_hotel`),
  CONSTRAINT `hotel_habitaciones_ibfk_2` FOREIGN KEY (`rela_tipo_habitacion`) REFERENCES `tipos_habitacion` (`id_tipo_habitacion`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones`
--

LOCK TABLES `hotel_habitaciones` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones` DISABLE KEYS */;
INSERT INTO `hotel_habitaciones` VALUES (15,58,1,2,30000.00,'Habitación luminosa y bien distribuida, ideal para quienes buscan un espacio cómodo y funcional. Está equipada con una cama matrimonial, según el , mesa de luz con velador, y placard amplio para guardar pertenencias de forma ordenada. Espacio sencillo pero bien cuidado, pensado para ofrecer una estadía confortable.',1,'2025-09-25 17:26:22','[\"assets/images/hab_68d57b3e4211c_ostrovok-445092-243d61-859914.jpg\",\"assets/images/hab_68d57b3e422ca_ostrovok-445092-367310-933479.jpg\",\"assets/images/425150400.jpg\"]'),(16,58,4,4,150000.00,'Elegante y confortable suite equipada con mobiliario de alta calidad, ideal para una estadía placentera. Cuenta con cama king size, área de estar, baño privado, climatización, TV por cable y Wi-Fi. Perfecta para quienes buscan comodidad y privacidad en un ambiente distinguido.',1,'2025-09-25 17:44:08','[\"assets/images/hab_68d57f68ad0c2_46586885.jpg\",\"assets/images/hab_68d57f68ad26e_532056808.jpg\",\"assets/images/544661146.jpg\"]'),(17,59,1,2,50000.00,'Una habitación simple, un espacio pequeño y acogedor, con lo esencial para descansar.',1,'2025-10-21 21:17:40','[\"assets/images/hab_68f7f8749a95b_Habitacion-Sencilla_P1-1200x600.jpg\",\"assets/images/hab_68f7f8749aafd_Habitacion-Sencilla_P2-1200x600.jpg\",\"assets/images/hab_68f7f8749acb6_Habitacion-Sencilla_P3-1200x600.jpg\",\"assets/images/hab_68f7f8749ae45_Habitacion-Sencilla_P4-1200x600.jpg\"]'),(18,59,4,10,5000.00,'Prueba',0,'2025-11-18 02:19:46','[\"assets/images/hab_691bd9b94dd44_415730882.jpg\"]'),(19,2,1,2,20000.00,'Esto es una prueba',1,'2025-11-19 01:06:59','[\"assets/images/hab_691d18337d7f1_Habitacion-Sencilla_P4-1200x600.jpg\",\"assets/images/hab_691d18337daf3_Habitacion-Sencilla_P1-1200x600.jpg\",\"assets/images/hab_691d18337dd30_Habitacion-Sencilla_P2-1200x600.jpg\",\"assets/images/hab_691d18337df17_Habitacion-Sencilla_P3-1200x600.jpg\"]');
/*!40000 ALTER TABLE `hotel_habitaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_habitaciones_stock`
--

DROP TABLE IF EXISTS `hotel_habitaciones_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_habitaciones_stock` (
  `id_stock` int NOT NULL AUTO_INCREMENT,
  `rela_habitacion` int NOT NULL,
  `fecha` date NOT NULL,
  `cantidad_disponible` int NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_stock`),
  UNIQUE KEY `unica_fecha_habitacion` (`rela_habitacion`,`fecha`),
  CONSTRAINT `hotel_habitaciones_stock_ibfk_1` FOREIGN KEY (`rela_habitacion`) REFERENCES `hotel_habitaciones` (`id_hotel_habitacion`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones_stock`
--

LOCK TABLES `hotel_habitaciones_stock` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones_stock` DISABLE KEYS */;
INSERT INTO `hotel_habitaciones_stock` VALUES (64,16,'2025-11-04',3,1),(65,16,'2025-11-05',3,1),(66,16,'2025-11-06',3,1),(67,16,'2025-11-07',3,1),(68,16,'2025-11-08',3,1),(69,16,'2025-11-09',3,1),(70,16,'2025-11-10',3,1),(71,16,'2025-11-11',3,1),(72,16,'2025-11-12',3,1),(73,16,'2025-11-13',3,1),(74,16,'2025-11-14',3,1),(75,16,'2025-11-15',3,1),(76,16,'2025-11-16',3,1),(77,16,'2025-11-17',3,1),(78,16,'2025-11-18',3,1),(79,16,'2025-11-19',3,1),(80,16,'2025-11-20',3,1),(81,16,'2025-11-21',3,1),(82,16,'2025-11-22',3,1),(83,16,'2025-11-23',3,1),(84,16,'2025-11-24',3,1),(85,16,'2025-11-25',3,1),(86,16,'2025-11-26',3,1),(87,16,'2025-11-27',3,1),(88,16,'2025-11-28',3,1),(89,16,'2025-11-29',3,1),(90,16,'2025-11-30',3,1),(91,15,'2025-11-04',3,1),(92,15,'2025-11-05',3,1),(93,15,'2025-11-06',3,1),(94,15,'2025-11-07',3,1),(95,15,'2025-11-08',3,1),(96,15,'2025-11-09',3,1),(97,15,'2025-11-10',3,1),(98,15,'2025-11-11',3,1),(99,15,'2025-11-12',3,1),(100,15,'2025-11-13',3,1),(101,15,'2025-11-14',3,1),(102,15,'2025-11-15',3,1),(103,15,'2025-11-16',3,1),(104,15,'2025-11-17',3,1),(105,15,'2025-11-18',3,1),(106,15,'2025-11-19',3,1),(107,15,'2025-11-20',3,1),(108,15,'2025-11-21',3,1),(109,15,'2025-11-22',3,1),(110,15,'2025-11-23',3,1),(111,15,'2025-11-24',3,1),(112,15,'2025-11-25',3,1),(113,15,'2025-11-26',3,1),(114,15,'2025-11-27',3,1),(115,15,'2025-11-28',3,1),(116,15,'2025-11-29',3,1),(117,15,'2025-11-30',3,1),(118,17,'2025-11-18',3,1),(119,17,'2025-11-19',3,1),(120,17,'2025-11-20',3,1),(121,17,'2025-11-21',3,1),(122,17,'2025-11-22',3,1),(123,17,'2025-11-23',3,1),(124,17,'2025-11-24',3,1),(125,17,'2025-11-25',3,1),(126,17,'2025-11-26',3,1),(127,17,'2025-11-27',3,1),(128,17,'2025-11-28',3,1),(129,17,'2025-11-29',3,1),(130,17,'2025-11-30',3,1),(131,17,'2025-12-01',2,1),(132,17,'2025-12-02',2,1),(133,17,'2025-12-03',2,1),(134,17,'2025-12-04',2,1),(135,17,'2025-12-05',2,1),(136,17,'2025-12-06',2,1),(137,17,'2025-12-07',2,1),(138,17,'2025-12-08',2,1),(139,17,'2025-12-09',2,1),(140,17,'2025-12-10',2,1),(141,17,'2025-12-11',2,1),(142,17,'2025-12-12',2,1),(143,17,'2025-12-13',2,1),(144,17,'2025-12-14',2,1),(145,17,'2025-12-15',2,1),(146,17,'2025-12-16',2,1),(147,17,'2025-12-17',2,1),(148,17,'2025-12-18',2,1),(149,17,'2025-12-19',2,1),(150,17,'2025-12-20',2,1),(151,17,'2025-12-21',2,1),(152,17,'2025-12-22',2,1),(153,17,'2025-12-23',2,1),(154,17,'2025-12-24',2,1),(155,17,'2025-12-25',2,1),(156,17,'2025-12-26',2,1),(157,17,'2025-12-27',2,1),(158,17,'2025-12-28',2,1),(159,17,'2025-12-29',2,1),(160,17,'2025-12-30',2,1),(161,17,'2025-12-31',2,1),(162,19,'2025-11-18',3,1),(163,19,'2025-11-19',3,1),(164,19,'2025-11-20',3,1),(165,19,'2025-11-21',3,1),(166,19,'2025-11-22',3,1),(167,19,'2025-11-23',3,1),(168,19,'2025-11-24',3,1),(169,19,'2025-11-25',3,1),(170,19,'2025-11-26',3,1),(171,19,'2025-11-27',3,1),(172,19,'2025-11-28',3,1),(173,19,'2025-11-29',3,1),(174,19,'2025-11-30',3,1);
/*!40000 ALTER TABLE `hotel_habitaciones_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel_tarifas_temporada`
--

DROP TABLE IF EXISTS `hotel_tarifas_temporada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_tarifas_temporada` (
  `id_hotel_tarifas_temporada` int NOT NULL AUTO_INCREMENT,
  `rela_hotel` int NOT NULL,
  `rela_temporada` int DEFAULT NULL,
  `rela_monedas` int DEFAULT NULL,
  `precio_por_noche` decimal(10,2) NOT NULL,
  `disponibilidad` int DEFAULT '0',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hotel_tarifas_temporada`),
  KEY `rela_hotel` (`rela_hotel`),
  KEY `fk_dtarifas_temporada` (`rela_temporada`),
  KEY `fk_tarifas_moneda` (`rela_monedas`),
  CONSTRAINT `fk_dtarifas_temporada` FOREIGN KEY (`rela_temporada`) REFERENCES `temporadas` (`id_temporada`),
  CONSTRAINT `fk_tarifas_moneda` FOREIGN KEY (`rela_monedas`) REFERENCES `monedas` (`id_moneda`),
  CONSTRAINT `hotel_tarifas_temporada_ibfk_1` FOREIGN KEY (`rela_hotel`) REFERENCES `hotel` (`id_hotel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_tarifas_temporada`
--

LOCK TABLES `hotel_tarifas_temporada` WRITE;
/*!40000 ALTER TABLE `hotel_tarifas_temporada` DISABLE KEYS */;
/*!40000 ALTER TABLE `hotel_tarifas_temporada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hoteles_info`
--

DROP TABLE IF EXISTS `hoteles_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hoteles_info` (
  `id_info` int NOT NULL AUTO_INCREMENT,
  `rela_hotel` int NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `descripcion` text,
  `servicios` text,
  `politicas_cancelacion` text,
  `reglas` text,
  `fotos` text,
  PRIMARY KEY (`id_info`),
  KEY `fk_hoteles_info_hotel_idx` (`rela_hotel`),
  CONSTRAINT `fk_hoteles_info_hotel` FOREIGN KEY (`rela_hotel`) REFERENCES `hotel` (`id_hotel`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoteles_info`
--

LOCK TABLES `hoteles_info` WRITE;
/*!40000 ALTER TABLE `hoteles_info` DISABLE KEYS */;
INSERT INTO `hoteles_info` VALUES (2,2,'San Martín 1001, P3600 Formosa','Hotel Internacional de Turismo, ubicado estratégicamente para brindar comodidad y excelente atención a turistas y visitantes.','Wi-Fi, desayuno buffet, estacionamiento, servicio a la habitación, aire acondicionado','Cancelación gratuita hasta 72 horas antes de la fecha de llegada.','No se permiten fiestas ni eventos, se debe respetar el horario de silencio después de las 22 hs.',NULL),(39,58,'Av. Dr. Luis Gutnisky 3754','El Hotel Howard Johnson Formosa ofrece confort y excelente ubicación en la ciudad, ideal para turistas y viajeros de negocios.','Wi-Fi gratis, pileta, gimnasio, desayuno incluido, estacionamiento','Cancelación sin costo hasta 48 horas antes de la llegada.','No se permiten mascotas. Prohibido fumar en habitaciones.','{\"2\":\"6918ae0138ab3_ostrovok-445092-f26b12-029944.jpg\"}'),(40,59,'San Martín 535, P3600 Formosa','Es un alojamiento de categorización media, ideal para quienes buscan comodidad y accesibilidad en el corazón de la ciudad.','Wi-Fi gratuito en todo el hotel, Desayuno buffet incluido, Restaurante con una variada oferta gastronómica regional e internacional, Sala de reuniones y espacios para eventos, Estacionamiento privado para huéspedes.','Cancelación gratuita hasta 24 horas antes de la fecha de check-in.','Los huéspedes deben tener al menos 18 años para realizar el check-in sin la presencia de un adulto responsable, No se permiten mascotas en el hotel, excepto en casos especiales con autorización previa, Prohibido fumar dentro de las habitaciones y en áreas cerradas. El hotel dispone de espacios habilitados para fumadores, El hotel se reserva el derecho de solicitar la salida de cualquier huésped cuyo comportamiento sea considerado inapropiado o que cause molestias a otros huéspedes.',''),(47,70,'Puchini 5000','Hola 1234','hola 1234','Hola 1234','hola 1234','');
/*!40000 ALTER TABLE `hoteles_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulos`
--

DROP TABLE IF EXISTS `modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos` (
  `id_modulos` int NOT NULL AUTO_INCREMENT,
  `modulos_nombre` varchar(45) NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_modulos`),
  UNIQUE KEY `uq_modulos_nombre` (`modulos_nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos`
--

LOCK TABLES `modulos` WRITE;
/*!40000 ALTER TABLE `modulos` DISABLE KEYS */;
INSERT INTO `modulos` VALUES (1,'usuarios',1),(2,'ventas',1),(3,'clientes',1),(4,'proveedores',1),(5,'hoteles',1),(9,'transportes',1),(10,'tours',1);
/*!40000 ALTER TABLE `modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monedas`
--

DROP TABLE IF EXISTS `monedas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monedas` (
  `id_moneda` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `simbolo` varchar(10) NOT NULL,
  `tasa_cambio` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_moneda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monedas`
--

LOCK TABLES `monedas` WRITE;
/*!40000 ALTER TABLE `monedas` DISABLE KEYS */;
INSERT INTO `monedas` VALUES (1,'Euro','€',1.0000,1),(2,'Dolar','U$S',1.0000,1),(3,'Peso Argentino','$',1.0000,1),(4,'prueba2','€2',1.0000,0),(5,'prueba','€',1.0000,0);
/*!40000 ALTER TABLE `monedas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motivos_cancelacion`
--

DROP TABLE IF EXISTS `motivos_cancelacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivos_cancelacion` (
  `id_motivo_cancelacion` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_motivo_cancelacion`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivos_cancelacion`
--

LOCK TABLES `motivos_cancelacion` WRITE;
/*!40000 ALTER TABLE `motivos_cancelacion` DISABLE KEYS */;
INSERT INTO `motivos_cancelacion` VALUES (1,'Problemas de salud',1),(2,'Clima adverso',1),(3,'Cambio de fechas',1),(4,'Problemas de transporte',1),(5,'Otros',1),(6,'Pruebaz',0),(7,'Prueba',0),(8,'Pruebas',0);
/*!40000 ALTER TABLE `motivos_cancelacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nacionalidad`
--

DROP TABLE IF EXISTS `nacionalidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nacionalidad` (
  `id_nacionalidad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_nacionalidad`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nacionalidad`
--

LOCK TABLES `nacionalidad` WRITE;
/*!40000 ALTER TABLE `nacionalidad` DISABLE KEYS */;
INSERT INTO `nacionalidad` VALUES (1,'Argentina'),(2,'España'),(3,'México'),(4,'Estados Unidos'),(5,'Colombia'),(6,'Francia'),(7,'Alemania'),(8,'Italia'),(9,'Japón'),(10,'Reino Unido'),(11,'Canadá'),(12,'Brasil'),(13,'Australia'),(14,'Chile'),(15,'Perú'),(16,'Venezuela'),(17,'Ecuador'),(18,'Uruguay'),(19,'Rusia'),(20,'China'),(21,'India'),(22,'Sudáfrica'),(23,'Egipto'),(24,'Arabia Saudita'),(25,'Tailandia'),(26,'Corea del Sur'),(27,'Países Bajos'),(28,'Suecia'),(29,'Noruega'),(30,'Finlandia'),(31,'Paraguay'),(32,'Bolivia');
/*!40000 ALTER TABLE `nacionalidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` int unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinario_usuario` int NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT '0',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `creado_en` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `idx_destinario` (`destinario_usuario`),
  CONSTRAINT `fk_notificaciones_usuario` FOREIGN KEY (`destinario_usuario`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE,
  CONSTRAINT `notificaciones_chk_1` CHECK (json_valid(`metadata`))
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (87,'Pago aprobado #119','Tu pago por $100000 fue aprobado y la reserva #167 ha sido confirmada.','pago',74,1,'{\"reserva\":\"167\",\"pago\":\"119\",\"tipo_pago\":\"mercadopago\"}','2025-11-18 22:03:40'),(88,'Pago aprobado #120','Tu pago por $450000 fue aprobado y la reserva #168 ha sido confirmada.','pago',74,1,'{\"reserva\":\"168\",\"pago\":\"120\",\"tipo_pago\":\"mercadopago\"}','2025-11-18 22:04:48'),(89,'Pago aprobado #121','Tu pago por $20000 fue aprobado y la reserva #169 ha sido confirmada.','pago',74,1,'{\"reserva\":\"169\",\"pago\":\"121\",\"tipo_pago\":\"mercadopago\"}','2025-11-18 22:14:35'),(90,'Pago aprobado #122','Tu pago por $150000 fue aprobado y la reserva #170 ha sido confirmada.','pago',74,1,'{\"reserva\":\"170\",\"pago\":\"122\",\"tipo_pago\":\"mercadopago\"}','2025-11-18 22:37:40'),(91,'Pago aprobado #124','Tu pago por $18000 fue aprobado y la reserva #184 ha sido confirmada.','pago',74,1,'{\"reserva\":\"184\",\"pago\":\"124\",\"tipo_pago\":\"mercadopago\"}','2025-11-24 09:51:42'),(92,'Pago aprobado #125','Tu pago por $33000 fue aprobado y la reserva #186 ha sido confirmada.','pago',74,1,'{\"reserva\":\"186\",\"pago\":\"125\",\"tipo_pago\":\"mercadopago\"}','2025-11-24 10:23:14'),(93,'Pago aprobado #126','Tu pago por $18000 fue aprobado y la reserva #187 ha sido confirmada.','pago',74,1,'{\"reserva\":\"187\",\"pago\":\"126\",\"tipo_pago\":\"mercadopago\"}','2025-11-24 10:25:57'),(94,'Pago aprobado #131','Tu pago por $45000 fue aprobado y la reserva #193 ha sido confirmada.','pago',74,1,'{\"reserva\":\"193\",\"pago\":\"131\",\"tipo_pago\":\"mercadopago\"}','2025-11-25 17:04:30'),(95,'Pago aprobado #132','Tu pago por $10000 fue aprobado y la reserva #194 ha sido confirmada.','pago',74,0,'{\"reserva\":\"194\",\"pago\":\"132\",\"tipo_pago\":\"mercadopago\"}','2025-11-25 17:08:48'),(96,'Pago aprobado #133','Tu pago por $468000 fue aprobado y la reserva #196 ha sido confirmada.','pago',74,0,'{\"reserva\":\"196\",\"pago\":\"133\",\"tipo_pago\":\"mercadopago\"}','2025-11-25 18:22:49'),(97,'Pago aprobado #134','Tu pago por $18000 fue aprobado y la reserva #197 ha sido confirmada.','pago',74,0,'{\"reserva\":\"197\",\"pago\":\"134\",\"tipo_pago\":\"mercadopago\"}','2025-11-25 18:25:18');
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `pago_fecha` date DEFAULT NULL,
  `pago_monto` decimal(10,0) DEFAULT NULL,
  `pago_estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `rela_reservas` int NOT NULL,
  `rela_tipo_pago` int NOT NULL,
  `pago_comprobante` varchar(255) DEFAULT NULL,
  `rela_monedas` int DEFAULT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `fk_pago_reservas1_idx` (`rela_reservas`),
  KEY `fk_pago_tipo_pago1_idx` (`rela_tipo_pago`),
  KEY `fk_pago_moneda` (`rela_monedas`),
  CONSTRAINT `fk_pago_moneda` FOREIGN KEY (`rela_monedas`) REFERENCES `monedas` (`id_moneda`),
  CONSTRAINT `fk_pago_reservas1` FOREIGN KEY (`rela_reservas`) REFERENCES `reservas` (`id_reservas`),
  CONSTRAINT `fk_pago_tipo_pago1` FOREIGN KEY (`rela_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
INSERT INTO `pago` VALUES (131,'2025-11-25',45000,'aprobado',193,8,'135226550608',3),(132,'2025-11-25',10000,'aprobado',194,8,'135227184102',3),(133,'2025-11-25',468000,'aprobado',196,8,'135237172214',3),(134,'2025-11-25',18000,'aprobado',197,8,'134622565473',3);
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasajeros`
--

DROP TABLE IF EXISTS `pasajeros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasajeros` (
  `id_pasajeros` int NOT NULL AUTO_INCREMENT,
  `rela_usuario` int DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `rela_nacionalidad` int NOT NULL,
  `rela_tipo_documento` int NOT NULL,
  `numero_documento` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `sexo` enum('Masculino','Femenino','Otro') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pasajeros`),
  UNIQUE KEY `idx_pasajeros_documento` (`rela_tipo_documento`,`numero_documento`),
  KEY `rela_usuario` (`rela_usuario`),
  KEY `rela_nacionalidad` (`rela_nacionalidad`),
  CONSTRAINT `pasajeros_ibfk_1` FOREIGN KEY (`rela_usuario`) REFERENCES `usuarios` (`id_usuarios`),
  CONSTRAINT `pasajeros_ibfk_2` FOREIGN KEY (`rela_nacionalidad`) REFERENCES `nacionalidad` (`id_nacionalidad`),
  CONSTRAINT `pasajeros_ibfk_3` FOREIGN KEY (`rela_tipo_documento`) REFERENCES `tipos_documento` (`id_tipo_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasajeros`
--

LOCK TABLES `pasajeros` WRITE;
/*!40000 ALTER TABLE `pasajeros` DISABLE KEYS */;
INSERT INTO `pasajeros` VALUES (26,74,'Mirco','Aguilar',1,1,'45817260','Masculino','2004-06-06','2025-11-25 18:22:13'),(27,74,'Mirco','Aguilar',1,1,'123456789','Masculino','2025-11-07','2025-11-25 18:24:44');
/*!40000 ALTER TABLE `pasajeros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (27,72,'9221ac9afb6f08f5f10986aa3d11fc7c63c153d4514c13b21cca15fa981841ce','2025-09-12 17:41:54','2025-09-12 19:41:54'),(29,74,'82ec91f6d57f251b636ea0cf78403ea701d59520bbec0eb82477fb311d81bfe4','2025-11-13 20:40:06','2025-11-13 22:40:06');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfiles`
--

DROP TABLE IF EXISTS `perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfiles` (
  `id_perfiles` int NOT NULL AUTO_INCREMENT,
  `perfiles_nombre` varchar(45) NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_perfiles`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles`
--

LOCK TABLES `perfiles` WRITE;
/*!40000 ALTER TABLE `perfiles` DISABLE KEYS */;
INSERT INTO `perfiles` VALUES (1,'Cliente',1),(2,'Administrador',1),(3,'Administrador de hospedaje',1),(5,'Encargado de transporte',1),(13,'Encargado general',1),(14,'Guia',1),(24,'Pruebaa',0),(25,'Prueba',0),(26,'Prueba',0),(27,'Prueba',0);
/*!40000 ALTER TABLE `perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfiles_has_modulos`
--

DROP TABLE IF EXISTS `perfiles_has_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfiles_has_modulos` (
  `perfiles_id_perfiles` int NOT NULL,
  `modulos_id_modulos` int NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`perfiles_id_perfiles`,`modulos_id_modulos`),
  KEY `fk_perfiles_has_modulos_modulos1_idx` (`modulos_id_modulos`),
  KEY `fk_perfiles_has_modulos_perfiles1_idx` (`perfiles_id_perfiles`),
  CONSTRAINT `fk_perfiles_has_modulos_modulos1` FOREIGN KEY (`modulos_id_modulos`) REFERENCES `modulos` (`id_modulos`),
  CONSTRAINT `fk_perfiles_has_modulos_perfiles1` FOREIGN KEY (`perfiles_id_perfiles`) REFERENCES `perfiles` (`id_perfiles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles_has_modulos`
--

LOCK TABLES `perfiles_has_modulos` WRITE;
/*!40000 ALTER TABLE `perfiles_has_modulos` DISABLE KEYS */;
INSERT INTO `perfiles_has_modulos` VALUES (1,3,1),(2,1,1),(2,2,1),(2,3,1),(3,4,1),(3,5,1),(5,4,1),(5,9,1),(14,4,1),(14,10,1);
/*!40000 ALTER TABLE `perfiles_has_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personas` (
  `id_personas` int NOT NULL AUTO_INCREMENT,
  `personas_nombre` varchar(45) DEFAULT NULL,
  `personas_apellido` varchar(45) DEFAULT NULL,
  `personas_fecha_nac` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `personas_dni` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_personas`)
) ENGINE=InnoDB AUTO_INCREMENT=378 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (1,'Juan','Perez','2004-07-07',1,'10000000'),(32,'Mirco','Aguilar','2004-06-06',1,'45817260'),(33,'Miguel','De la Rosa','2004-08-03',1,'1000001'),(34,'Juan','Pérez','1990-05-14',1,'1000002'),(35,'Ana','Gómez','1985-10-20',1,'1000003'),(36,'Carlos','López','1992-08-08',1,'1000004'),(37,'Lucia','Martínez','2000-01-01',1,'1000005'),(38,'Pedro','Ramírez','1998-03-15',1,'1000006'),(39,'María','Díaz','1996-09-12',1,'1000007'),(40,'Martín','Fernández','1987-04-22',1,'1000008'),(41,'Sofía','Alonso','1994-07-09',1,'1000009'),(42,'Nicolás','Silva','1991-02-18',1,'1000010'),(43,'Julieta','Molina','1999-11-30',1,'1000011'),(44,'Diego','Torres','1993-06-21',1,'1000012'),(45,'Valentina','Arias','1990-10-03',1,'1000013'),(46,'Agustín','Cruz','1989-12-25',1,'1000014'),(47,'Camila','Castro','1997-01-17',1,'1000015'),(48,'Bruno','Suárez','1995-08-14',1,'1000016'),(366,'Jose','Lopez','2005-07-01',1,'45800200'),(367,'miguel','perez','0204-07-06',1,'44890250'),(368,'Lucas','Prueba','2004-07-07',1,'45900200'),(369,'Marcos','Gimenez','2000-02-10',1,'40235867'),(370,'Miguel','Ledesma','2004-08-06',1,'45768250'),(371,'Miguel','Mirco Aguilar','2025-08-06',1,'45918720'),(372,'Miguel','De la rosa','2004-08-01',1,'45817260'),(373,'lucas','dominguez','2003-09-05',1,'45817260'),(374,'carlos','ayala','2000-09-02',1,'42346782'),(375,'prueba','prueba','2004-06-06',1,'45817250'),(376,'prueba2','prueba2','2025-10-09',1,'45872012'),(377,'Mirco','Aguilar','2004-06-06',1,'45817260');
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id_proveedores` int NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(255) NOT NULL,
  `cuit` varchar(20) DEFAULT NULL,
  `proveedor_direccion` varchar(255) DEFAULT NULL,
  `proveedor_email` varchar(100) NOT NULL,
  `rela_usuario` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_proveedores`),
  KEY `fk_proveedores_usuario` (`rela_usuario`),
  CONSTRAINT `fk_proveedores_usuario` FOREIGN KEY (`rela_usuario`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'prueba','1','prueba','prueba',78,1,'2025-08-11 19:20:44'),(2,'esto es una prueba','23','aca','hola@gmail.com',78,0,'2025-08-22 14:32:30'),(3,'Hotel Howard Johnson','20-12345678-9','Av. Libertad 456, Formosa','contacto@hotelformosa.com',72,1,'2025-08-22 18:48:49'),(4,'Transporte Rápido','30-23456789-0','Calle Turismo 789, Formosa','info@transporte.com',79,1,'2025-08-22 18:48:49'),(5,'Guías Turísticos Formosa','23-34567890-1','Calle 123, Formosa','contacto@guiasformosa.com',80,1,'2025-08-22 18:48:49');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincias` (
  `id_provincia` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_provincia`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
INSERT INTO `provincias` VALUES (1,'Formosa',1),(4,'Chaco',0),(7,'prueba',0);
/*!40000 ALTER TABLE `provincias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resena`
--

DROP TABLE IF EXISTS `resena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resena` (
  `id_resena` int NOT NULL AUTO_INCREMENT,
  `resena_comentario` varchar(100) DEFAULT NULL,
  `resena_puntuacion` int DEFAULT NULL,
  `resena_fecha` date DEFAULT NULL,
  `rela_usuarios` int NOT NULL,
  PRIMARY KEY (`id_resena`),
  KEY `fk_resena_usuarios1_idx` (`rela_usuarios`),
  CONSTRAINT `fk_resena_usuarios1` FOREIGN KEY (`rela_usuarios`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resena`
--

LOCK TABLES `resena` WRITE;
/*!40000 ALTER TABLE `resena` DISABLE KEYS */;
/*!40000 ALTER TABLE `resena` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas` (
  `id_reservas` int NOT NULL AUTO_INCREMENT,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) DEFAULT '0.00',
  `reservas_estado` varchar(45) DEFAULT NULL,
  `rela_usuarios` int NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_reservas`),
  KEY `fk_reservas_usuarios1_idx` (`rela_usuarios`),
  CONSTRAINT `fk_reservas_usuarios1` FOREIGN KEY (`rela_usuarios`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (193,'2025-11-25 17:04:16',45000.00,'confirmada',74,1),(194,'2025-11-25 17:08:27',10000.00,'confirmada',74,1),(195,'2025-10-25 17:08:27',15000.00,'confirmada',74,1),(196,'2025-11-25 18:22:17',468000.00,'confirmada',74,1),(197,'2025-11-25 18:24:47',18000.00,'confirmada',74,1);
/*!40000 ALTER TABLE `reservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_tour`
--

DROP TABLE IF EXISTS `stock_tour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_tour` (
  `id_stock_tour` int NOT NULL AUTO_INCREMENT,
  `rela_tour` int NOT NULL,
  `fecha` date NOT NULL,
  `cupos_disponibles` int NOT NULL DEFAULT '0',
  `cupos_reservados` int NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_stock_tour`),
  KEY `rela_tour` (`rela_tour`),
  CONSTRAINT `stock_tour_ibfk_1` FOREIGN KEY (`rela_tour`) REFERENCES `tours` (`id_tour`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_tour`
--

LOCK TABLES `stock_tour` WRITE;
/*!40000 ALTER TABLE `stock_tour` DISABLE KEYS */;
INSERT INTO `stock_tour` VALUES (13,6,'2025-10-25',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(14,6,'2025-10-26',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(15,6,'2025-10-27',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(16,6,'2025-10-28',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(17,6,'2025-10-29',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(18,6,'2025-10-30',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(19,6,'2025-10-31',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(20,6,'2025-11-25',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(21,6,'2025-11-26',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(22,6,'2025-11-27',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(23,6,'2025-11-28',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(24,6,'2025-11-29',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(25,6,'2025-11-30',10,0,1,'2025-11-25 16:46:27','2025-11-25 16:46:27'),(26,6,'2025-12-01',10,0,1,'2025-11-25 16:47:48','2025-11-25 16:47:48'),(27,14,'2025-11-25',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08'),(28,14,'2025-11-26',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08'),(29,14,'2025-11-27',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08'),(30,14,'2025-11-28',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08'),(31,14,'2025-11-29',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08'),(32,14,'2025-11-30',10,0,1,'2025-11-25 17:08:08','2025-11-25 17:08:08');
/*!40000 ALTER TABLE `stock_tour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temporadas`
--

DROP TABLE IF EXISTS `temporadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temporadas` (
  `id_temporada` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_temporada`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporadas`
--

LOCK TABLES `temporadas` WRITE;
/*!40000 ALTER TABLE `temporadas` DISABLE KEYS */;
INSERT INTO `temporadas` VALUES (1,'Pruebaa','2025-08-01','2025-08-31',0),(2,'Enero','2025-01-01','2025-01-31',0),(3,'Agosto','2025-08-01','2025-08-31',0),(4,'Febrero','2025-02-01','2025-02-28',1),(5,'prueba2','2025-11-01','2025-11-30',0);
/*!40000 ALTER TABLE `temporadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_contacto`
--

DROP TABLE IF EXISTS `tipo_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_contacto` (
  `id_tipo_contacto` int NOT NULL AUTO_INCREMENT,
  `tipo_contacto_descripcion` varchar(80) NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_tipo_contacto`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_contacto`
--

LOCK TABLES `tipo_contacto` WRITE;
/*!40000 ALTER TABLE `tipo_contacto` DISABLE KEYS */;
INSERT INTO `tipo_contacto` VALUES (1,'Telefono movil',1),(2,'Email alternativo',1),(9,'Prueba',1),(10,'Prueba',1);
/*!40000 ALTER TABLE `tipo_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_pago`
--

DROP TABLE IF EXISTS `tipo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_pago` (
  `id_tipo_pago` int NOT NULL AUTO_INCREMENT,
  `tipo_pago_descripcion` varchar(60) DEFAULT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_tipo_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_pago`
--

LOCK TABLES `tipo_pago` WRITE;
/*!40000 ALTER TABLE `tipo_pago` DISABLE KEYS */;
INSERT INTO `tipo_pago` VALUES (1,'Tarjeta de crédito',1),(2,'Transferencia bancaria',1),(3,'Tarjeta de debito',1),(8,'Mercado Pago',1);
/*!40000 ALTER TABLE `tipo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_proveedores`
--

DROP TABLE IF EXISTS `tipo_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_proveedores` (
  `id_tipo_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_tipo_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_proveedores`
--

LOCK TABLES `tipo_proveedores` WRITE;
/*!40000 ALTER TABLE `tipo_proveedores` DISABLE KEYS */;
INSERT INTO `tipo_proveedores` VALUES (1,'Hospedaje','Hoteles, cabañas, departamentos, hostales, etc.',1),(2,'Transporte','Servicios de transporte de personas, como empresas de colectivos',1),(3,'Guía Turístico','Servicios de guías turísticos para recorridos, visitas guiadas, actividades y tours en distintas zonas turísticas, brindando información sobre la cultura, historia y atracciones locales',1),(4,'Prueba','Prueba',0),(5,'prueba','Prueba2',0),(6,'prueba23','Prueba2',0);
/*!40000 ALTER TABLE `tipo_proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_transporte`
--

DROP TABLE IF EXISTS `tipo_transporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_transporte` (
  `id_tipo_transporte` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(60) DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_tipo_transporte`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_transporte`
--

LOCK TABLES `tipo_transporte` WRITE;
/*!40000 ALTER TABLE `tipo_transporte` DISABLE KEYS */;
INSERT INTO `tipo_transporte` VALUES (1,'Cama',1),(2,'Semi Cama',1),(3,'Vip',1),(6,'Común',1),(7,'Prueba2',0);
/*!40000 ALTER TABLE `tipo_transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_documento`
--

DROP TABLE IF EXISTS `tipos_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_documento` (
  `id_tipo_documento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_tipo_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_documento`
--

LOCK TABLES `tipos_documento` WRITE;
/*!40000 ALTER TABLE `tipos_documento` DISABLE KEYS */;
INSERT INTO `tipos_documento` VALUES (1,'Dni',1),(2,'cuitt',0),(3,'prueba2',0);
/*!40000 ALTER TABLE `tipos_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_habitacion`
--

DROP TABLE IF EXISTS `tipos_habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_habitacion` (
  `id_tipo_habitacion` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `capacidad` int NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_tipo_habitacion`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_habitacion`
--

LOCK TABLES `tipos_habitacion` WRITE;
/*!40000 ALTER TABLE `tipos_habitacion` DISABLE KEYS */;
INSERT INTO `tipos_habitacion` VALUES (1,'Habitación Simple','Habitación para una persona',1,1),(3,'Habitación Triple','Ideal para tres personas',3,1),(4,'Suite','Habitación de lujo con sala de estar',2,1),(16,'prueba2','Prueba344',232,0);
/*!40000 ALTER TABLE `tipos_habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `id_tour` int NOT NULL AUTO_INCREMENT,
  `nombre_tour` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `duracion_horas` time DEFAULT NULL,
  `precio_por_persona` decimal(10,2) DEFAULT NULL,
  `hora_encuentro` time DEFAULT NULL,
  `lugar_encuentro` varchar(100) DEFAULT NULL,
  `imagen_principal` varchar(100) NOT NULL,
  `rela_proveedor` int NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `direccion` varchar(100) NOT NULL,
  `estado_revision` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `motivo_rechazo` text,
  `fecha_revision` datetime DEFAULT NULL,
  `revisado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_tour`),
  KEY `rela_proveedor` (`rela_proveedor`),
  KEY `fk_tour_revisado_por` (`revisado_por`),
  CONSTRAINT `fk_tour_revisado_por` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id_usuarios`),
  CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`rela_proveedor`) REFERENCES `proveedores` (`id_proveedores`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (6,'Paseo en el museo','Un paseo breve por el museo de nuestra provincia','01:00:00',15000.00,'10:00:00','El mastil de la costanera','museo_formosa.jpg',5,1,'2025-09-22 23:36:53','Belgrano 836, P3600ENN Formosa','aprobado',NULL,'2025-10-29 10:47:56',66),(11,'prueba1','Esto es una prueba','02:00:00',1000.00,'12:00:00','Prueba1','1764082223_415730882.jpg',5,1,'2025-11-25 13:25:33','Martha Salotti 400, C1107 CMB, Cdad. Autónoma de Buenos Aires','pendiente',NULL,NULL,NULL),(14,'Paseo por la plaza','Esto es una prueba','02:00:00',10000.00,'12:00:00','Capitulo','1764101233_Plaza_San_Martín_Formosa.jpg',1,1,'2025-11-25 20:07:13','Avenida 25 de Mayo y Avenida 9 de Julio','aprobado',NULL,'2025-11-25 17:07:29',66);
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporte`
--

DROP TABLE IF EXISTS `transporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporte` (
  `id_transporte` int NOT NULL AUTO_INCREMENT,
  `transporte_matricula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `transporte_capacidad` int NOT NULL,
  `rela_tipo_transporte` int NOT NULL,
  `nombre_servicio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `imagen_principal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rela_proveedor` int NOT NULL,
  `fecha_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  `estado_revision` enum('pendiente','aprobado','rechazado') COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `motivo_rechazo` text COLLATE utf8mb4_general_ci,
  `fecha_revision` datetime DEFAULT NULL,
  `revisado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_transporte`),
  KEY `fk_tipo_transporte` (`rela_tipo_transporte`),
  KEY `fk_proveedor_transporte` (`rela_proveedor`),
  KEY `fk_transporte_revisado_por` (`revisado_por`),
  CONSTRAINT `fk_proveedor_transporte` FOREIGN KEY (`rela_proveedor`) REFERENCES `proveedores` (`id_proveedores`),
  CONSTRAINT `fk_tipo_transporte` FOREIGN KEY (`rela_tipo_transporte`) REFERENCES `tipo_transporte` (`id_tipo_transporte`),
  CONSTRAINT `fk_transporte_revisado_por` FOREIGN KEY (`revisado_por`) REFERENCES `usuarios` (`id_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte`
--

LOCK TABLES `transporte` WRITE;
/*!40000 ALTER TABLE `transporte` DISABLE KEYS */;
INSERT INTO `transporte` VALUES (3,'ABC123',42,2,'Colectivo Formosa','Servicio de colectivo interurbano en Formosa','1763742747_colectivo_formosa.jpg',4,'2025-08-05 19:31:13',1,'aprobado',NULL,'2025-10-29 10:47:54',66),(12,'wyz123',0,2,'Prueba','prueba','1763749700_532056808.jpg',4,'2025-11-05 22:19:35',1,'pendiente',NULL,NULL,NULL),(13,'RCT582',56,2,'Viaje del Norte','Este transporte ofrece un servicio cómodo para grupos medianos, ideal para viajes de media y larga distancia. Con asientos tipo Semi Cama, los pasajeros pueden disfrutar de un viaje más relajado y confortable, mientras el vehículo, con capacidad para 56 personas, asegura un desplazamiento eficiente para excursiones, traslados turísticos o rutas regulares. El servicio “Viaje del Norte” está diseñado para combinar seguridad, comodidad y eficiencia en cada trayecto.','1763984463_flechabusjpg.webp',1,'2025-11-24 08:41:03',1,'aprobado',NULL,'2025-11-24 08:41:40',66);
/*!40000 ALTER TABLE `transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporte_pisos`
--

DROP TABLE IF EXISTS `transporte_pisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporte_pisos` (
  `id_piso` int NOT NULL AUTO_INCREMENT,
  `rela_transporte` int NOT NULL,
  `numero_piso` int NOT NULL,
  `filas` int NOT NULL,
  `asientos_por_fila` int NOT NULL,
  PRIMARY KEY (`id_piso`),
  KEY `rela_transporte` (`rela_transporte`),
  CONSTRAINT `transporte_pisos_ibfk_1` FOREIGN KEY (`rela_transporte`) REFERENCES `transporte` (`id_transporte`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_pisos`
--

LOCK TABLES `transporte_pisos` WRITE;
/*!40000 ALTER TABLE `transporte_pisos` DISABLE KEYS */;
INSERT INTO `transporte_pisos` VALUES (8,12,1,5,3),(9,12,2,4,4),(10,3,1,6,4),(11,3,2,8,4),(12,13,1,7,4),(13,13,2,7,4);
/*!40000 ALTER TABLE `transporte_pisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporte_rutas`
--

DROP TABLE IF EXISTS `transporte_rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporte_rutas` (
  `id_ruta` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `trayecto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `rela_ciudad_origen` int DEFAULT NULL,
  `rela_ciudad_destino` int DEFAULT NULL,
  `duracion` time DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `precio_por_persona` decimal(10,2) NOT NULL,
  `rela_transporte` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_ruta`),
  KEY `fk_transporte_rutas` (`rela_transporte`),
  KEY `fk_ruta_origen` (`rela_ciudad_origen`),
  KEY `fk_ruta_destino` (`rela_ciudad_destino`),
  CONSTRAINT `fk_ruta_destino` FOREIGN KEY (`rela_ciudad_destino`) REFERENCES `ciudades` (`id_ciudad`),
  CONSTRAINT `fk_ruta_origen` FOREIGN KEY (`rela_ciudad_origen`) REFERENCES `ciudades` (`id_ciudad`),
  CONSTRAINT `fk_transporte_rutas` FOREIGN KEY (`rela_transporte`) REFERENCES `transporte` (`id_transporte`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_rutas`
--

LOCK TABLES `transporte_rutas` WRITE;
/*!40000 ALTER TABLE `transporte_rutas` DISABLE KEYS */;
INSERT INTO `transporte_rutas` VALUES (10,'Formosa - Clorinda','Formosa - Herradura - Clorinda',1,3,'02:00:00','Viaje premium a Clorinda',15000.00,3,1),(14,'prueba22','Prueba2 - Prueba21',7,8,'01:40:00','Viaje directo de capital a prueba',9000.00,3,1),(23,'Formosa - Clorinda','Formosa - Herradura - Clorinda',1,3,'02:00:00','Viaje a clorinda con una parada en Herradura.',18000.00,13,1),(24,'Formosa - Clorinda','Formosa - Herradura - Clorinda',1,3,'02:00:00','Viaje a clorinda con una parada en Herradura.',18000.00,12,1);
/*!40000 ALTER TABLE `transporte_rutas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuarios` int NOT NULL AUTO_INCREMENT,
  `usuarios_nombre_usuario` varchar(25) NOT NULL,
  `usuarios_email` varchar(45) NOT NULL,
  `usuarios_fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuarios_password` varchar(245) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `rela_personas` int DEFAULT NULL,
  `rela_perfiles` int NOT NULL,
  PRIMARY KEY (`id_usuarios`),
  KEY `fk_usuarios_personas_idx` (`rela_personas`),
  KEY `fk_usuarios_tipo_de_usuarios1_idx` (`rela_perfiles`),
  CONSTRAINT `fk_usuarios_personas` FOREIGN KEY (`rela_personas`) REFERENCES `personas` (`id_personas`),
  CONSTRAINT `fk_usuarios_tipo_de_usuarios1` FOREIGN KEY (`rela_perfiles`) REFERENCES `perfiles` (`id_perfiles`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (66,'jperez','juan@gmail.com','2025-06-27 17:00:20','$2y$10$EP9hGHC3LErzPlsgN7J70e85ER.Hdjx.txhmABH6XsIHNefkfL3OW',1,1,2),(72,'marcosgimenez','hola@gmail.com','2025-07-08 10:59:17','$2y$10$oRZ4l80wAB8A/lzXtKwbq.fa9SxYB9n5acruXxcMQUKe27TpD0O1.',1,369,3),(74,'migueledesma','mircoaguilar02@gmail.com','2025-08-20 20:18:17','$2y$10$BEGQ4j2oTfff6k/WhvRkVe.OXBmwGKLMuxMJqD12biy8in/HWFmXK',1,370,1),(78,'prueba','prueba','2025-08-20 20:18:17','$2y$10$BEGQ4j2oTfff6k/WhvRkVe.OXBmwGKLMuxMJqD12biy8in/HWFmXK',1,1,5),(79,'lucasdominguez','lucas@gmail.com','2025-09-16 20:43:46','$2y$10$mnYo.CXZM7GA6x8tUtN/CeyRDLbrKPGpagUFAmXHYdeqeAv5dU7Ou',1,373,5),(80,'carlosayala','carlos@gmail.com','2025-09-22 14:04:40','$2y$10$GTR8UEy2mIvSKS9wFREbY.1WvdocRCyocYMeWyceyRJwx0iCsDrT.',1,374,14),(83,'maguilar','mircoaguilar@gmail.com','2025-11-05 22:22:35','$2y$10$yLy2lk349tdFT1/ajepIh.eLVmk8/bIjcey0FadEPxvc5yhktLrLu',1,377,1),(86,'poc75','lucia2@gmail.com','2025-11-12 17:41:46','$2y$10$A9MQw9kOHp1/BObq5zWpy.PDRURIz.FhLRjlGnJlCGN9/S6Gpshtm',0,35,1),(87,'poc7','lucia@gmail.com','2025-11-12 19:17:06','$2y$10$/iNRNPOFowoLXPgJMdfV.OXnFgSgGKDaiLc9m4G7.DGB0xm499e7.',1,35,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viaje_asientos`
--

DROP TABLE IF EXISTS `viaje_asientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `viaje_asientos` (
  `id_asiento` int NOT NULL AUTO_INCREMENT,
  `rela_viaje` int NOT NULL,
  `piso` tinyint DEFAULT '1',
  `fila` int NOT NULL,
  `columna` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `ocupado` tinyint(1) DEFAULT '0',
  `rela_reserva` int DEFAULT NULL,
  PRIMARY KEY (`id_asiento`),
  KEY `rela_viaje` (`rela_viaje`),
  KEY `rela_reserva` (`rela_reserva`),
  CONSTRAINT `viaje_asientos_ibfk_1` FOREIGN KEY (`rela_viaje`) REFERENCES `viajes` (`id_viajes`),
  CONSTRAINT `viaje_asientos_ibfk_2` FOREIGN KEY (`rela_reserva`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viaje_asientos`
--

LOCK TABLES `viaje_asientos` WRITE;
/*!40000 ALTER TABLE `viaje_asientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `viaje_asientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viajes`
--

DROP TABLE IF EXISTS `viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `viajes` (
  `id_viajes` int NOT NULL AUTO_INCREMENT,
  `viaje_fecha` date DEFAULT NULL,
  `rela_transporte_rutas` int NOT NULL,
  `hora_salida` time NOT NULL,
  `hora_llegada` time NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_viajes`),
  KEY `fk_viajes_transporte_rutas` (`rela_transporte_rutas`),
  CONSTRAINT `fk_viajes_transporte_rutas` FOREIGN KEY (`rela_transporte_rutas`) REFERENCES `transporte_rutas` (`id_ruta`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viajes`
--

LOCK TABLES `viajes` WRITE;
/*!40000 ALTER TABLE `viajes` DISABLE KEYS */;
INSERT INTO `viajes` VALUES (3,'2025-12-20',10,'08:00:00','10:00:00',1),(6,'2025-11-29',14,'12:00:00','13:40:00',1),(7,'2025-11-27',14,'09:00:00','10:40:00',1),(8,'2025-11-30',23,'09:00:00','11:00:00',1),(9,'2025-11-30',24,'09:00:00','10:40:00',1);
/*!40000 ALTER TABLE `viajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'viajar'
--

--
-- Dumping routines for database 'viajar'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-25 18:41:32
