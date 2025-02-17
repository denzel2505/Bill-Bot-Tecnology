-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2025 a las 13:50:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clinica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `numero_de_factura` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `fecha de vencimiento` date NOT NULL,
  `dias de vencimiento` int(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `EPS` varchar(100) NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `valor` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`numero_de_factura`, `fecha`, `fecha de vencimiento`, `dias de vencimiento`, `descripcion`, `EPS`, `servicio`, `valor`) VALUES
('FA12542', '2025-01-15', '2025-01-13', 3, 'FACTURA ATENCION 5', 'CAJACOPI', 'GENERAL', 50.6),
('FA253404', '2024-12-04', '2024-12-08', 4, 'FACTURA ATENCION 1', 'COOSALUD', 'URGENCIAS', 200.99),
('FA33333', '2024-12-13', '2024-12-24', 5, 'FACTURA ATENCION 2', 'MUTUALSER', 'GENERAL', 300.123),
('FA50062', '2024-12-04', '2024-12-07', 4, 'FACTURA ATENCION 3', 'SALUD TOTAL', 'ODONTOLOGIA', 12000),
('FA77688', '2025-01-01', '2025-01-02', 7, 'FACTURA ATENCION 4', 'NUEVA EPS', 'URGENCIAS', 860.5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`numero_de_factura`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
