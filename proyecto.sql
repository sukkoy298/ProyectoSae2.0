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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.asignacion_requerimientos: ~8 rows (aproximadamente)
INSERT INTO `asignacion_requerimientos` (`id_asignacion`, `id_requerimiento`, `id_desarrollador`, `id_fase`, `fecha_asignacion`, `activo`) VALUES
	(1, 1, 1, 1, '2025-06-17 14:53:16', 0),
	(2, 2, 1, 2, '2025-06-17 15:09:33', 0),
	(3, 4, 3, 2, '2025-06-17 15:24:31', 0),
	(4, 3, 1, 2, '2025-06-18 18:26:57', 0),
	(5, 5, 1, 2, '2025-06-18 18:34:16', 0),
	(6, 6, 1, 3, '2025-06-18 19:11:05', 0),
	(7, 7, 1, 1, '2025-06-18 19:46:12', 1),
	(8, 8, 1, 2, '2025-07-01 11:50:56', 1);

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
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`Id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.cliente: ~6 rows (aproximadamente)
INSERT INTO `cliente` (`Id_cliente`, `Nombre_cliente`, `Correo_cliente`, `Contraseña_cliente`, `Ciudad_cliente`, `Empresa_cliente`, `Telefono_cliente`, `Fecha_registro`, `activo`) VALUES
	(1, 'Angel gabriel', 'jhsgdhjw@g.com', '$2y$10$.q1IUAIntaoZE6HaA2YFmOsQF16O7OtNRcfeLE/o13KTI2BN.7Foy', 'barquisimeto', 'boom', '04145458046', '2025-06-17 16:20:30', 0),
	(2, 'valeris', 'vcshjd@ho.com', '$2y$10$bkb3zG375VdEuQsMedu7HumjKLGANDFvJla.IrGD1T2D9Du0Si3Je', 'barquisimeto', 'dsd', '04226667788', '2025-06-17 16:51:31', 0),
	(3, 'sarath', 'sara@gmail.com', '$2y$10$vW7eZsXnW1GQBT1ntqTXnuiB0nNaULn0IBq3kmD4qo9gUtc.yCALe', 'yaracuy', 'solartech', '04123334455', '2025-06-17 17:54:27', 0),
	(4, 'Orion', 'Orion123@gmail.com', '$2y$10$CnqWRiNnxb0Dvfq8ghvgLuPmURI8vK5FXwFpBh8Kv5OmKVN.4IIga', 'barquisimeto', 'orion company', '04226667788', '2025-07-01 16:02:45', 1),
	(5, 'iuji', 'jhsgdhjw@g.com', '$2y$10$j.Q5hvoCGcSwasSHEiU.4ejoka9jM5H7CiseUYY66A/iLOCk3bfPC', 'yaracuy', 'jaskjaksja', '2891209182', '2025-07-01 16:10:40', 1),
	(6, 'valeris', 'jhsgdhjw@g.com', '$2y$10$mPVvZyffKptRnVdd4NgnH.X36VAkvGI6FxBrkbLyt3fNv2l1gdix6', 'barquisimeto', 'josecompany', '04145458046', '2025-07-01 16:14:50', 1);

-- Volcando estructura para tabla gestion_proyectos.desarrollador
CREATE TABLE IF NOT EXISTS `desarrollador` (
  `Id_Desarrollador` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Especialidad` varchar(50) NOT NULL,
  `Experiencia` int(11) DEFAULT 1,
  `Fecha_incorporacion` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`Id_Desarrollador`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.desarrollador: ~6 rows (aproximadamente)
INSERT INTO `desarrollador` (`Id_Desarrollador`, `Nombre`, `Correo`, `Contraseña`, `Especialidad`, `Experiencia`, `Fecha_incorporacion`, `activo`) VALUES
	(1, 'carlos Augusto', 'carlos@gmail.com', '$2y$10$qXuudiTp.VPdyvi3iCqSFO11KhAinVjhasrhMK5v9P56t/KmAjuHe', 'front-end', 1, '2025-06-17', 1),
	(2, 'eichner lunar', 'eichner@gmail.com', '$2y$10$VH0h2fXN8RuYswvq5TYQpOd3gobT/AexT8Pb9AQiVC0k6vgOEXjWq', 'Back-end', 12, '2025-06-17', 0),
	(3, 'Rafa Alvarez', 'rafael@gmail.com', '$2y$10$9aaTzPj8mXPKSspiT2T7Ge/NFDaLPo36kW1Gc/uOvxp7lNYk5NVGK', 'Full-Stack', 25, '2025-06-17', 0),
	(4, 'Samantha', 'sam@gmail.com', '$2y$10$8RxI6mj9r4VnAgtypJDUSu3UJGPtWFh3gDJacvl6o2RNLnZuW9r.C', 'front-end', 12, '2025-06-19', 0),
	(5, 'alejandra', 'alejandra@gmail.com', '$2y$10$2EsLwweZ.2/Vi6x2SwOG2uo3modze66aU1vRZvkDL7dJpmG85zLZO', 'Full-Stack', 11, '2025-06-19', 0),
	(6, 'Carlosaug', 'carlosaug@gmail.com', '$2y$10$vGnMnAhNUzOGUrCP/l6C4.IsQnbgWXDZzq3cmGyPu0fKSv5iAt3.e', 'Full-Stack', 12, '2025-07-01', 1);

