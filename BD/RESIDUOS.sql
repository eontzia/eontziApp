-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-11-2015 a las 12:43:39
-- Versión del servidor: 5.5.44
-- Versión de PHP: 5.6.13-1+deb.sury.org~precise+3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `RESIDUOS`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Clientes`
--

CREATE TABLE IF NOT EXISTS `Clientes` (
  `Client_Id` int(20) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(30) NOT NULL,
  `Apelido` varchar(30) NOT NULL,
  `Direccion` varchar(30) NOT NULL,
  `Ciudad` varchar(20) NOT NULL,
  `Telefono` varchar(20) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Comprado` int(1) NOT NULL,
  `Fecha_crear` datetime NOT NULL,
  `otra` varchar(30) NOT NULL,
  PRIMARY KEY (`Client_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dis-datos`
--

CREATE TABLE IF NOT EXISTS `Dis-datos` (
  `Dis-datos_Id` int(11) NOT NULL AUTO_INCREMENT,
  `Dispositivo_Id` int(11) NOT NULL,
  `Volumen` int(20) NOT NULL,
  `Fuego` int(20) NOT NULL,
  `Fecha` datetime NOT NULL,
  `otro` varchar(50) NOT NULL,
  PRIMARY KEY (`Dis-datos_Id`),
  KEY `Dispositivo_Id` (`Dispositivo_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Dispositivos`
--

CREATE TABLE IF NOT EXISTS `Dispositivos` (
  `Dispositivo_Id` int(30) NOT NULL AUTO_INCREMENT,
  `Cliente_Id` int(30) NOT NULL,
  `Direccion` varchar(30) NOT NULL,
  `Ciudad` varchar(30) NOT NULL,
  `Latitude` varchar(20) NOT NULL,
  `Longitude` varchar(20) NOT NULL,
  `Activo` tinyint(1) NOT NULL,
  `Bario` varchar(20) NOT NULL,
  PRIMARY KEY (`Dispositivo_Id`),
  KEY `Cliente_Id` (`Cliente_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Trabajador`
--

CREATE TABLE IF NOT EXISTS `Trabajador` (
  `Trabajador_Id` int(30) NOT NULL AUTO_INCREMENT,
  `Cliente_Id` int(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Appelido` varchar(30) NOT NULL,
  `Telefono` int(20) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Key` varchar(50) NOT NULL,
  `Activo` tinyint(1) NOT NULL,
  `Fetcha_crear` datetime NOT NULL,
  PRIMARY KEY (`Trabajador_Id`),
  KEY `Cliente_Id` (`Cliente_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Dis-datos`
--
ALTER TABLE `Dis-datos`
  ADD CONSTRAINT `Dis-datos_ibfk_1` FOREIGN KEY (`Dispositivo_Id`) REFERENCES `Dispositivos` (`Dispositivo_Id`);

--
-- Filtros para la tabla `Dispositivos`
--
ALTER TABLE `Dispositivos`
  ADD CONSTRAINT `Dispositivos_ibfk_1` FOREIGN KEY (`Cliente_Id`) REFERENCES `Clientes` (`Client_Id`);

--
-- Filtros para la tabla `Trabajador`
--
ALTER TABLE `Trabajador`
  ADD CONSTRAINT `Trabajador_ibfk_1` FOREIGN KEY (`Cliente_Id`) REFERENCES `Clientes` (`Client_Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
