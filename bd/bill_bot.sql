-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 08:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bill_bot`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `id` int(100) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `url` varchar(500) NOT NULL,
  `sesion_activa` int(20) NOT NULL,
  `ultima_conexion` datetime DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`id`, `nombre`, `apellido`, `usuario`, `correo`, `contraseña`, `url`, `sesion_activa`, `ultima_conexion`, `rol`) VALUES
(12, 'Jimena', 'Alida Ropain', 'Jimenita', 'iratebuffalo436@gmail.com', 'paraque?123', '', 1, '2025-05-10 13:10:18', 'Administrador');

-- --------------------------------------------------------

--
-- Table structure for table `facturadores`
--

CREATE TABLE `facturadores` (
  `id` int(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contraseña` varchar(70) NOT NULL,
  `rol` varchar(40) NOT NULL,
  `imagen_perfil` varchar(500) DEFAULT NULL,
  `session_activa` tinyint(1) DEFAULT 0,
  `ultima_actividad` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `facturadores`
--

INSERT INTO `facturadores` (`id`, `nombre`, `usuario`, `correo`, `contraseña`, `rol`, `imagen_perfil`, `session_activa`, `ultima_actividad`) VALUES
(22, 'Elkin Miranda', 'elkinmb3', 'elkinmb3@gmail.com', '123', 'Facturador', NULL, 0, '2025-05-09 20:30:08'),
(23, 'Miguel Ramirez', 'Migue123', 'miramire97@gmail.com', '123', 'Facturador', NULL, 0, '2025-05-09 20:13:25'),
(24, 'Eider Arroyo', 'Eider651', 'avilaeider0805@gmail.com', '123', 'Facturador', NULL, 0, '2025-05-09 15:38:21');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens_usuarios2`
--

CREATE TABLE `password_reset_tokens_usuarios2` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens_usuarios2`
--

INSERT INTO `password_reset_tokens_usuarios2` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(3, 22, '32aff15c3367e28ab4e2d1f1774334b64b639db725865c873a33a246ea7fd66d', '2025-05-08 22:10:26', '2025-05-08 19:10:26'),
(5, 23, '4b7a18a1802a38438f6d7cbac77c596cc794748f4024448ed695e4b1bb9f1513', '2025-05-10 00:40:42', '2025-05-09 21:40:42'),
(6, 22, '537940092fc4f7f417b6e42896837051041654a55e6a3d8d6ecc12e71d7a24a2', '2025-05-10 00:41:25', '2025-05-09 21:41:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facturadores`
--
ALTER TABLE `facturadores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens_usuarios2`
--
ALTER TABLE `password_reset_tokens_usuarios2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `facturadores`
--
ALTER TABLE `facturadores`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `password_reset_tokens_usuarios2`
--
ALTER TABLE `password_reset_tokens_usuarios2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `administrador` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_reset_tokens_usuarios2`
--
ALTER TABLE `password_reset_tokens_usuarios2`
  ADD CONSTRAINT `password_reset_tokens_usuarios2_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `facturadores` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
