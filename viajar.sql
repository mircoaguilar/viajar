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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito`
--

LOCK TABLES `carrito` WRITE;
/*!40000 ALTER TABLE `carrito` DISABLE KEYS */;
INSERT INTO `carrito` VALUES (13,74,'2025-10-28 02:09:41',1);
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
  `cantidad` int DEFAULT '1',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `carrito_items_ibfk_1` (`rela_carrito`),
  CONSTRAINT `carrito_items_ibfk_1` FOREIGN KEY (`rela_carrito`) REFERENCES `carrito` (`id_carrito`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrito_items`
--

LOCK TABLES `carrito_items` WRITE;
/*!40000 ALTER TABLE `carrito_items` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `carrito_items` VALUES (75,13,'hotel',16,1,'2025-11-04','2025-11-06',150000.00,300000.00),(77,13,'tour',6,1,'2025-10-25',NULL,15000.00,15000.00),(83,13,'transporte',3,2,'2025-12-20',NULL,15000.00,30000.00);
=======
INSERT INTO `carrito_items` VALUES (54,13,'hotel',16,1,'2025-10-30','2025-10-31',150000.00,150000.00);
>>>>>>> origin/master
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudades`
--

LOCK TABLES `ciudades` WRITE;
/*!40000 ALTER TABLE `ciudades` DISABLE KEYS */;
INSERT INTO `ciudades` VALUES (1,'Formosa capital',1,1),(3,'Clorinda',1,1),(4,'Pirané',1,1),(5,'Ingeniero Juárez',1,1),(6,'El Espinillo',1,1),(7,'Comandante Fontana',1,1),(8,'Laguna Yema',1,1),(9,'San Francisco de Laishí',1,1),(10,'Misión Tacaaglé',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
INSERT INTO `contacto` VALUES (2,'3704687824',366,1),(4,'3705786843',367,1),(6,'3705000777',368,1),(8,'3705287463',369,1),(9,'3705786845',370,1),(10,'3705786845',371,1),(11,'3705786845',372,1),(12,'3704876934',373,1),(13,'3704859273',374,1),(14,'3704687834',375,1),(15,'3704000000',376,1);
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
<<<<<<< HEAD
=======
-- Table structure for table `detalle_pasajeros`
--

DROP TABLE IF EXISTS `detalle_pasajeros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pasajeros` (
  `id_detalle_pasajeros` int NOT NULL AUTO_INCREMENT,
  `detalle_pasajeros_asiento` int DEFAULT NULL,
  `detalle_pasajeros_estado` varchar(50) DEFAULT NULL,
  `rela_reservas` int NOT NULL,
  PRIMARY KEY (`id_detalle_pasajeros`),
  KEY `fk_detalle_pasajeros_reservas1_idx` (`rela_reservas`),
  CONSTRAINT `fk_detalle_pasajeros_reservas1` FOREIGN KEY (`rela_reservas`) REFERENCES `reservas` (`id_reservas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pasajeros`
--

LOCK TABLES `detalle_pasajeros` WRITE;
/*!40000 ALTER TABLE `detalle_pasajeros` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pasajeros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pasajeros_has_pasajeros`
--

DROP TABLE IF EXISTS `detalle_pasajeros_has_pasajeros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pasajeros_has_pasajeros` (
  `detalle_pasajeros_id_detalle_pasajeros` int NOT NULL,
  `pasajeros_id_pasajeros` int NOT NULL,
  PRIMARY KEY (`detalle_pasajeros_id_detalle_pasajeros`,`pasajeros_id_pasajeros`),
  KEY `fk_detalle_pasajeros_has_pasajeros_pasajeros1_idx` (`pasajeros_id_pasajeros`),
  KEY `fk_detalle_pasajeros_has_pasajeros_detalle_pasajeros1_idx` (`detalle_pasajeros_id_detalle_pasajeros`),
  CONSTRAINT `fk_detalle_pasajeros_has_pasajeros_detalle_pasajeros1` FOREIGN KEY (`detalle_pasajeros_id_detalle_pasajeros`) REFERENCES `detalle_pasajeros` (`id_detalle_pasajeros`),
  CONSTRAINT `fk_detalle_pasajeros_has_pasajeros_pasajeros1` FOREIGN KEY (`pasajeros_id_pasajeros`) REFERENCES `pasajeros` (`id_pasajeros`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pasajeros_has_pasajeros`
--

LOCK TABLES `detalle_pasajeros_has_pasajeros` WRITE;
/*!40000 ALTER TABLE `detalle_pasajeros_has_pasajeros` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pasajeros_has_pasajeros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pasajeros_has_viajes`
--

DROP TABLE IF EXISTS `detalle_pasajeros_has_viajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pasajeros_has_viajes` (
  `detalle_pasajeros_id_detalle_pasajeros` int NOT NULL,
  `viajes_id_viajes` int NOT NULL,
  PRIMARY KEY (`detalle_pasajeros_id_detalle_pasajeros`,`viajes_id_viajes`),
  KEY `fk_detalle_pasajeros_has_viajes_viajes1_idx` (`viajes_id_viajes`),
  KEY `fk_detalle_pasajeros_has_viajes_detalle_pasajeros1_idx` (`detalle_pasajeros_id_detalle_pasajeros`),
  CONSTRAINT `fk_detalle_pasajeros_has_viajes_detalle_pasajeros1` FOREIGN KEY (`detalle_pasajeros_id_detalle_pasajeros`) REFERENCES `detalle_pasajeros` (`id_detalle_pasajeros`),
  CONSTRAINT `fk_detalle_pasajeros_has_viajes_viajes1` FOREIGN KEY (`viajes_id_viajes`) REFERENCES `viajes` (`id_viajes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pasajeros_has_viajes`
--

LOCK TABLES `detalle_pasajeros_has_viajes` WRITE;
/*!40000 ALTER TABLE `detalle_pasajeros_has_viajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_pasajeros_has_viajes` ENABLE KEYS */;
UNLOCK TABLES;

--
>>>>>>> origin/master
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
  PRIMARY KEY (`id_detalle_hotel`),
  KEY `fk_hotel_detalle_reserva` (`rela_detalle_reserva`),
  KEY `fk_detalle_hotel_habitacion` (`rela_habitacion`),
  CONSTRAINT `fk_detalle_hotel_habitacion` FOREIGN KEY (`rela_habitacion`) REFERENCES `hotel_habitaciones` (`id_hotel_habitacion`),
  CONSTRAINT `fk_hotel_detalle_reserva` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_hotel`
--

LOCK TABLES `detalle_reserva_hotel` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_hotel` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `detalle_reserva_hotel` VALUES (88,190,16,'2025-11-04','2025-11-06',2);
=======
INSERT INTO `detalle_reserva_hotel` VALUES (64,122,16,'2025-10-24','2025-10-25',1),(65,124,16,'2025-10-25','2025-10-26',1),(66,125,16,'2025-10-25','2025-10-26',1),(67,126,16,'2025-10-25','2025-10-26',1),(68,128,16,'2025-10-25','2025-10-26',1),(69,130,16,'2025-10-25','2025-10-26',1),(70,132,16,'2025-10-25','2025-10-26',1),(71,134,16,'2025-10-25','2025-10-26',1),(72,136,16,'2025-10-30','2025-10-31',1);
>>>>>>> origin/master
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
  PRIMARY KEY (`id_detalle_tour`),
  KEY `fk_tour_detalle_reserva` (`rela_detalle_reserva`),
  KEY `fk_tour_detalle` (`rela_tour`),
  CONSTRAINT `fk_tour_detalle` FOREIGN KEY (`rela_tour`) REFERENCES `tours` (`id_tour`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tour_detalle_reserva` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_tour`
--

LOCK TABLES `detalle_reserva_tour` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_tour` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `detalle_reserva_tour` VALUES (53,191,6,'2025-10-25');
=======
INSERT INTO `detalle_reserva_tour` VALUES (32,123,6,'2025-10-26'),(33,127,6,'2025-10-28'),(34,129,6,'2025-10-28'),(35,131,6,'2025-10-28'),(36,133,6,'2025-10-28'),(37,135,6,'2025-10-28');
>>>>>>> origin/master
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
<<<<<<< HEAD
  `piso` int NOT NULL,
  `numero_asiento` int NOT NULL,
  `fila` int DEFAULT NULL,
  `columna` int DEFAULT NULL,
  `fecha_servicio` date DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_transporte`),
  KEY `rela_detalle_reserva` (`rela_detalle_reserva`),
  KEY `id_viaje` (`id_viaje`),
  CONSTRAINT `detalle_reserva_transporte_ibfk_1` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`),
  CONSTRAINT `detalle_reserva_transporte_ibfk_2` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id_viajes`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
=======
  `asiento_desde` int DEFAULT NULL,
  `asiento_hasta` int DEFAULT NULL,
  PRIMARY KEY (`id_detalle_transporte`),
  KEY `fk_transporte_detalle_reserva` (`rela_detalle_reserva`),
  KEY `fk_transporte_viaje` (`id_viaje`),
  CONSTRAINT `fk_transporte_detalle_reserva` FOREIGN KEY (`rela_detalle_reserva`) REFERENCES `detalle_reservas` (`id_detalle_reserva`),
  CONSTRAINT `fk_transporte_viaje` FOREIGN KEY (`id_viaje`) REFERENCES `viajes` (`id_viajes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva_transporte`
--

LOCK TABLES `detalle_reserva_transporte` WRITE;
/*!40000 ALTER TABLE `detalle_reserva_transporte` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `detalle_reserva_transporte` VALUES (3,192,3,1,11,3,3,'2025-12-20',15000.00),(4,192,3,1,12,3,4,'2025-12-20',15000.00);
=======
>>>>>>> origin/master
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reservas`
--

LOCK TABLES `detalle_reservas` WRITE;
/*!40000 ALTER TABLE `detalle_reservas` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `detalle_reservas` VALUES (190,123,'hotel',1,150000.00,300000.00),(191,123,'tour',1,15000.00,15000.00),(192,123,'transporte',2,15000.00,30000.00);
=======
INSERT INTO `detalle_reservas` VALUES (122,80,'hotel',1,150000.00,150000.00),(123,80,'tour',2,15000.00,30000.00),(124,81,'hotel',1,150000.00,150000.00),(125,82,'hotel',1,150000.00,150000.00),(126,83,'hotel',1,150000.00,150000.00),(127,83,'tour',2,15000.00,30000.00),(128,84,'hotel',1,150000.00,150000.00),(129,84,'tour',2,15000.00,30000.00),(130,85,'hotel',1,150000.00,150000.00),(131,85,'tour',2,15000.00,30000.00),(132,86,'hotel',1,150000.00,150000.00),(133,86,'tour',2,15000.00,30000.00),(134,87,'hotel',1,150000.00,150000.00),(135,87,'tour',2,15000.00,30000.00),(136,88,'hotel',1,150000.00,150000.00);
>>>>>>> origin/master
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
INSERT INTO `domicilio` VALUES (5,'Avenida 25 de mayo 0000',366,1),(6,'Puchini 0000',367,1),(7,'prueba',368,1),(8,'Mi casa',369,1),(9,'Barrio san Martin',370,1),(10,'Barrio san Martin',371,1),(11,'Barrio san Martin',372,1),(12,'Avenida 10 de mayo 1111',373,NULL),(13,'Av italia 0000',374,NULL),(14,'Av italia 0000',375,NULL),(15,'Barrio san Martin 2',376,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados_reserva`
--

LOCK TABLES `estados_reserva` WRITE;
/*!40000 ALTER TABLE `estados_reserva` DISABLE KEYS */;
INSERT INTO `estados_reserva` VALUES (1,'Pendiente',1),(2,'Confirmada',1),(3,'Cancelada',1),(4,'En curso',1),(5,'Finalizada',1),(10,'Pruebaa',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel`
--

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;
INSERT INTO `hotel` VALUES (2,'Hotel Internacional de Turismo','2025-08-05 19:31:13','internacional1.jpg',1,1,1,1,'aprobado',NULL,'2025-10-29 10:14:20',66),(58,'Howard Johnson Formosa','2025-09-25 14:24:15','68d57abfceb67_howard1.jpg',1,1,3,1,'aprobado',NULL,'2025-10-29 10:14:20',66),(59,'Hotel regina','2025-10-21 17:43:17','68f7f06565afa_190136777.jpg',1,1,3,1,'aprobado',NULL,'2025-10-29 10:47:43',66);
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones`
--

LOCK TABLES `hotel_habitaciones` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones` DISABLE KEYS */;
INSERT INTO `hotel_habitaciones` VALUES (15,58,1,2,30000.00,'Habitación luminosa y bien distribuida, ideal para quienes buscan un espacio cómodo y funcional. Está equipada con una cama matrimonial, según el , mesa de luz con velador, y placard amplio para guardar pertenencias de forma ordenada. Espacio sencillo pero bien cuidado, pensado para ofrecer una estadía confortable.',1,'2025-09-25 17:26:22','[\"assets/images/hab_68d57b3e4211c_ostrovok-445092-243d61-859914.jpg\",\"assets/images/hab_68d57b3e422ca_ostrovok-445092-367310-933479.jpg\",\"assets/images/425150400.jpg\"]'),(16,58,4,4,150000.00,'Elegante y confortable suite equipada con mobiliario de alta calidad, ideal para una estadía placentera. Cuenta con cama king size, área de estar, baño privado, climatización, TV por cable y Wi-Fi. Perfecta para quienes buscan comodidad y privacidad en un ambiente distinguido.',1,'2025-09-25 17:44:08','[\"assets/images/hab_68d57f68ad0c2_46586885.jpg\",\"assets/images/hab_68d57f68ad26e_532056808.jpg\",\"assets/images/544661146.jpg\"]'),(17,59,1,2,50000.00,'Una habitación simple, un espacio pequeño y acogedor, con lo esencial para descansar.',1,'2025-10-21 21:17:40','[\"assets/images/hab_68f7f8749a95b_Habitacion-Sencilla_P1-1200x600.jpg\",\"assets/images/hab_68f7f8749aafd_Habitacion-Sencilla_P2-1200x600.jpg\",\"assets/images/hab_68f7f8749acb6_Habitacion-Sencilla_P3-1200x600.jpg\",\"assets/images/hab_68f7f8749ae45_Habitacion-Sencilla_P4-1200x600.jpg\"]');
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_habitaciones_stock`
--

LOCK TABLES `hotel_habitaciones_stock` WRITE;
/*!40000 ALTER TABLE `hotel_habitaciones_stock` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `hotel_habitaciones_stock` VALUES (64,16,'2025-11-04',3,1),(65,16,'2025-11-05',3,1),(66,16,'2025-11-06',3,1),(67,16,'2025-11-07',3,1),(68,16,'2025-11-08',3,1),(69,16,'2025-11-09',3,1),(70,16,'2025-11-10',3,1),(71,16,'2025-11-11',3,1),(72,16,'2025-11-12',3,1),(73,16,'2025-11-13',3,1),(74,16,'2025-11-14',3,1),(75,16,'2025-11-15',3,1),(76,16,'2025-11-16',3,1),(77,16,'2025-11-17',3,1),(78,16,'2025-11-18',3,1),(79,16,'2025-11-19',3,1),(80,16,'2025-11-20',3,1),(81,16,'2025-11-21',3,1),(82,16,'2025-11-22',3,1),(83,16,'2025-11-23',3,1),(84,16,'2025-11-24',3,1),(85,16,'2025-11-25',3,1),(86,16,'2025-11-26',3,1),(87,16,'2025-11-27',3,1),(88,16,'2025-11-28',3,1),(89,16,'2025-11-29',3,1),(90,16,'2025-11-30',3,1),(91,15,'2025-11-04',3,1),(92,15,'2025-11-05',3,1),(93,15,'2025-11-06',3,1),(94,15,'2025-11-07',3,1),(95,15,'2025-11-08',3,1),(96,15,'2025-11-09',3,1),(97,15,'2025-11-10',3,1),(98,15,'2025-11-11',3,1),(99,15,'2025-11-12',3,1),(100,15,'2025-11-13',3,1),(101,15,'2025-11-14',3,1),(102,15,'2025-11-15',3,1),(103,15,'2025-11-16',3,1),(104,15,'2025-11-17',3,1),(105,15,'2025-11-18',3,1),(106,15,'2025-11-19',3,1),(107,15,'2025-11-20',3,1),(108,15,'2025-11-21',3,1),(109,15,'2025-11-22',3,1),(110,15,'2025-11-23',3,1),(111,15,'2025-11-24',3,1),(112,15,'2025-11-25',3,1),(113,15,'2025-11-26',3,1),(114,15,'2025-11-27',3,1),(115,15,'2025-11-28',3,1),(116,15,'2025-11-29',3,1),(117,15,'2025-11-30',3,1);
=======
INSERT INTO `hotel_habitaciones_stock` VALUES (48,15,'2025-10-24',3,1),(49,15,'2025-10-25',3,1),(50,15,'2025-10-26',3,1),(51,15,'2025-10-27',3,1),(52,15,'2025-10-28',3,1),(53,15,'2025-10-29',3,1),(54,15,'2025-10-30',3,1),(55,15,'2025-10-31',3,1),(56,16,'2025-10-24',3,1),(57,16,'2025-10-25',3,1),(58,16,'2025-10-26',3,1),(59,16,'2025-10-27',3,1),(60,16,'2025-10-28',3,1),(61,16,'2025-10-29',3,1),(62,16,'2025-10-30',3,1),(63,16,'2025-10-31',3,1);
>>>>>>> origin/master
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hoteles_info`
--

LOCK TABLES `hoteles_info` WRITE;
/*!40000 ALTER TABLE `hoteles_info` DISABLE KEYS */;
INSERT INTO `hoteles_info` VALUES (2,2,'San Martín 1001, P3600 Formosa','Hotel Internacional de Turismo, ubicado estratégicamente para brindar comodidad y excelente atención a turistas y visitantes.','Wi-Fi, desayuno buffet, estacionamiento, servicio a la habitación, aire acondicionado','Cancelación gratuita hasta 72 horas antes de la fecha de llegada.','No se permiten fiestas ni eventos, se debe respetar el horario de silencio después de las 22 hs.',NULL),(39,58,'Av. Dr. Luis Gutnisky 3754','El Hotel Howard Johnson Formosa ofrece confort y excelente ubicación en la ciudad, ideal para turistas y viajeros de negocios.','Wi-Fi gratis, pileta, gimnasio, desayuno incluido, estacionamiento','Cancelación sin costo hasta 48 horas antes de la llegada.','No se permiten mascotas. Prohibido fumar en habitaciones.','[\"68d57abfd4528_ostrovok-445092-0c1a5a-825552.jpg\",\"560131983.jpg\",\"68d57abfd46e6_ostrovok-445092-4e791c-079585.jpg\",\"425150020.jpg\",\"139266106.jpg\",\"560131994.jpg\",\"68d57abfda71c_ostrovok-445092-f26b12-029944.jpg\"]'),(40,59,'San Martín 535, P3600 Formosa','Es un alojamiento de categorización media, ideal para quienes buscan comodidad y accesibilidad en el corazón de la ciudad.','Wi-Fi gratuito en todo el hotel, Desayuno buffet incluido, Restaurante con una variada oferta gastronómica regional e internacional, Sala de reuniones y espacios para eventos, Estacionamiento privado para huéspedes.','Cancelación gratuita hasta 24 horas antes de la fecha de check-in.','Los huéspedes deben tener al menos 18 años para realizar el check-in sin la presencia de un adulto responsable, No se permiten mascotas en el hotel, excepto en casos especiales con autorización previa, Prohibido fumar dentro de las habitaciones y en áreas cerradas. El hotel dispone de espacios habilitados para fumadores, El hotel se reserva el derecho de solicitar la salida de cualquier huésped cuyo comportamiento sea considerado inapropiado o que cause molestias a otros huéspedes.','[\"68f7f06568cd6_415730679.jpg\",\"68f7f06568e94_415730882.jpg\",\"68f7f06568fea_82275192.jpg\",\"68f7f0656913d_82275212.jpg\"]');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monedas`
--

LOCK TABLES `monedas` WRITE;
/*!40000 ALTER TABLE `monedas` DISABLE KEYS */;
INSERT INTO `monedas` VALUES (1,'Euro','€',1.0000,1),(2,'Dolar','U$S',1.0000,1),(3,'Peso Argentino','$',1.0000,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivos_cancelacion`
--

LOCK TABLES `motivos_cancelacion` WRITE;
/*!40000 ALTER TABLE `motivos_cancelacion` DISABLE KEYS */;
INSERT INTO `motivos_cancelacion` VALUES (1,'Problemas de salud',1),(2,'Clima adverso',1),(3,'Cambio de fechas',1),(4,'Problemas de transporte',1),(5,'Otros',1),(6,'Pruebaz',0);
/*!40000 ALTER TABLE `motivos_cancelacion` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (47,'Pago aprobado #59','Tu pago por $345000 fue aprobado y la reserva #79 ha sido confirmada.','pago',74,1,'{\"reserva\":\"79\",\"pago\":\"59\",\"tipo_pago\":\"mercadopago\"}','2025-10-24 19:56:31'),(48,'Pago aprobado #60','Tu pago por $180000 fue aprobado y la reserva #80 ha sido confirmada.','pago',74,1,'{\"reserva\":\"80\",\"pago\":\"60\",\"tipo_pago\":\"mercadopago\"}','2025-10-24 20:29:14'),(49,'Pago aprobado #65','Tu pago por $180000 fue aprobado y la reserva #87 ha sido confirmada.','pago',74,1,'{\"reserva\":\"87\",\"pago\":\"65\",\"tipo_pago\":\"mercadopago\"}','2025-10-27 18:01:38');
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `pago` VALUES (78,'2025-11-04',345000,'pendiente',123,8,NULL,3);
=======
INSERT INTO `pago` VALUES (60,'2025-10-24',180000,'aprobado',80,8,'131203243302',3),(61,'2025-10-25',150000,'pendiente',81,8,NULL,3),(62,'2025-10-25',150000,'pendiente',81,8,NULL,3),(63,'2025-10-25',150000,'pendiente',81,8,NULL,3),(64,'2025-10-25',150000,'pendiente',82,8,NULL,3),(65,'2025-10-27',180000,'aprobado',87,8,'130918844773',3),(66,'2025-10-27',150000,'pendiente',88,8,NULL,3);
>>>>>>> origin/master
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
  `pasajeros_tipo` varchar(50) DEFAULT NULL,
  `rela_personas` int NOT NULL,
  PRIMARY KEY (`id_pasajeros`),
  KEY `fk_pasajeros_personas1_idx` (`rela_personas`),
  CONSTRAINT `fk_pasajeros_personas1` FOREIGN KEY (`rela_personas`) REFERENCES `personas` (`id_personas`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasajeros`
--

LOCK TABLES `pasajeros` WRITE;
/*!40000 ALTER TABLE `pasajeros` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (27,72,'9221ac9afb6f08f5f10986aa3d11fc7c63c153d4514c13b21cca15fa981841ce','2025-09-12 17:41:54','2025-09-12 19:41:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles`
--

LOCK TABLES `perfiles` WRITE;
/*!40000 ALTER TABLE `perfiles` DISABLE KEYS */;
INSERT INTO `perfiles` VALUES (1,'Cliente',1),(2,'Administrador',1),(3,'Administrador de hospedaje',1),(5,'Encargado de transporte',1),(13,'Encargado general',1),(14,'Guia',1),(18,'Prueba2',0),(19,'Prueba',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=377 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (1,'Juan','Perez','2004-07-07',1,'10000000'),(32,'Mirco','Aguilar','2004-06-06',1,'45817260'),(33,'Miguel','De la Rosa','2004-08-03',1,'1000001'),(34,'Juan','Pérez','1990-05-14',1,'1000002'),(35,'Ana','Gómezzz','1985-10-20',1,'1000003'),(36,'Carlos','López','1992-08-08',1,'1000004'),(37,'Lucia','Martínez','2000-01-01',1,'1000005'),(38,'Pedro','Ramírez','1998-03-15',1,'1000006'),(39,'María','Díaz','1996-09-12',1,'1000007'),(40,'Martín','Fernández','1987-04-22',1,'1000008'),(41,'Sofía','Alonso','1994-07-09',1,'1000009'),(42,'Nicolás','Silva','1991-02-18',1,'1000010'),(43,'Julieta','Molina','1999-11-30',1,'1000011'),(44,'Diego','Torres','1993-06-21',1,'1000012'),(45,'Valentina','Arias','1990-10-03',1,'1000013'),(46,'Agustín','Cruz','1989-12-25',1,'1000014'),(47,'Camila','Castro','1997-01-17',1,'1000015'),(48,'Bruno','Suárez','1995-08-14',1,'1000016'),(366,'Jose','Lopez','2005-07-01',1,'45800200'),(367,'miguel','perez','0204-07-06',1,'44890250'),(368,'Lucas','Prueba','2004-07-07',1,'45900200'),(369,'Marcos','Gimenez','2000-02-10',1,'40235867'),(370,'Miguel','Ledesma','2004-08-06',1,'45768250'),(371,'Miguel','Mirco Aguilar','2025-08-06',1,'45918720'),(372,'Miguel','De la rosa','2004-08-01',1,'45817260'),(373,'lucas','dominguez','2003-09-05',1,'45817260'),(374,'carlos','ayala','2000-09-02',1,'42346782'),(375,'prueba','prueba','2004-06-06',1,'45817250'),(376,'prueba2','prueba2','2025-10-09',1,'45872012');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
INSERT INTO `provincias` VALUES (1,'Formosa',0),(2,'Chaco',0),(3,'Chaco ',0),(4,'Chaco',1);
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas`
--

LOCK TABLES `reservas` WRITE;
/*!40000 ALTER TABLE `reservas` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `reservas` VALUES (123,'2025-11-04 20:06:17',345000.00,'pendiente',74,1);
=======
INSERT INTO `reservas` VALUES (80,'2025-10-24 20:28:51',180000.00,'confirmada',74,1),(81,'2025-10-25 20:38:42',150000.00,'pendiente',74,1),(82,'2025-10-25 20:41:56',150000.00,'pendiente',74,1),(83,'2025-10-27 17:42:43',180000.00,'pendiente',74,1),(84,'2025-10-27 17:47:13',180000.00,'pendiente',74,1),(85,'2025-10-27 17:47:47',180000.00,'pendiente',74,1),(86,'2025-10-27 17:48:21',180000.00,'pendiente',74,1),(87,'2025-10-27 17:51:43',180000.00,'confirmada',74,1),(88,'2025-10-27 23:09:44',150000.00,'pendiente',74,1),(89,'2025-11-20 23:10:44',200000.00,'pendiente',74,1),(90,'2025-12-20 23:10:44',200000.00,'pendiente',74,1),(91,'2025-01-20 23:10:44',200000.00,'pendiente',74,1),(92,'2025-02-20 23:10:44',200000.00,'pendiente',74,1),(93,'2025-03-20 23:10:44',200000.00,'pendiente',74,1),(94,'2025-04-20 23:10:44',200000.00,'pendiente',74,1),(95,'2025-05-20 23:10:44',200000.00,'pendiente',74,1),(96,'2025-06-20 23:10:44',200000.00,'pendiente',74,1),(97,'2025-07-20 23:10:44',200000.00,'pendiente',74,1),(98,'2025-08-20 23:10:44',200000.00,'pendiente',74,1),(99,'2025-09-20 23:10:44',200000.00,'pendiente',74,1);
>>>>>>> origin/master
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_tour`
--

LOCK TABLES `stock_tour` WRITE;
/*!40000 ALTER TABLE `stock_tour` DISABLE KEYS */;
INSERT INTO `stock_tour` VALUES (13,6,'2025-10-25',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(14,6,'2025-10-26',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(15,6,'2025-10-27',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(16,6,'2025-10-28',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(17,6,'2025-10-29',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(18,6,'2025-10-30',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05'),(19,6,'2025-10-31',10,0,1,'2025-10-13 22:37:05','2025-10-13 22:37:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temporadas`
--

LOCK TABLES `temporadas` WRITE;
/*!40000 ALTER TABLE `temporadas` DISABLE KEYS */;
INSERT INTO `temporadas` VALUES (1,'Pruebaa','2025-08-01','2025-08-31',0),(2,'Enero','2025-01-01','2025-01-31',0),(3,'Agosto','2025-08-01','2025-08-31',0),(4,'Febrero','2025-02-01','2025-02-28',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_contacto`
--

LOCK TABLES `tipo_contacto` WRITE;
/*!40000 ALTER TABLE `tipo_contacto` DISABLE KEYS */;
INSERT INTO `tipo_contacto` VALUES (1,'Telefono movil',1),(2,'Email alternativo',1),(6,'Pruebaa',0),(7,'Pruebaa',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_proveedores`
--

LOCK TABLES `tipo_proveedores` WRITE;
/*!40000 ALTER TABLE `tipo_proveedores` DISABLE KEYS */;
INSERT INTO `tipo_proveedores` VALUES (1,'Hospedaje','Hoteles, cabañas, departamentos, hostales, etc.',1),(2,'Transporte','Servicios de transporte de personas, como empresas de colectivos',1),(3,'Guía Turístico','Servicios de guías turísticos para recorridos, visitas guiadas, actividades y tours en distintas zonas turísticas, brindando información sobre la cultura, historia y atracciones locales',1),(4,'Prueba','Prueba',0);
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_transporte`
--

LOCK TABLES `tipo_transporte` WRITE;
/*!40000 ALTER TABLE `tipo_transporte` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `tipo_transporte` VALUES (1,'Cama',1),(2,'Semi Cama',1),(3,'Vip',1),(6,'Común',1);
=======
INSERT INTO `tipo_transporte` VALUES (1,'Cama',1),(2,'Semi Cama',1),(3,'Vip',1);
>>>>>>> origin/master
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_documento`
--

LOCK TABLES `tipos_documento` WRITE;
/*!40000 ALTER TABLE `tipos_documento` DISABLE KEYS */;
INSERT INTO `tipos_documento` VALUES (1,'Dni',1),(2,'cuitt',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_habitacion`
--

LOCK TABLES `tipos_habitacion` WRITE;
/*!40000 ALTER TABLE `tipos_habitacion` DISABLE KEYS */;
INSERT INTO `tipos_habitacion` VALUES (1,'Habitación Simple','Habitación para una persona',1,1),(3,'Habitación Triple','Ideal para tres personas',3,1),(4,'Suite','Habitación de lujo con sala de estar',2,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (6,'Paseo en el museo','Un paseo breve por el museo de nuestra provincia','01:00:00',15000.00,'10:00:00','El mastil de la costanera','museo_formosa.jpg',5,1,'2025-09-22 23:36:53','Belgrano 836, P3600ENN Formosa','aprobado',NULL,'2025-10-29 10:47:56',66);
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
=======
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte`
--

LOCK TABLES `transporte` WRITE;
/*!40000 ALTER TABLE `transporte` DISABLE KEYS */;
INSERT INTO `transporte` VALUES (3,'ABC123',40,2,'Colectivo Formosa','Servicio de colectivo interurbano en Formosa','colectivo_formosa.jpg',4,'2025-08-05 19:31:13',1,'aprobado',NULL,'2025-10-29 10:47:54',66);
/*!40000 ALTER TABLE `transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
<<<<<<< HEAD
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_pisos`
--

LOCK TABLES `transporte_pisos` WRITE;
/*!40000 ALTER TABLE `transporte_pisos` DISABLE KEYS */;
/*!40000 ALTER TABLE `transporte_pisos` ENABLE KEYS */;
UNLOCK TABLES;

--
=======
>>>>>>> origin/master
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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
=======
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transporte_rutas`
--

LOCK TABLES `transporte_rutas` WRITE;
/*!40000 ALTER TABLE `transporte_rutas` DISABLE KEYS */;
INSERT INTO `transporte_rutas` VALUES (10,'Formosa - Clorinda','Formosa - Herradura - Clorinda',1,3,'02:00:00','Viaje premium a Clorinda',15000.00,3,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (66,'jperez','juan@gmail.com','2025-06-27 17:00:20','$2y$10$EP9hGHC3LErzPlsgN7J70e85ER.Hdjx.txhmABH6XsIHNefkfL3OW',1,1,2),(72,'marcosgimenez','9zmirko123@gmail.com','2025-07-08 10:59:17','$2y$10$oRZ4l80wAB8A/lzXtKwbq.fa9SxYB9n5acruXxcMQUKe27TpD0O1.',1,369,3),(74,'migueledesma','mircoaguilar02@gmail.com','2025-08-20 20:18:17','$2y$10$KYZO87Ch3hEKrGFvPOsB9O9n/NVcMexlThQiFUmpktAavK/6V5vp2',1,370,1),(78,'prueba','prueba','2025-08-20 20:18:17','prueba',1,1,1),(79,'lucasdominguez','lucas@gmail.com','2025-09-16 20:43:46','$2y$10$mnYo.CXZM7GA6x8tUtN/CeyRDLbrKPGpagUFAmXHYdeqeAv5dU7Ou',1,373,5),(80,'carlosayala','carlos@gmail.com','2025-09-22 14:04:40','$2y$10$GTR8UEy2mIvSKS9wFREbY.1WvdocRCyocYMeWyceyRJwx0iCsDrT.',1,374,14);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
<<<<<<< HEAD
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viaje_asientos`
--

LOCK TABLES `viaje_asientos` WRITE;
/*!40000 ALTER TABLE `viaje_asientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `viaje_asientos` ENABLE KEYS */;
UNLOCK TABLES;

--
=======
>>>>>>> origin/master
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
<<<<<<< HEAD
=======
  `asientos_disponibles` int DEFAULT NULL,
>>>>>>> origin/master
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_viajes`),
  KEY `fk_viajes_transporte_rutas` (`rela_transporte_rutas`),
  CONSTRAINT `fk_viajes_transporte_rutas` FOREIGN KEY (`rela_transporte_rutas`) REFERENCES `transporte_rutas` (`id_ruta`)
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
=======
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
>>>>>>> origin/master
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viajes`
--

LOCK TABLES `viajes` WRITE;
/*!40000 ALTER TABLE `viajes` DISABLE KEYS */;
<<<<<<< HEAD
INSERT INTO `viajes` VALUES (3,'2025-12-20',10,'08:00:00','10:00:00',1);
=======
INSERT INTO `viajes` VALUES (3,'2025-12-20',10,'08:00:00','10:00:00',50,1);
>>>>>>> origin/master
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

<<<<<<< HEAD
-- Dump completed on 2025-11-05 17:54:55
=======
-- Dump completed on 2025-10-30 18:23:53
>>>>>>> origin/master
