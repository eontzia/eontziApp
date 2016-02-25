-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Servidor: 127.8.197.2:3306
-- Tiempo de generación: 24-02-2016 a las 23:45:14
-- Versión del servidor: 5.5.45
-- Versión de PHP: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `eontzia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Clientes`
--

CREATE TABLE IF NOT EXISTS `Clientes` (
  `Cliente_Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del Cliente',
  `Nombre` varchar(30) NOT NULL COMMENT 'Nombre cliente/empresa',
  `Comprado` char(1) NOT NULL DEFAULT '0',
  `Fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Comentarios` varchar(150) DEFAULT NULL,
  `NIF` char(9) DEFAULT NULL,
  `Fecha_modif` timestamp NULL DEFAULT NULL,
  `Nombre_contacto` varchar(30) NOT NULL,
  `Apellido_contacto` varchar(50) NOT NULL,
  `Correo_contacto` varchar(100) NOT NULL,
  `Tel_contacto` varchar(15) NOT NULL,
  `Dir_contacto` varchar(100) NOT NULL,
  `Localidad_contacto` varchar(100) NOT NULL,
  PRIMARY KEY (`Cliente_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Disparadores `Clientes`
--
DROP TRIGGER IF EXISTS `TR_CLI_UPDATE_FECHA`;
DELIMITER //
CREATE TRIGGER `TR_CLI_UPDATE_FECHA` BEFORE UPDATE ON `Clientes`
 FOR EACH ROW SET new.Fecha_modif=NOW()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Comunidades`
--

CREATE TABLE IF NOT EXISTS `Comunidades` (
  `Id_comunidad` tinyint(2) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(80) NOT NULL,
  `Pais_Id` smallint(3) NOT NULL,
  PRIMARY KEY (`Id_comunidad`),
  KEY `Id_pais` (`Pais_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dispositivos`
--

CREATE TABLE IF NOT EXISTS `Dispositivos` (
  `Dispositivo_Id` int(30) NOT NULL AUTO_INCREMENT,
  `Cliente_Id` int(10) NOT NULL,
  `Latitud` decimal(10,6) NOT NULL,
  `Longitud` decimal(10,6) NOT NULL,
  `Activo` tinyint(1) NOT NULL,
  `Barrio` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `Tipo` char(1) CHARACTER SET utf8 NOT NULL,
  `Direccion` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `Fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Fecha_modif` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Dispositivo_Id`),
  KEY `Cliente_Id` (`Cliente_Id`),
  KEY `IX_Tipo` (`Tipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Disparadores `Dispositivos`
--
DROP TRIGGER IF EXISTS `TR_DISP_UPDATE_FECHA`;
DELIMITER //
CREATE TRIGGER `TR_DISP_UPDATE_FECHA` BEFORE UPDATE ON `Dispositivos`
 FOR EACH ROW SET new.Fecha_modif=NOW()
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dis_datos`
--

CREATE TABLE IF NOT EXISTS `Dis_datos` (
  `Dis_datos_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Dispositivo_Id` int(11) NOT NULL,
  `Volumen` tinyint(4) NOT NULL,
  `Fuego` char(1) NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Bateria` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`Dis_datos_Id`),
  KEY `Dispositivo_Id` (`Dispositivo_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1767 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Municipios`
--

CREATE TABLE IF NOT EXISTS `Municipios` (
  `Municipio_Id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `Provincia_Id` tinyint(2) NOT NULL,
  `Cod_municipio` int(11) NOT NULL COMMENT 'Código de muncipio DENTRO de la provincia, campo no único',
  `DC` int(11) NOT NULL COMMENT 'Digito Control. El INE no revela cómo se calcula, secreto nuclear.',
  `Nombre` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`Municipio_Id`),
  KEY `Id_provincia` (`Provincia_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8117 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Paises`
--

CREATE TABLE IF NOT EXISTS `Paises` (
  `Pais_Id` smallint(3) NOT NULL AUTO_INCREMENT,
  `Iso` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `Nombre` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`Pais_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Perfiles`
--

CREATE TABLE IF NOT EXISTS `Perfiles` (
  `Perfil_Id` char(1) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Comentario` varchar(150) NOT NULL,
  PRIMARY KEY (`Perfil_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Provincias`
--

CREATE TABLE IF NOT EXISTS `Provincias` (
  `Id_provincia` tinyint(2) NOT NULL,
  `Id_comunidad` tinyint(2) NOT NULL,
  `Provincia` varchar(30) NOT NULL,
  PRIMARY KEY (`Id_provincia`),
  KEY `Id_comunidad` (`Id_comunidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipos`
--

CREATE TABLE IF NOT EXISTS `Tipos` (
  `Tipo_Id` char(1) NOT NULL COMMENT 'ID tipo de container',
  `Tipo` varchar(40) NOT NULL,
  PRIMARY KEY (`Tipo_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Trabajadores`
--

CREATE TABLE IF NOT EXISTS `Trabajadores` (
  `Trabajador_Id` smallint(6) NOT NULL,
  `Cliente_Id` int(10) NOT NULL,
  `Nombre` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Apellido` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Telefono` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `Email` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `User_key` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Activo` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `Fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Perfil_Id` char(1) CHARACTER SET utf8 DEFAULT '1',
  `Profile_imageURL` varchar(900) NOT NULL DEFAULT '../images/Profile/no-profile.gif',
  PRIMARY KEY (`Trabajador_Id`),
  KEY `Cliente_Id` (`Cliente_Id`),
  KEY `Perfil_Id` (`Perfil_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Disparadores `Trabajadores`
--
DROP TRIGGER IF EXISTS `TR_NuevoIdTrabajador`;
DELIMITER //
CREATE TRIGGER `TR_NuevoIdTrabajador` BEFORE INSERT ON `Trabajadores`
 FOR EACH ROW BEGIN

	DECLARE Tid integer;
	SELECT MAX(Trabajador_Id)+1 INTO Tid FROM Trabajadores;
	
	SET new.Trabajador_Id=Tid;

END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `TR_NuevoUsuario`;
DELIMITER //
CREATE TRIGGER `TR_NuevoUsuario` AFTER INSERT ON `Trabajadores`
 FOR EACH ROW BEGIN
	DECLARE nomUsu char(20);
	DECLARE nom char(1);
	DECLARE ape char(30);
	DECLARE id integer;
	set nom=LEFT(new.Nombre,1), ape=new.Apellido,id=new.Trabajador_Id;
	SET nomUsu=LOWER(CONCAT(nom,ape,id));

	INSERT INTO Usuarios(Nombre_usuario,Password,Trabajador_Id)
	VALUES(nomUsu,MD5('changeme'),new.Trabajador_Id);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `Usuario_Id` smallint(6) NOT NULL,
  `Nombre_usuario` varchar(50) NOT NULL COMMENT 'Utilizado para Login',
  `Password` varchar(255) NOT NULL,
  `Trabajador_Id` smallint(6) NOT NULL,
  PRIMARY KEY (`Usuario_Id`),
  UNIQUE KEY `Nombre_usuario` (`Nombre_usuario`),
  KEY `Id_trabajador` (`Trabajador_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `Usuarios`
--
DROP TRIGGER IF EXISTS `TR_nuevoIdUsuario`;
DELIMITER //
CREATE TRIGGER `TR_nuevoIdUsuario` BEFORE INSERT ON `Usuarios`
 FOR EACH ROW BEGIN

	DECLARE id integer;
	select MAX(Usuario_Id)+1 INTO id FROM Usuarios;
	
	SET new.Usuario_Id=id;

END
//
DELIMITER ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Comunidades`
--
ALTER TABLE `Comunidades`
  ADD CONSTRAINT `FK_PAIS_COMUNIDADES` FOREIGN KEY (`Pais_Id`) REFERENCES `Paises` (`Pais_Id`);

--
-- Filtros para la tabla `Dispositivos`
--
ALTER TABLE `Dispositivos`
  ADD CONSTRAINT `FK_CLIENTES_DISPOSITIVOS` FOREIGN KEY (`Cliente_Id`) REFERENCES `Clientes` (`Cliente_Id`),
  ADD CONSTRAINT `FK_TIPOS_DISPOSITIVOS` FOREIGN KEY (`Tipo`) REFERENCES `Tipos` (`Tipo_Id`);

--
-- Filtros para la tabla `Dis_datos`
--
ALTER TABLE `Dis_datos`
  ADD CONSTRAINT `Dis_datos_ibfk_1` FOREIGN KEY (`Dispositivo_Id`) REFERENCES `Dispositivos` (`Dispositivo_Id`);

--
-- Filtros para la tabla `Municipios`
--
ALTER TABLE `Municipios`
  ADD CONSTRAINT `FK_PROVINCIA_MUNICIPIOS` FOREIGN KEY (`Provincia_Id`) REFERENCES `Provincias` (`Id_provincia`);

--
-- Filtros para la tabla `Provincias`
--
ALTER TABLE `Provincias`
  ADD CONSTRAINT `FK_COMUNIDAD_PROVINCIA` FOREIGN KEY (`Id_comunidad`) REFERENCES `Comunidades` (`Id_comunidad`);

--
-- Filtros para la tabla `Trabajadores`
--
ALTER TABLE `Trabajadores`
  ADD CONSTRAINT `FK_CLIENTES_TRABAJADORES` FOREIGN KEY (`Cliente_Id`) REFERENCES `Clientes` (`Cliente_Id`),
  ADD CONSTRAINT `FK_PERFILES_TRABAJADORES` FOREIGN KEY (`Perfil_Id`) REFERENCES `Perfiles` (`Perfil_Id`);

--
-- Filtros para la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `FK_TRABAJADORES_USUARIOS` FOREIGN KEY (`Trabajador_Id`) REFERENCES `Trabajadores` (`Trabajador_Id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`adminKjCE67d`@`127.8.197.2` EVENT `e_timezone` ON SCHEDULE EVERY 1 MINUTE STARTS '2016-01-13 02:55:38' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Comprueba la zona horaria para poner +1' DO BEGIN
        DECLARE var CHAR(10);
		SELECT @@global.time_zone INTO var;
		IF strcmp(var,"SYSTEM")=0 THEN
			SET GLOBAL time_zone='+01:00';
		END IF;
      END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