-- Volcando estructura para tabla gestion_proyectos.fase
CREATE TABLE IF NOT EXISTS `fase` (
  `Id_fase` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Orden` int(11) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  PRIMARY KEY (`Id_fase`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.fase: ~5 rows (aproximadamente)
INSERT INTO `fase` (`Id_fase`, `Nombre`, `Orden`, `Descripcion`) VALUES
	(1, 'Análisis', 1, 'Requerimiento en etapa de análisis'),
	(2, 'Desarrollo', 2, 'Requerimiento en implementación'),
	(3, 'Pruebas', 3, 'Requerimiento en etapa de testing'),
	(4, 'Aprobación', 4, 'Esperando aprobación del cliente'),
	(5, 'Finalizado', 5, 'Requerimiento completado');

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.reporte: ~14 rows (aproximadamente)
INSERT INTO `reporte` (`Id_reporte`, `Id_requerimiento`, `Cambio_realizado`, `Fecha_cambio`, `Id_desarrollador`) VALUES
	(1, 1, 'vista de los montos', '2025-06-17', 1),
	(2, 2, '[Cambio de fase] asignacion', '2025-06-17', 1),
	(3, 2, 'Terminado por el desarrollador', '2025-06-17', 1),
	(4, 4, '[Cambio de fase] empieza el desarrollo', '2025-06-17', 3),
	(5, 1, 'Terminado por el desarrollador', '2025-06-17', 1),
	(6, 3, '[Cambio de fase] se hizo la api de los pagos', '2025-06-19', 1),
	(7, 4, 'Terminado por el desarrollador', '2025-06-19', 3),
	(8, 5, 'desarrollo avanzado del foro donde se puso visible el foro', '2025-06-19', 1),
	(9, 6, '[Cambio de fase] jksdajkndsjlmnkdsklmdz', '2025-06-19', 1),
	(10, 3, 'Terminado por el desarrollador', '2025-06-19', 1),
	(11, 5, 'Terminado por el desarrollador', '2025-06-19', 1),
	(12, 6, 'Terminado por el desarrollador', '2025-06-19', 1),
	(13, 7, 'xjhxjhsJHxjhsZxhbxbh', '2025-06-19', 1),
	(14, 8, '[Cambio de fase] se arreglo la parte de los pagos', '2025-07-01', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.requerimiento: ~9 rows (aproximadamente)
INSERT INTO `requerimiento` (`Id_requerimiento`, `Id_sistema`, `Descripcion`, `Prioridad`, `Fecha_creacion`, `Fecha_modificacion`, `Id_fase`, `Estado`) VALUES
	(1, 1, 'hay que acomodor los montos multimoneda', 'Media', '2025-06-17', '2025-06-17', 1, 'Eliminado'),
	(2, 2, 'sahsjkhahsakjsajhsak', 'Baja', '2025-06-17', '2025-06-17', 2, 'Eliminado'),
	(3, 1, 'jdkashfjdjkashdas', 'Media', '2025-06-17', '2025-06-19', 2, 'Eliminado'),
	(4, 4, 'uiuyig ', 'Baja', '2025-06-17', '2025-06-17', 2, 'Eliminado'),
	(5, 2, 'necesito un foro para mis estudiantes', 'Alta', '2025-06-19', '2025-06-19', 2, 'Eliminado'),
	(6, 4, 'quero actualizarlo', 'Alta', '2025-06-19', '2025-06-19', 3, 'Eliminado'),
	(7, 1, 'uwshrfseuifsefuifhesuf', 'Alta', '2025-06-19', '2025-06-19', 1, 'Eliminado'),
	(8, 2, 'esta mala la parte de pagos', 'Alta', '2025-07-01', '2025-07-01', 2, 'En Proceso'),
	(9, 7, 'nesecito acomodar la vista de clientes en mi sistema', 'Alta', '2025-07-01', NULL, 1, 'Pendiente');

-- Volcando estructura para tabla gestion_proyectos.sistema
CREATE TABLE IF NOT EXISTS `sistema` (
  `Id_sistema` int(11) NOT NULL AUTO_INCREMENT,
  `Id_cliente` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Version_sistema` varchar(20) DEFAULT '1.0',
  `Fecha_inicio` date DEFAULT NULL,
  `id_tipo_sistema` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_sistema`),
  KEY `Id_cliente` (`Id_cliente`),
  CONSTRAINT `sistema_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.sistema: ~6 rows (aproximadamente)
INSERT INTO `sistema` (`Id_sistema`, `Id_cliente`, `Nombre`, `Version_sistema`, `Fecha_inicio`, `id_tipo_sistema`) VALUES
	(1, 2, 'Pagos iujo', '1.0', '2025-06-10', NULL),
	(2, 3, 'aula solar', '1.0', '2025-06-06', NULL),
	(4, 1, 'Aula vacia', '1.0', '2024-01-24', NULL),
	(5, 1, 'Aula virtual el santo', '1.1', '2025-06-11', NULL),
	(6, 1, 'valeris', '1.1', '2025-06-04', NULL),
	(7, 4, 'valeris', '1.1', '2025-07-10', 1);

-- Volcando estructura para tabla gestion_proyectos.tipo_sistema
CREATE TABLE IF NOT EXISTS `tipo_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla gestion_proyectos.tipo_sistema: ~1 rows (aproximadamente)
INSERT INTO `tipo_sistema` (`id`, `nombre`, `descripcion`, `activo`) VALUES
	(1, 'Sae pagos', 'sistema administrativo de pagos por taquilla', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
