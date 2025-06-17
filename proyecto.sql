-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para gestion_proyectos
CREATE DATABASE IF NOT EXISTS `gestion_proyectos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `gestion_proyectos`;

-- Volcando estructura para tabla gestion_proyectos.asignacion_requerimientos
CREATE TABLE IF NOT EXISTS `asignacion_requerimientos` (
  `id_asignacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_requerimiento` int(11) NOT NULL,
  `id_desarrollador` int(11) NOT NULL,
  `id_fase` int(11) NOT NULL,
  `fecha_asignacion` datetime NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_asignacion`),
  UNIQUE KEY `uq_asignacion_activa` (`id_requerimiento`,`activo`),
  KEY `fk_asignacion_desarrollador` (`id_desarrollador`),
  KEY `fk_asignacion_fase` (`id_fase`),
  CONSTRAINT `fk_asignacion_desarrollador` FOREIGN KEY (`id_desarrollador`) REFERENCES `desarrollador` (`Id_Desarrollador`),
  CONSTRAINT `fk_asignacion_fase` FOREIGN KEY (`id_fase`) REFERENCES `fase` (`Id_fase`),
  CONSTRAINT `fk_asignacion_requerimiento` FOREIGN KEY (`id_requerimiento`) REFERENCES `requerimiento` (`Id_requerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.cliente
CREATE TABLE IF NOT EXISTS `cliente` (
  `Id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre_cliente` varchar(255) NOT NULL,
  `Correo_cliente` varchar(100) NOT NULL,
  `Contraseña_cliente` varchar(255) NOT NULL,
  `Ciudad_cliente` varchar(50) NOT NULL,
  `Empresa_cliente` varchar(100) NOT NULL,
  `Telefono_cliente` varchar(20) DEFAULT NULL,
  `Fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`Id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.desarrollador
CREATE TABLE IF NOT EXISTS `desarrollador` (
  `Id_Desarrollador` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Especialidad` varchar(50) NOT NULL,
  `Experiencia` int(11) DEFAULT 1,
  `Fecha_incorporacion` date NOT NULL,
  `Id_fase` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Desarrollador`),
  KEY `desarrollador_ibfk_1` (`Id_fase`),
  CONSTRAINT `desarrollador_ibfk_1` FOREIGN KEY (`Id_fase`) REFERENCES `fase` (`Id_fase`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.fase
CREATE TABLE IF NOT EXISTS `fase` (
  `Id_fase` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Orden` int(11) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  PRIMARY KEY (`Id_fase`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.reporte
CREATE TABLE IF NOT EXISTS `reporte` (
  `Id_reporte` int(11) NOT NULL AUTO_INCREMENT,
  `Id_requerimiento` int(11) NOT NULL,
  `Cambio_realizado` text NOT NULL,
  `Fecha_cambio` date DEFAULT NULL,
  `Id_desarrollador` int(11) NOT NULL,
  PRIMARY KEY (`Id_reporte`),
  KEY `Id_requerimiento` (`Id_requerimiento`),
  KEY `Id_desarrollador` (`Id_desarrollador`),
  CONSTRAINT `reporte_ibfk_1` FOREIGN KEY (`Id_requerimiento`) REFERENCES `requerimiento` (`Id_requerimiento`),
  CONSTRAINT `reporte_ibfk_2` FOREIGN KEY (`Id_desarrollador`) REFERENCES `desarrollador` (`Id_Desarrollador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.requerimiento
CREATE TABLE IF NOT EXISTS `requerimiento` (
  `Id_requerimiento` int(11) NOT NULL AUTO_INCREMENT,
  `Id_sistema` int(11) NOT NULL,
  `Descripcion` text NOT NULL,
  `Prioridad` varchar(20) DEFAULT NULL,
  `Fecha_creacion` date DEFAULT NULL,
  `Fecha_modificacion` date DEFAULT NULL,
  `Id_fase` int(11) NOT NULL,
  `Estado` varchar(20) NOT NULL DEFAULT 'Pendiente',
  PRIMARY KEY (`Id_requerimiento`),
  KEY `Id_sistema` (`Id_sistema`),
  KEY `Id_fase` (`Id_fase`),
  CONSTRAINT `requerimiento_ibfk_1` FOREIGN KEY (`Id_sistema`) REFERENCES `sistema` (`Id_sistema`),
  CONSTRAINT `requerimiento_ibfk_3` FOREIGN KEY (`Id_fase`) REFERENCES `fase` (`Id_fase`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla gestion_proyectos.sistema
CREATE TABLE IF NOT EXISTS `sistema` (
  `Id_sistema` int(11) NOT NULL AUTO_INCREMENT,
  `Id_cliente` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Tipo_sistema` varchar(50) NOT NULL,
  `Version_sistema` varchar(20) DEFAULT '1.0',
  `Estado_sistema` varchar(30) DEFAULT 'Pendiente',
  `Fecha_inicio` date DEFAULT NULL,
  PRIMARY KEY (`Id_sistema`),
  KEY `Id_cliente` (`Id_cliente`),
  CONSTRAINT `sistema_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
