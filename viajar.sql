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
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (157,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Doble, capacidad: 2','2025-11-26 10:51:35'),(158,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Matrimonial, capacidad: 2','2025-11-26 10:51:59'),(159,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Cuádruple, capacidad: 4','2025-11-26 10:52:40'),(160,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Ejecutiva , capacidad: 2','2025-11-26 10:53:13'),(161,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Superior, capacidad: 2','2025-11-26 10:53:58'),(162,66,'Actualización de tipo de habitación','Se actualizó la habitación (ID: 20) a nombre: Habitación Ejecutiva , capacidad: 2','2025-11-26 10:54:06'),(163,66,'Actualización de tipo de habitación','Se actualizó la habitación (ID: 19) a nombre: Habitación Cuádruple, capacidad: 4','2025-11-26 10:54:14'),(164,66,'Alta de tipo de habitación','Se creó la habitación: Habitación Deluxe, capacidad: 2','2025-11-26 10:54:50'),(165,66,'Alta de tipo de habitación','Se creó la habitación: prueba','2025-11-26 11:24:16'),(166,66,'Actualización de tipo de habitación','Se actualizó la habitación (ID: 27) a nombre: prueba2','2025-11-26 11:25:10'),(167,66,'Baja lógica de tipo de habitación','Se eliminó lógicamente la habitación (ID: 27)','2025-11-26 11:25:14'),(168,74,'Reserva creada','El usuario ID 74 creó la reserva #198 por $19000','2025-11-26 11:48:37'),(169,74,'Reserva creada','El usuario ID 74 creó la reserva #199 por $19000','2025-11-26 15:57:43'),(170,74,'Reserva creada','El usuario ID 74 creó la reserva #200 por $19000','2025-11-26 16:10:40'),(171,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #200 por $19000 (Mercado Pago ID: 134737782789)','2025-11-26 16:12:08'),(172,74,'Reserva creada','El usuario ID 74 creó la reserva #201 por $21000','2025-11-26 21:50:43'),(173,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #201 por $21000 (Mercado Pago ID: 134780333031)','2025-11-26 21:51:14'),(174,74,'Reserva creada','El usuario ID 74 creó la reserva #202 por $21000','2025-11-26 21:56:14'),(175,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #202 por $21000 (Mercado Pago ID: 135396202124)','2025-11-26 21:56:33'),(176,74,'Reserva creada','El usuario ID 74 creó la reserva #203 por $15000','2025-11-27 03:20:03'),(177,74,'Reserva creada','El usuario ID 74 creó la reserva #204 por $15000','2025-11-27 03:25:53'),(178,74,'Reserva creada','El usuario ID 74 creó la reserva #205 por $15000','2025-11-27 10:53:03'),(179,74,'Reserva creada','El usuario ID 74 creó la reserva #206 por $15000','2025-11-27 11:00:23'),(180,74,'Reserva creada','El usuario ID 74 creó la reserva #207 por $15000','2025-11-27 11:02:38'),(181,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #207 por $15000 (Mercado Pago ID: 134834060415)','2025-11-27 11:03:08'),(182,74,'Reserva creada','El usuario ID 74 creó la reserva #213 por $15000','2025-11-27 11:14:11'),(183,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #213 por $15000 (Mercado Pago ID: 134835375561)','2025-11-27 11:14:33'),(184,74,'Reserva creada','El usuario ID 74 creó la reserva #217 por $15000','2025-11-27 11:37:08'),(185,74,'Reserva creada','El usuario ID 74 creó la reserva #218 por $15000','2025-11-27 11:39:18'),(186,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #218 por $15000 (Mercado Pago ID: 135454295304)','2025-11-27 11:39:48'),(187,74,'Reserva creada','El usuario ID 74 creó la reserva #224 por $15000','2025-11-27 12:05:33'),(188,74,'Reserva creada','El usuario ID 74 creó la reserva #227 por $15000','2025-11-27 12:13:35'),(189,74,'Reserva creada','El usuario ID 74 creó la reserva #238 por $15000','2025-11-27 13:03:26'),(190,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #238 por $15000 (Mercado Pago ID: 134850237473)','2025-11-27 13:04:36'),(191,74,'Reserva creada','El usuario ID 74 creó la reserva #239 por $15000','2025-11-27 13:15:08'),(192,74,'Reserva creada','El usuario ID 74 creó la reserva #240 por $15000','2025-11-27 13:20:40'),(193,74,'Reserva creada','El usuario ID 74 creó la reserva #241 por $15000','2025-11-27 13:21:01'),(194,74,'Reserva creada','El usuario ID 74 creó la reserva #242 por $15000','2025-11-27 13:24:26'),(195,74,'Reserva creada','El usuario ID 74 creó la reserva #243 por $15000','2025-11-27 13:24:34'),(196,74,'Reserva creada','El usuario ID 74 creó la reserva #244 por $15000','2025-11-27 13:25:24'),(197,74,'Reserva creada','El usuario ID 74 creó la reserva #245 por $15000','2025-11-27 13:33:59'),(198,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #245 por $15000 (Mercado Pago ID: 134854142773)','2025-11-27 13:34:32'),(199,74,'Reserva creada','El usuario ID 74 creó la reserva #246 por $30000','2025-11-27 13:37:54'),(200,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #246 por $30000 (Mercado Pago ID: 135469968498)','2025-11-27 13:38:12'),(201,74,'Reserva creada','El usuario ID 74 creó la reserva #247 por $30000','2025-11-27 15:35:57'),(202,74,'Reserva creada','El usuario ID 74 creó la reserva #248 por $70000','2025-11-27 15:50:34'),(203,74,'Reserva creada','El usuario ID 74 creó la reserva #249 por $70000','2025-11-27 15:53:18'),(204,74,'Reserva creada','El usuario ID 74 creó la reserva #250 por $30000','2025-11-27 15:56:45'),(205,74,'Reserva creada','El usuario ID 74 creó la reserva #251 por $30000','2025-11-27 16:04:50'),(206,74,'Reserva creada','El usuario ID 74 creó la reserva #252 por $15000','2025-11-27 16:05:54'),(207,74,'Reserva creada','El usuario ID 74 creó la reserva #253 por $15000','2025-11-27 16:12:01'),(208,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #253 por $15000 (Mercado Pago ID: 135486768390)','2025-11-27 16:12:17'),(209,74,'Reserva creada','El usuario ID 74 creó la reserva #254 por $15000','2025-11-27 16:14:44'),(210,74,'Confirmación de pago','El usuario ID 74 confirmó el pago de la reserva #254 por $15000 (Mercado Pago ID: 135487062438)','2025-11-27 16:14:58'),(211,74,'Reserva creada','El usuario ID 74 creó la reserva #255 por $38500','2025-11-27 18:05:27'),(212,74,'Reserva creada','El usuario ID 74 creó la reserva #256 por $7000','2025-11-27 18:09:01'),(213,74,'Reserva creada','El usuario ID 74 creó la reserva #257 por $21000','2025-11-27 18:13:11'),(214,74,'Reserva creada','El usuario ID 74 creó la reserva #258 por $21000','2025-11-27 18:17:07'),(215,74,'Reserva creada','El usuario ID 74 creó la reserva #259 por $21000','2025-11-27 18:18:52'),(216,74,'Confirmación de pago','Usuario 74 confirmó pago de reserva #259 por $21000 (MP ID: 135502828336)','2025-11-27 18:19:30'),(217,74,'Reserva creada','El usuario ID 74 creó la reserva #260 por $55000','2025-11-27 18:21:45'),(218,74,'Confirmación de pago','Usuario 74 confirmó pago de reserva #260 por $55000 (MP ID: 134887597925)','2025-11-27 18:22:32'),(219,74,'Reserva creada','El usuario ID 74 creó la reserva #261 por $48000','2025-11-27 18:25:00'),(220,74,'Confirmación de pago','Usuario 74 confirmó pago de reserva #261 por $48000 (MP ID: 135503467614)','2025-11-27 18:25:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb3;
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
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8mb3;
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
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_hotel`
--

LOCK TABLES `detalle_reserva_hotel` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_hotel` DISABLE KEYS */;
INSERT INTO `detalle_reserva_hotel` VALUES (116,348,20,'2025-11-27','2025-11-28',1,'pendiente'),(117,355,20,'2025-11-27','2025-11-29',2,'confirmada'),(118,358,20,'2025-11-27','2025-11-29',2,'confirmada');
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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_tour`
--

LOCK TABLES `detalle_reserva_tour` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_tour` DISABLE KEYS */;
INSERT INTO `detalle_reserva_tour` VALUES (107,349,15,'2025-11-30','pendiente'),(108,351,15,'2025-11-29','pendiente'),(109,352,15,'2025-11-30','pendiente'),(110,353,15,'2025-11-30','pendiente'),(111,354,15,'2025-11-30','confirmada'),(112,357,15,'2025-11-29','confirmada'),(113,360,15,'2025-11-28','confirmada');
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_transporte`
--

LOCK TABLES `detalle_reserva_transporte` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_transporte` DISABLE KEYS */;
INSERT INTO `detalle_reserva_transporte` VALUES (57,356,10,2,12,'2025-11-27',15000.00,32,'confirmada'),(58,359,10,1,7,'2025-11-27',15000.00,32,'confirmada');
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
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reservas`
--

LOCK TABLES `detalle_reservas` WRITE;
/*!40000 ALTER TABLE `detalle_reservas` DISABLE KEYS */;
INSERT INTO `detalle_reservas` VALUES (348,255,'hotel',1,9500.00,9500.00),(349,255,'tour',2,7000.00,14000.00),(350,255,'transporte',1,15000.00,15000.00),(351,256,'tour',1,7000.00,7000.00),(352,257,'tour',3,7000.00,21000.00),(353,258,'tour',3,7000.00,21000.00),(354,259,'tour',3,7000.00,21000.00),(355,260,'hotel',1,9500.00,19000.00),(356,260,'transporte',1,15000.00,15000.00),(357,260,'tour',3,7000.00,21000.00),(358,261,'hotel',1,9500.00,19000.00),(359,261,'transporte',1,15000.00,15000.00),(360,261,'tour',2,7000.00,14000.00);
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
-- Table structure for table `ganancias`
--

DROP TABLE IF EXISTS `ganancias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ganancias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_reserva` int NOT NULL,
  `tipo_servicio` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ganancia_neta` decimal(10,2) NOT NULL,
  `fecha_calculo` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_reserva` (`id_reserva`),
  CONSTRAINT `ganancias_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ganancias`
--

LOCK TABLES `ganancias` WRITE;
/*!40000 ALTER TABLE `ganancias` DISABLE KEYS */;
INSERT INTO `ganancias` VALUES (1,259,'reserva',2100.00,'2025-11-27 18:19:30'),(2,260,'reserva',5500.00,'2025-11-27 18:22:32'),(3,261,'reserva',4800.00,'2025-11-27 18:25:18');
/*!40000 ALTER TABLE `ganancias` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel`
--

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;
INSERT INTO `hotel` VALUES (71,'Howard Johnson Formosa','2025-11-26 11:06:46','692709760bd03_howard1.jpg',1,1,3,1,'aprobado',NULL,'2025-11-26 11:07:23',66);
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones`
--

LOCK TABLES `hotel_habitaciones` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones` DISABLE KEYS */;
INSERT INTO `hotel_habitaciones` VALUES (20,71,1,2,9500.00,'Habitación cómoda y funcional, ideal para dos personas. Cuenta con una cama matrimonial, baño privado, aire acondicionado, TV de pantalla plana, conexión Wi-Fi gratuita.',1,'2025-11-26 14:29:25','[\"assets/images/hab_692712f74f445_WhatsApp-Image-2022-03-22-at-21.42.07-3.jpeg\",\"assets/images/hab_692712f74f66a_WhatsApp-Image-2022-03-22-at-21.42.07-4.jpeg\",\"assets/images/hab_692712f74f7ec_WhatsApp-Image-2022-03-22-at-21.42.07.jpeg\",\"assets/images/hab_692712f74fbcc_WhatsApp-Image-2022-03-22-at-21.42.07-2.jpeg\"]');
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
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones_stock`
--

LOCK TABLES `hotel_habitaciones_stock` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones_stock` DISABLE KEYS */;
INSERT INTO `hotel_habitaciones_stock` VALUES (175,20,'2025-11-26',2,1),(176,20,'2025-11-27',0,1),(177,20,'2025-11-28',0,1),(178,20,'2025-11-29',2,1),(179,20,'2025-11-30',2,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoteles_info`
--

LOCK TABLES `hoteles_info` WRITE;
/*!40000 ALTER TABLE `hoteles_info` DISABLE KEYS */;
INSERT INTO `hoteles_info` VALUES (48,71,'Avenida Gutnisky 3754, Formosa Capital, 3600','El Hotel Howard Johnson Formosa ofrece una experiencia de alojamiento cómoda y moderna para quienes visitan la región. Con una excelente ubicación cerca de las principales atracciones turísticas y zonas comerciales, el hotel cuenta con amplias y bien equipadas habitaciones, ideales tanto para viajeros de negocios como para aquellos en busca de descanso. Además, dispone de servicios exclusivos como restaurante, gimnasio, pileta y salas de reuniones, todo en un ambiente relajado y acogedor. Con su atención al detalle y hospitalidad, el Howard Johnson Formosa es la opción perfecta para una estancia placentera y sin preocupaciones.','Wifi, Desayuno buffet, Piscina exterior, Gimnasio, Restaurante y bar, Estacionamiento gratuito, Sala de reuniones y eventos, Servicio de habitación 24 horas, Recepción 24 horas, Aire acondicionado y Calefacción, Lavandería y tintorería, Caja de seguridad, Transporte al aeropuerto, Zona de juegos para niños.','La cancelación gratuita es posible hasta 24 horas antes de la fecha de llegada. Si la cancelación se realiza menos de 24 horas antes de la llegada o en caso de no presentación, se aplicará un cargo equivalente a la primera noche de la reserva.','El check-in se realiza a partir de las 14:00 horas y el check-out debe ser antes de las 12:00 horas. Se requiere presentar un documento de identidad válido al momento del check-in. El hotel es un establecimiento libre de humo en todas sus áreas. No se permiten mascotas, excepto en casos especiales y con autorización previa. Las personas adicionales no registradas en la reserva deben ser notificadas al hotel antes de su llegada y pueden generar cargos adicionales.','[\"692709760e499_11307308.jpg\",\"692709760e669_11307326.jpg\",\"692709760e7b8_138957414.jpg\",\"692709760e902_560131983.jpg\",\"692709760ea4b_560131994.jpg\",\"692709760eb8b_ostrovok-445092-0c1a5a-825552.jpg\",\"692709760ecdc_ostrovok-445092-4e791c-079585.jpg\",\"692709760ee3a_ostrovok-445092-f26b12-029944.jpg\"]');
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
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (98,'Pago aprobado #137','Tu pago por $19000 fue aprobado y la reserva #200 ha sido confirmada.','pago',74,1,'{\"reserva\":\"200\",\"pago\":\"137\",\"tipo_pago\":\"mercadopago\"}','2025-11-26 16:12:08'),(99,'Pago aprobado #138','Tu pago por $21000 fue aprobado y la reserva #201 ha sido confirmada.','pago',74,1,'{\"reserva\":\"201\",\"pago\":\"138\",\"tipo_pago\":\"mercadopago\"}','2025-11-26 21:51:14'),(100,'Pago aprobado #139','Tu pago por $21000 fue aprobado y la reserva #202 ha sido confirmada.','pago',74,1,'{\"reserva\":\"202\",\"pago\":\"139\",\"tipo_pago\":\"mercadopago\"}','2025-11-26 21:56:33'),(101,'Pago aprobado #144','Tu pago por $15000 fue aprobado y la reserva #207 ha sido confirmada.','pago',74,0,'{\"reserva\":\"207\",\"pago\":\"144\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 11:03:08'),(102,'Pago aprobado #145','Tu pago por $15000 fue aprobado y la reserva #213 ha sido confirmada.','pago',74,0,'{\"reserva\":\"213\",\"pago\":\"145\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 11:14:33'),(103,'Pago aprobado #146','Tu pago por $15000 fue aprobado y la reserva #218 ha sido confirmada.','pago',74,0,'{\"reserva\":\"218\",\"pago\":\"146\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 11:39:48'),(104,'Pago aprobado #147','Tu pago por $15000 fue aprobado y la reserva #238 ha sido confirmada.','pago',74,0,'{\"reserva\":\"238\",\"pago\":\"147\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 13:04:36'),(105,'Pago aprobado #154','Tu pago por $15000 fue aprobado y la reserva #245 ha sido confirmada.','pago',74,0,'{\"reserva\":\"245\",\"pago\":\"154\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 13:34:32'),(106,'Pago aprobado #155','Tu pago por $30000 fue aprobado y la reserva #246 ha sido confirmada.','pago',74,0,'{\"reserva\":\"246\",\"pago\":\"155\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 13:38:12'),(107,'Pago aprobado #162','Tu pago por $15000 fue aprobado y la reserva #253 ha sido confirmada.','pago',74,0,'{\"reserva\":\"253\",\"pago\":\"162\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 16:12:17'),(108,'Pago aprobado #163','Tu pago por $15000 fue aprobado y la reserva #254 ha sido confirmada.','pago',74,0,'{\"reserva\":\"254\",\"pago\":\"163\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 16:14:58'),(109,'Pago aprobado #168','Tu pago por $21000 fue aprobado y la reserva #259 ha sido confirmada.','pago',74,0,'{\"reserva\":\"259\",\"pago\":\"168\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 18:19:30'),(110,'Pago aprobado #169','Tu pago por $55000 fue aprobado y la reserva #260 ha sido confirmada.','pago',74,0,'{\"reserva\":\"260\",\"pago\":\"169\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 18:22:32'),(111,'Pago aprobado #170','Tu pago por $48000 fue aprobado y la reserva #261 ha sido confirmada.','pago',74,0,'{\"reserva\":\"261\",\"pago\":\"170\",\"tipo_pago\":\"mercadopago\"}','2025-11-27 18:25:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
INSERT INTO `pago` VALUES (164,'2025-11-27',38500,'pendiente',255,8,NULL,3),(165,'2025-11-27',7000,'pendiente',256,8,NULL,3),(166,'2025-11-27',21000,'aprobado',257,8,'134886519213',3),(167,'2025-11-27',21000,'aprobado',258,8,'135502437228',3),(168,'2025-11-27',21000,'aprobado',259,8,'135502828336',3),(169,'2025-11-27',55000,'aprobado',260,8,'134887597925',3),(170,'2025-11-27',48000,'aprobado',261,8,'135503467614',3);
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
  UNIQUE KEY `ux_pasajeros_usuario_documento` (`rela_usuario`,`numero_documento`),
  KEY `rela_nacionalidad` (`rela_nacionalidad`),
  CONSTRAINT `pasajeros_ibfk_1` FOREIGN KEY (`rela_usuario`) REFERENCES `usuarios` (`id_usuarios`),
  CONSTRAINT `pasajeros_ibfk_2` FOREIGN KEY (`rela_nacionalidad`) REFERENCES `nacionalidad` (`id_nacionalidad`),
  CONSTRAINT `pasajeros_ibfk_3` FOREIGN KEY (`rela_tipo_documento`) REFERENCES `tipos_documento` (`id_tipo_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasajeros`
--

LOCK TABLES `pasajeros` WRITE;
/*!40000 ALTER TABLE `pasajeros` DISABLE KEYS */;
INSERT INTO `pasajeros` VALUES (32,74,'Mirco','Aguilar',1,1,'45817260','Masculino','2004-06-06','2025-11-27 01:41:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
INSERT INTO `reservas` VALUES (255,'2025-11-27 18:05:27',38500.00,'pendiente',74,1),(256,'2025-11-27 18:09:01',7000.00,'pendiente',74,1),(257,'2025-11-27 18:13:11',21000.00,'confirmada',74,1),(258,'2025-11-27 18:17:07',21000.00,'confirmada',74,1),(259,'2025-11-27 18:18:52',21000.00,'confirmada',74,1),(260,'2025-11-27 18:21:45',55000.00,'confirmada',74,1),(261,'2025-11-27 18:25:00',48000.00,'confirmada',74,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_tour`
--

LOCK TABLES `stock_tour` WRITE;
/*!40000 ALTER TABLE `stock_tour` DISABLE KEYS */;
INSERT INTO `stock_tour` VALUES (33,15,'2025-11-26',10,0,1,'2025-11-26 21:47:23','2025-11-26 21:47:23'),(34,15,'2025-11-27',10,0,1,'2025-11-26 21:47:23','2025-11-26 21:47:23'),(35,15,'2025-11-28',8,2,1,'2025-11-26 21:47:23','2025-11-27 18:25:18'),(36,15,'2025-11-29',7,3,1,'2025-11-26 21:47:23','2025-11-27 18:22:32'),(37,15,'2025-11-30',1,9,1,'2025-11-26 21:47:23','2025-11-27 18:19:30');
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
INSERT INTO `tipo_transporte` VALUES (1,'Cama',1),(2,'Semi Cama',1),(3,'Vip',1),(6,'Común',1);
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
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_tipo_habitacion`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_habitacion`
--

LOCK TABLES `tipos_habitacion` WRITE;
/*!40000 ALTER TABLE `tipos_habitacion` DISABLE KEYS */;
INSERT INTO `tipos_habitacion` VALUES (1,'Habitación Simple',1),(3,'Habitación Triple',1),(4,'Suite',1),(17,'Habitación Doble',1),(18,'Habitación Matrimonial',1),(19,'Habitación Cuádruple',1),(20,'Habitación Ejecutiva ',1),(21,'Habitación Superior',1),(22,'Habitación Deluxe',1),(23,'Habitación Familiar',1),(24,'Habitación Adaptada o Accesible',1),(25,'Habitación con Balcón',1),(26,'Penthouse',1),(27,'prueba2',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (15,'Paseo en el museo','Embárcate en un fascinante recorrido por el Museo de Formosa con un tour guiado que te permitirá conocer la historia, el arte y la cultura de la región en profundidad. A lo largo del tour, un guía especializado te acompañará a través de las diversas salas del museo, explicando las colecciones y los contextos históricos de cada exhibición. Desde las piezas arqueológicas de los pueblos originarios hasta las obras de arte contemporáneo, descubrirás cómo la identidad formoseña se ha forjado a lo largo del tiempo. Este recorrido es ideal para quienes desean conocer más sobre la riqueza cultural de Formosa y explorar sus raíces históricas de manera amena y educativa.','01:00:00',7000.00,'18:00:00','El mastil de la costanera','1764204385_museo-duffard.jpg',5,1,'2025-11-27 00:46:25','Belgrano 836, P3600ENN Formosa','aprobado',NULL,'2025-11-26 21:46:53',66);
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte`
--

LOCK TABLES `transporte` WRITE;
/*!40000 ALTER TABLE `transporte` DISABLE KEYS */;
INSERT INTO `transporte` VALUES (14,'AHJ823',40,2,'Colectivo Formosa','Colectivo Semi Cama con asientos reclinables, aire acondicionado, servicio de Wi-Fi gratuito y pantallas individuales','1764185238_321612905_1227429697843065_3729564296211320379_n.jpg',4,'2025-11-26 16:27:18',1,'aprobado',NULL,'2025-11-26 16:27:42',66);
/*!40000 ALTER TABLE `transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transporte_asientos_bloqueados`
--

DROP TABLE IF EXISTS `transporte_asientos_bloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transporte_asientos_bloqueados` (
  `id_block` int NOT NULL AUTO_INCREMENT,
  `id_viaje` int NOT NULL,
  `piso` int NOT NULL,
  `numero_asiento` int NOT NULL,
  `id_usuario` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id_block`),
  UNIQUE KEY `ux_viaje_piso_asiento` (`id_viaje`,`piso`,`numero_asiento`),
  KEY `fk_bloqueo_usuario` (`id_usuario`),
  CONSTRAINT `fk_bloqueo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuarios`),
  CONSTRAINT `fk_bloqueo_viaje` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id_viajes`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_asientos_bloqueados`
--

LOCK TABLES `transporte_asientos_bloqueados` WRITE;
/*!40000 ALTER TABLE `transporte_asientos_bloqueados` DISABLE KEYS */;
INSERT INTO `transporte_asientos_bloqueados` VALUES (178,10,2,12,74,'2025-11-27 18:21:28','2025-11-27 18:26:28'),(179,10,1,7,74,'2025-11-27 18:24:45','2025-11-27 18:29:45');
/*!40000 ALTER TABLE `transporte_asientos_bloqueados` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_pisos`
--

LOCK TABLES `transporte_pisos` WRITE;
/*!40000 ALTER TABLE `transporte_pisos` DISABLE KEYS */;
INSERT INTO `transporte_pisos` VALUES (14,14,1,4,4),(15,14,2,6,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_rutas`
--

LOCK TABLES `transporte_rutas` WRITE;
/*!40000 ALTER TABLE `transporte_rutas` DISABLE KEYS */;
INSERT INTO `transporte_rutas` VALUES (25,'Formosa - Clorinda','Formosa - Herradura - Clorinda',1,3,'03:00:00','Este viaje te lleva de Formosa Capital a Clorinda, con una parada opcional en Herradura. Viaja cómodo, con asientos reclinables y aire acondicionado, disfrutando del paisaje mientras avanzas hacia tu destino',15000.00,14,1);
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
INSERT INTO `usuarios` VALUES (66,'jperez','juan@gmail.com','2025-06-27 17:00:20','$2y$10$EP9hGHC3LErzPlsgN7J70e85ER.Hdjx.txhmABH6XsIHNefkfL3OW',1,1,2),(72,'marcosgimenez','hola@gmail.com','2025-07-08 10:59:17','$2y$10$oRZ4l80wAB8A/lzXtKwbq.fa9SxYB9n5acruXxcMQUKe27TpD0O1.',1,369,3),(74,'migueledesma','mircoaguilar02@gmail.com','2025-08-20 20:18:17','$2y$10$BEGQ4j2oTfff6k/WhvRkVe.OXBmwGKLMuxMJqD12biy8in/HWFmXK',1,370,1),(78,'prueba','prueba','2025-08-20 20:18:17','$2y$10$BEGQ4j2oTfff6k/WhvRkVe.OXBmwGKLMuxMJqD12biy8in/HWFmXK',1,1,1),(79,'lucasdominguez','lucas@gmail.com','2025-09-16 20:43:46','$2y$10$mnYo.CXZM7GA6x8tUtN/CeyRDLbrKPGpagUFAmXHYdeqeAv5dU7Ou',1,373,5),(80,'carlosayala','carlos@gmail.com','2025-09-22 14:04:40','$2y$10$GTR8UEy2mIvSKS9wFREbY.1WvdocRCyocYMeWyceyRJwx0iCsDrT.',1,374,14),(83,'maguilar','mircoaguilar@gmail.com','2025-11-05 22:22:35','$2y$10$yLy2lk349tdFT1/ajepIh.eLVmk8/bIjcey0FadEPxvc5yhktLrLu',1,377,1),(86,'poc75','lucia2@gmail.com','2025-11-12 17:41:46','$2y$10$A9MQw9kOHp1/BObq5zWpy.PDRURIz.FhLRjlGnJlCGN9/S6Gpshtm',0,35,1),(87,'poc7','lucia@gmail.com','2025-11-12 19:17:06','$2y$10$/iNRNPOFowoLXPgJMdfV.OXnFgSgGKDaiLc9m4G7.DGB0xm499e7.',1,35,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viajes`
--

LOCK TABLES `viajes` WRITE;
/*!40000 ALTER TABLE `viajes` DISABLE KEYS */;
INSERT INTO `viajes` VALUES (10,'2025-11-29',25,'08:00:00','11:00:00',1);
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

-- Dump completed on 2025-11-27 18:25:47
