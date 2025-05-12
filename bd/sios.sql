-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2025 a las 21:53:33
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
-- Base de datos: `sios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id_detalle` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id_detalle`, `id_factura`, `descripcion`, `cantidad`, `valor_unitario`, `subtotal`) VALUES
(1, 1, 'Consulta general', 1, 45000.00, 45000.00),
(2, 1, 'Laboratorio básico', 1, 65000.00, 65000.00),
(3, 2, 'Urgencias básicas', 1, 120000.00, 120000.00),
(4, 2, 'Hospitalización general', 1, 180000.00, 180000.00),
(5, 3, 'Odontología general', 1, 55000.00, 55000.00),
(6, 4, 'Ecografía abdominal', 1, 150000.00, 150000.00),
(7, 5, 'Consulta general', 1, 45000.00, 45000.00),
(8, 6, 'Rayos X tórax', 1, 75000.00, 75000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `epicrisis`
--

CREATE TABLE `epicrisis` (
  `id_epicrisis` int(11) NOT NULL,
  `id_prestacion` int(11) NOT NULL,
  `resumen_clinico` text NOT NULL,
  `recomendaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `epicrisis`
--

INSERT INTO `epicrisis` (`id_epicrisis`, `id_prestacion`, `resumen_clinico`, `recomendaciones`) VALUES
(1, 1, 'Paciente con síntomas de infección respiratoria. Fiebre de 38°C, congestión nasal y tos seca.', 'Reposo, hidratación, acetaminofén 500mg cada 8 horas por 3 días.'),
(2, 3, 'Paciente con fractura de fémur derecho por caída. Se realizó reducción e inmovilización.', 'Control con ortopedia en una semana. No apoyar pierna derecha. Analgésicos según dolor.'),
(3, 5, 'Paciente con caries en primer molar inferior derecho. Se realizó obturación.', 'Higiene dental estricta. Control en 6 meses.'),
(4, 7, 'Paciente con crisis hipertensiva. TA 180/110. Se estabilizó con medicación endovenosa.', 'Control médico semanal. Tomar medicación antihipertensiva diariamente.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eps`
--

CREATE TABLE `eps` (
  `id_eps` int(11) NOT NULL,
  `nombre_eps` varchar(100) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `direccion_eps` varchar(100) NOT NULL,
  `telefono_eps` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eps`
--

INSERT INTO `eps` (`id_eps`, `nombre_eps`, `nit`, `direccion_eps`, `telefono_eps`) VALUES
(1, 'CAJACOPI', '890.901.363-6', 'Carrera 43A # 1-50, Medellín', '6044486115'),
(2, 'SALUD_TOTAL', '900.198.124-4', 'Calle 100 # 19-54, Bogotá', '6013078066'),
(3, 'COOSALUD', '860.001.151-0', 'Carrera 11 # 93-45, Bogotá', '6016429292'),
(4, 'MUTUALSER', '890.903.937-7', 'Carrera 13 # 27-47, Cali', '6028823232'),
(21, 'COOSALUD', '490.901.363-6', 'Carrera 43A # 1-50, Medellín', '6044486115'),
(22, 'MUTUALSER', '200.198.124-4', 'Calle 100 # 19-54, Bogotá', '6013078066'),
(23, 'COOSALUD', '660.001.151-0', 'Carrera 11 # 93-45, Bogotá', '6016429292'),
(24, 'SALUD_TOTAL', '190.903.937-7', 'Carrera 13 # 27-47, Cali', '6028823232');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `estado` enum('Pagada','Pendiente','Anulada') NOT NULL,
  `numero_factura` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_factura`, `id_paciente`, `fecha_emision`, `total`, `estado`, `numero_factura`) VALUES
(1, 1, '2023-05-10', 110000.00, 'Pagada', 'FA39136'),
(2, 2, '2023-05-12', 300000.00, 'Pagada', 'FA11251'),
(3, 3, '2023-05-12', 55000.00, 'Pendiente', 'FA18849'),
(4, 5, '2023-05-14', 150000.00, 'Pagada', 'FA50491'),
(5, 7, '2023-05-16', 45000.00, 'Pendiente', 'FA95911'),
(6, 8, '2023-05-17', 75000.00, 'Pagada', 'FA48080');

--
-- Disparadores `facturas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_factura` BEFORE INSERT ON `facturas` FOR EACH ROW BEGIN
    DECLARE nuevo_numero VARCHAR(20);
    REPEAT
        SET nuevo_numero = CONCAT('FA', LPAD(FLOOR(10000 + RAND() * 90000), 5, '0'));
    UNTIL NOT EXISTS (SELECT 1 FROM facturas WHERE numero_factura = nuevo_numero) END REPEAT;
    SET NEW.numero_factura = nuevo_numero;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `tipo_doc` enum('CC','TI','CE','RC','PA') NOT NULL,
  `num_doc` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `sexo` enum('M','F','O') NOT NULL,
  `fecha_nac` date NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `id_eps` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `tipo_doc`, `num_doc`, `nombre_completo`, `sexo`, `fecha_nac`, `direccion`, `telefono`, `id_eps`) VALUES
