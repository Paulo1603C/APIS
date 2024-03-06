-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-02-2024 a las 22:44:24
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `uta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_estudiantes`
--

CREATE TABLE `archivos_estudiantes` (
  `IdArch` int(11) NOT NULL,
  `RutaArch` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `Observacion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `IdEstPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `archivos_estudiantes`
--

INSERT INTO `archivos_estudiantes` (`IdArch`, `RutaArch`, `Observacion`, `IdEstPer`) VALUES
(23, 'CARRERAS/AUTOMATIZACIÓN Y ROBÓTICA/ALEJANDRO BETANCUR/CARTA/estudiantes.xlsx', 'archivo para importar datos', 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `IdCar` int(11) NOT NULL,
  `NomCar` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`IdCar`, `NomCar`) VALUES
(1, 'Ingeniería Industrial'),
(2, 'Software'),
(3, 'TI'),
(4, 'Telecomunicaciones'),
(5, 'Automatización y robótica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_secretarias`
--

CREATE TABLE `carreras_secretarias` (
  `CarSecId` int(11) NOT NULL,
  `IdCarPer` int(11) NOT NULL,
  `IdUserPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `carreras_secretarias`
--

INSERT INTO `carreras_secretarias` (`CarSecId`, `IdCarPer`, `IdUserPer`) VALUES
(6, 1, 4),
(7, 3, 4),
(8, 4, 4),
(9, 2, 4),
(10, 5, 4),
(31, 5, 29),
(32, 2, 29),
(40, 5, 35),
(42, 3, 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `IdEst` int(11) NOT NULL,
  `NomEst` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ApeEst` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `CedEst` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Fecha` date DEFAULT curdate(),
  `Nom_Modificador` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `IdCarPer` int(11) NOT NULL,
  `IdPlanPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`IdEst`, `NomEst`, `ApeEst`, `CedEst`, `Fecha`, `Nom_Modificador`, `IdCarPer`, `IdPlanPer`) VALUES
(38, 'Alejandro', 'Betancur', '18023', '2024-02-05', 'David Reyes', 5, 2),
(39, 'Cristina Elizabeth', 'Silva', '18024', '2024-02-05', 'David Reyes', 5, 2),
(67, 'Paulo Cesar', 'Martinez Altamirano', '1805808308', '2024-02-08', 'David Reyes', 1, 2),
(69, 'Cristina', 'Silva', '1850954270', '2024-02-08', 'David Reyes', 1, 2),
(70, 'Raul ', 'Perez', '1803325818', '2024-02-24', 'David Reyes', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_directorios`
--

CREATE TABLE `items_directorios` (
  `IdItemDir` int(11) NOT NULL,
  `IdPlanPer` int(11) NOT NULL,
  `IdSubDirPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `items_directorios`
--

INSERT INTO `items_directorios` (`IdItemDir`, `IdPlanPer`, `IdSubDirPer`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_plantillas`
--

CREATE TABLE `items_plantillas` (
  `IdItem` int(11) NOT NULL,
  `NomItem` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `items_plantillas`
--

INSERT INTO `items_plantillas` (`IdItem`, `NomItem`) VALUES
(1, 'CEDULA'),
(2, 'PRACTICAS'),
(3, 'INGLES'),
(4, 'CULTURA FÍSICA'),
(68, 'CALCULO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_subdirectorios`
--

CREATE TABLE `items_subdirectorios` (
  `IdItem` int(11) NOT NULL,
  `NomItem` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `items_subdirectorios`
--

INSERT INTO `items_subdirectorios` (`IdItem`, `NomItem`) VALUES
(1, 'Cedula'),
(2, 'Practicas'),
(3, 'Ingles'),
(4, 'CULTURA FÍSICA'),
(106, 'CARTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `IdPer` int(11) NOT NULL,
  `nomPer` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`IdPer`, `nomPer`) VALUES
(1, 'Lectura'),
(2, 'Eliminar'),
(3, 'Editar'),
(4, 'Crear');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantillas_directorios`
--

CREATE TABLE `plantillas_directorios` (
  `IdPlan` int(11) NOT NULL,
  `NomPlan` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `plantillas_directorios`
--

INSERT INTO `plantillas_directorios` (`IdPlan`, `NomPlan`) VALUES
(1, 'Estudiantes Nuevos'),
(2, 'Estudiantes Antiguos'),
(22, 'SD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `IdRol` int(11) NOT NULL,
  `NomRol` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`IdRol`, `NomRol`) VALUES
(1, 'Administrador'),
(2, 'Secretari@');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUser` int(11) NOT NULL,
  `NomUser` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `ApeUser` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Contraseña` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `IdRolPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUser`, `NomUser`, `ApeUser`, `Correo`, `Contraseña`, `IdRolPer`) VALUES
(4, 'David', 'Reyes', 'pauloalexis24@gmail.com', 'c34bdfa40d7926abe2212c9e798199292bb1fecd19ffbbcabf150dbe7f8c3887', 1),
(29, 'Masiett', 'Lopez', 'pmartinez4270@uta.edu.ec', 'e10c53b41d218dec1131f38241fc02218dc28f88f179490b2278182d6c269601', 2),
(35, 'Marlón', 'Zurrita', 'paulomartinez1999@gmail.com', 'adb6d8919d3b797c62f0da0449be1f2d5c04d07d23eb144b150ba5e54e3fba1e', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_permisos`
--

CREATE TABLE `usuarios_permisos` (
  `IdRelacion` int(11) NOT NULL,
  `IdUserPer` int(11) NOT NULL,
  `IdPerPer` int(11) NOT NULL,
  `IdItemSubPer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_permisos`
--

INSERT INTO `usuarios_permisos` (`IdRelacion`, `IdUserPer`, `IdPerPer`, `IdItemSubPer`) VALUES
(17, 4, 1, 1),
(18, 4, 2, 1),
(20, 4, 4, 1),
(21, 4, 2, 2),
(24, 4, 2, 3),
(65, 4, 3, 1),
(74, 35, 1, 1),
(75, 35, 2, 1),
(76, 4, 1, 106);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos_estudiantes`
--
ALTER TABLE `archivos_estudiantes`
  ADD PRIMARY KEY (`IdArch`),
  ADD KEY `IdEstPer` (`IdEstPer`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`IdCar`);

--
-- Indices de la tabla `carreras_secretarias`
--
ALTER TABLE `carreras_secretarias`
  ADD PRIMARY KEY (`CarSecId`),
  ADD KEY `IdCarPer` (`IdCarPer`),
  ADD KEY `IdUserPer` (`IdUserPer`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`IdEst`),
  ADD KEY `IdCarPer` (`IdCarPer`),
  ADD KEY `IdPlanPer` (`IdPlanPer`);

--
-- Indices de la tabla `items_directorios`
--
ALTER TABLE `items_directorios`
  ADD PRIMARY KEY (`IdItemDir`),
  ADD KEY `IdPlanPer` (`IdPlanPer`),
  ADD KEY `IdSubDirPer` (`IdSubDirPer`);

--
-- Indices de la tabla `items_plantillas`
--
ALTER TABLE `items_plantillas`
  ADD PRIMARY KEY (`IdItem`);

--
-- Indices de la tabla `items_subdirectorios`
--
ALTER TABLE `items_subdirectorios`
  ADD PRIMARY KEY (`IdItem`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`IdPer`);

--
-- Indices de la tabla `plantillas_directorios`
--
ALTER TABLE `plantillas_directorios`
  ADD PRIMARY KEY (`IdPlan`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdRol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUser`),
  ADD KEY `IdRolPer` (`IdRolPer`);

--
-- Indices de la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD PRIMARY KEY (`IdRelacion`),
  ADD KEY `IdUserPer` (`IdUserPer`),
  ADD KEY `IdPerPer` (`IdPerPer`),
  ADD KEY `IdItemSubPer` (`IdItemSubPer`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos_estudiantes`
--
ALTER TABLE `archivos_estudiantes`
  MODIFY `IdArch` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `IdCar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `carreras_secretarias`
--
ALTER TABLE `carreras_secretarias`
  MODIFY `CarSecId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `IdEst` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `items_directorios`
--
ALTER TABLE `items_directorios`
  MODIFY `IdItemDir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `items_plantillas`
--
ALTER TABLE `items_plantillas`
  MODIFY `IdItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `items_subdirectorios`
--
ALTER TABLE `items_subdirectorios`
  MODIFY `IdItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `IdPer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `plantillas_directorios`
--
ALTER TABLE `plantillas_directorios`
  MODIFY `IdPlan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `IdRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  MODIFY `IdRelacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos_estudiantes`
--
ALTER TABLE `archivos_estudiantes`
  ADD CONSTRAINT `archivos_estudiantes_ibfk_1` FOREIGN KEY (`IdEstPer`) REFERENCES `estudiantes` (`IdEst`);

--
-- Filtros para la tabla `carreras_secretarias`
--
ALTER TABLE `carreras_secretarias`
  ADD CONSTRAINT `carreras_secretarias_ibfk_1` FOREIGN KEY (`IdCarPer`) REFERENCES `carreras` (`IdCar`),
  ADD CONSTRAINT `carreras_secretarias_ibfk_2` FOREIGN KEY (`IdUserPer`) REFERENCES `usuarios` (`IdUser`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`IdCarPer`) REFERENCES `carreras` (`IdCar`),
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`IdPlanPer`) REFERENCES `plantillas_directorios` (`IdPlan`);

--
-- Filtros para la tabla `items_directorios`
--
ALTER TABLE `items_directorios`
  ADD CONSTRAINT `items_directorios_ibfk_1` FOREIGN KEY (`IdPlanPer`) REFERENCES `plantillas_directorios` (`IdPlan`),
  ADD CONSTRAINT `items_directorios_ibfk_2` FOREIGN KEY (`IdSubDirPer`) REFERENCES `items_plantillas` (`IdItem`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`IdRolPer`) REFERENCES `roles` (`IdRol`);

--
-- Filtros para la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD CONSTRAINT `usuarios_permisos_ibfk_1` FOREIGN KEY (`IdUserPer`) REFERENCES `usuarios` (`IdUser`),
  ADD CONSTRAINT `usuarios_permisos_ibfk_2` FOREIGN KEY (`IdPerPer`) REFERENCES `permisos` (`IdPer`),
  ADD CONSTRAINT `usuarios_permisos_ibfk_3` FOREIGN KEY (`IdItemSubPer`) REFERENCES `items_subdirectorios` (`IdItem`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