(1, 'CC', '1023456789', 'María González Pérez', 'F', '1985-06-12', 'Calle 123 #45-67, Bogotá', '3201234567', 1),
(2, 'CC', '987654321', 'Carlos Andrés López', 'M', '1992-03-20', 'Cra 10 #12-34, Medellín', '3109876543', 2),
(3, 'CC', '5432167890', 'Ana Milena Rodríguez', 'F', '1978-11-15', 'Av. 6 #23-45, Cali', '3151234567', 3),
(4, 'CC', '1098765432', 'Jorge Enrique Sánchez', 'M', '1989-07-25', 'Carrera 7 #45-89, Bogotá', '3177654321', 4),
(5, 'CC', '7654321098', 'Luisa Fernanda Martínez', 'F', '1995-02-18', 'Calle 34 #12-67, Barranquilla', '3001239876', 1),
(6, 'CC', '1234567890', 'Pedro Antonio Gómez', 'M', '1982-09-30', 'Cra 50 #34-12, Medellín', '3186543210', 2),
(7, 'TI', '1012345678', 'Sofía Camila Díaz', 'F', '2005-04-22', 'Av. Boyacá #23-45, Bogotá', '3198765432', 3),
(8, 'CE', 'AB1234567', 'David Alejandro Torres', 'M', '1990-12-05', 'Calle 72 #11-34, Cartagena', '3156789012', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion_servicio`
--

CREATE TABLE `prestacion_servicio` (
  `id_prestacion` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `fecha_servicio` date NOT NULL,
  `profesional` varchar(100) NOT NULL,
  `diagnostico` varchar(100) NOT NULL,
  `id_factura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion_servicio`
--

INSERT INTO `prestacion_servicio` (`id_prestacion`, `id_paciente`, `id_servicio`, `fecha_servicio`, `profesional`, `diagnostico`, `id_factura`) VALUES
(1, 1, 1, '2023-05-10', 'Dra. Laura Mendoza', 'J06.9 - Infección respiratoria aguda', 1),
(2, 1, 7, '2023-05-10', 'Dr. Carlos Ruiz', 'Z01.7 - Examen de laboratorio rutinario', 1),
(3, 2, 3, '2023-05-11', 'Dr. Andrés Gómez', 'S72.0 - Fractura de fémur', 2),
(4, 2, 5, '2023-05-11', 'Dr. Andrés Gómez', 'S72.0 - Fractura de fémur', 2),
(5, 3, 6, '2023-05-12', 'Dra. Sandra Pérez', 'K02.9 - Caries dental', 3),
(6, 4, 2, '2023-05-13', 'Dr. Ricardo Torres', 'E11.9 - Diabetes mellitus tipo 2', NULL),
(7, 5, 4, '2023-05-14', 'Dra. Carolina Jiménez', 'I10 - Hipertensión esencial', 4),
(8, 6, 10, '2023-05-15', 'Lic. Juan Carlos Ramírez', 'M54.5 - Lumbalgia', NULL),
(9, 7, 1, '2023-05-16', 'Dra. Laura Mendoza', 'J00 - Rinofaringitis aguda', 5),
(10, 8, 8, '2023-05-17', 'Dr. Felipe Castro', 'R07.9 - Dolor torácico no especificado', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_medicamentos`
--

CREATE TABLE `registro_medicamentos` (
  `id_registro` int(11) NOT NULL,
  `id_prestacion` int(11) NOT NULL,
  `medicamento` varchar(100) NOT NULL,
  `dosis` varchar(50) NOT NULL,
  `frecuencia` varchar(50) NOT NULL,
  `via` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_medicamentos`
--

INSERT INTO `registro_medicamentos` (`id_registro`, `id_prestacion`, `medicamento`, `dosis`, `frecuencia`, `via`) VALUES
(1, 1, 'Acetaminofén 500mg', '1 tableta', 'Cada 8 horas', 'Oral'),
(2, 3, 'Ketorolaco 30mg', '1 ampolla', 'Cada 12 horas', 'Intramuscular'),
(3, 3, 'Tramadol 50mg', '1 tableta', 'Cada 8 horas', 'Oral'),
(4, 5, 'Amoxicilina 500mg', '1 tableta', 'Cada 12 horas', 'Oral'),
(5, 7, 'Losartán 50mg', '1 tableta', 'Cada 24 horas', 'Oral');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `valor_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre_servicio`, `descripcion`, `valor_unitario`) VALUES
(1, 'Consulta general', 'Consulta médica de medicina general', 45000.00),
(2, 'Consulta especializada', 'Consulta con médico especialista', 85000.00),
(3, 'Urgencias básicas', 'Atención en servicio de urgencias nivel I', 120000.00),
(4, 'Urgencias complejas', 'Atención en servicio de urgencias nivel II o III', 250000.00),
(5, 'Hospitalización general', 'Día de hospitalización en sala general', 180000.00),
(6, 'Odontología general', 'Consulta odontológica básica', 55000.00),
(7, 'Laboratorio básico', 'Perfil 20 elementos', 65000.00),
(8, 'Rayos X tórax', 'Radiografía de tórax PA y lateral', 75000.00),
(9, 'Ecografía abdominal', 'Ecografía completa de abdomen', 150000.00),
(10, 'Terapia física', 'Sesión de terapia física', 60000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `epicrisis`
--
ALTER TABLE `epicrisis`
  ADD PRIMARY KEY (`id_epicrisis`),
  ADD KEY `id_prestacion` (`id_prestacion`);

--
-- Indices de la tabla `eps`
--
ALTER TABLE `eps`
  ADD PRIMARY KEY (`id_eps`),
  ADD UNIQUE KEY `nit` (`nit`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD UNIQUE KEY `numero_factura_2` (`numero_factura`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `num_doc` (`num_doc`),
  ADD KEY `id_eps` (`id_eps`);

--
-- Indices de la tabla `prestacion_servicio`
--
ALTER TABLE `prestacion_servicio`
  ADD PRIMARY KEY (`id_prestacion`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `registro_medicamentos`
--
ALTER TABLE `registro_medicamentos`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_prestacion` (`id_prestacion`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `epicrisis`
--
ALTER TABLE `epicrisis`
  MODIFY `id_epicrisis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `eps`
--
ALTER TABLE `eps`
  MODIFY `id_eps` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `prestacion_servicio`
--
ALTER TABLE `prestacion_servicio`
  MODIFY `id_prestacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registro_medicamentos`
--
ALTER TABLE `registro_medicamentos`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`);

--
-- Filtros para la tabla `epicrisis`
--
ALTER TABLE `epicrisis`
  ADD CONSTRAINT `epicrisis_ibfk_1` FOREIGN KEY (`id_prestacion`) REFERENCES `prestacion_servicio` (`id_prestacion`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_eps`) REFERENCES `eps` (`id_eps`);

--
-- Filtros para la tabla `prestacion_servicio`
--
ALTER TABLE `prestacion_servicio`
  ADD CONSTRAINT `prestacion_servicio_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `prestacion_servicio_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`),
  ADD CONSTRAINT `prestacion_servicio_ibfk_3` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`);

--
-- Filtros para la tabla `registro_medicamentos`
--
ALTER TABLE `registro_medicamentos`
  ADD CONSTRAINT `registro_medicamentos_ibfk_1` FOREIGN KEY (`id_prestacion`) REFERENCES `prestacion_servicio` (`id_prestacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
