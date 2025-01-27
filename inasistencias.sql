- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-01-2025 a las 01:24:39
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
-- Base de datos: `inasistencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excepciones`
--

CREATE TABLE `excepciones` (
  `idExcepciones` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fechaInicio` datetime NOT NULL,
  `fechaFin` datetime NOT NULL,
  `usuarioId` int(11) DEFAULT NULL,
  `fichaId` int(11) DEFAULT NULL,
  `tipoExcepcion` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excusas`
--

CREATE TABLE `excusas` (
  `idExcusas` int(11) NOT NULL,
  `inasistencias_idInasistencias` int(11) NOT NULL,
  `usuario_idUsuarios` int(11) NOT NULL,
  `idInstructor` int(11) NOT NULL,
  `uriArchivo` varchar(500) NOT NULL,
  `observacion` text DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha`
--

CREATE TABLE `ficha` (
  `idFicha` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `numeroFicha` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ficha`
--

INSERT INTO `ficha` (`idFicha`, `nombre`, `numeroFicha`, `status`) VALUES
(1, 'Adso', 2827725, 1),
(2, 'Gestion Empresarial', 3045122, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `idHorario` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `horaInicio` time NOT NULL,
  `fechaFin` date NOT NULL,
  `horaFin` time NOT NULL,
  `usuarioId` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inasistencias`
--

CREATE TABLE `inasistencias` (
  `idInasistencias` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `codigoNovedad` int(11) DEFAULT NULL,
  `usuario_idUsuarios` int(11) NOT NULL,
  `idInstructor` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `idNotificaciones` int(11) NOT NULL,
  `tipoNovedad` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `mensaje` varchar(500) DEFAULT NULL,
  `usuarioId` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuarios` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `documento` bigint(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `genero` int(11) NOT NULL,
  `correo` varchar(300) NOT NULL,
  `codigo` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `firma` varchar(100) DEFAULT NULL,
  `rol` varchar(45) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuarios`, `nombre`, `apellido`, `documento`, `telefono`, `genero`, `correo`, `codigo`, `password`, `firma`, `rol`, `status`) VALUES
(1, 'pedro', 'gomez', 12345, '8444465', 1, 'pedro@gmail.com', '12345', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '', 'APRENDIZ', 1),
(2, 'melo', 'diaz', 1234, '330004', 1, 'emlo@dsa.com', '1234', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '', 'INSTRUCTOR', 1),
(3, 'miguel', 'Sepulveda', 123, '555445', 1, 'miguel@gmail.com', '123', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '', 'APRENDIZ', 1),
(4, 'jeanpool', 'contreras', 1114153114, '330255', 1, 'jean@gmail.com', '123456', 'e23abca10997f023d0ee45d659856e5f4096727870236644cf8a4c3a2a2b3c07', '', 'APRENDIZ', 1),
(13, 'Gabriel', 'Garabito', 1, '32655999', 1, 'web@master.com', '1', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', '', 'COORDINADOR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_has_ficha`
--

CREATE TABLE `usuario_has_ficha` (
  `usuario_idUsuarios` int(11) NOT NULL,
  `ficha_idFicha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario_has_ficha`
--

INSERT INTO `usuario_has_ficha` (`usuario_idUsuarios`, `ficha_idFicha`) VALUES
(1, 2),
(3, 2),
(4, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `excepciones`
--
ALTER TABLE `excepciones`
  ADD PRIMARY KEY (`idExcepciones`),
  ADD KEY `usuarioId` (`usuarioId`),
  ADD KEY `fk_excepciones_ficha1_idx` (`fichaId`);

--
-- Indices de la tabla `excusas`
--
ALTER TABLE `excusas`
  ADD PRIMARY KEY (`idExcusas`),
  ADD KEY `fk_Excusas_Inasistencias1_idx` (`inasistencias_idInasistencias`),
  ADD KEY `fk_excusas_usuario1_idx` (`usuario_idUsuarios`);

--
-- Indices de la tabla `ficha`
--
ALTER TABLE `ficha`
  ADD PRIMARY KEY (`idFicha`),
  ADD UNIQUE KEY `numeroFicha_UNIQUE` (`numeroFicha`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`idHorario`),
  ADD KEY `usuarioId` (`usuarioId`);

--
-- Indices de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD PRIMARY KEY (`idInasistencias`),
  ADD KEY `fk_Inasistencias_Usuario1_idx` (`usuario_idUsuarios`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`idNotificaciones`),
  ADD KEY `usuarioId` (`usuarioId`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuarios`),
  ADD UNIQUE KEY `Documento_UNIQUE` (`documento`),
  ADD UNIQUE KEY `Codigo_UNIQUE` (`codigo`);

--
-- Indices de la tabla `usuario_has_ficha`
--
ALTER TABLE `usuario_has_ficha`
  ADD PRIMARY KEY (`usuario_idUsuarios`,`ficha_idFicha`),
  ADD KEY `fk_Usuario_has_Ficha_Ficha1_idx` (`ficha_idFicha`),
  ADD KEY `fk_Usuario_has_Ficha_Usuario_idx` (`usuario_idUsuarios`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `excepciones`
--
ALTER TABLE `excepciones`
  MODIFY `idExcepciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `excusas`
--
ALTER TABLE `excusas`
  MODIFY `idExcusas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `ficha`
--
ALTER TABLE `ficha`
  MODIFY `idFicha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `idHorario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  MODIFY `idInasistencias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `idNotificaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `excepciones`
--
ALTER TABLE `excepciones`
  ADD CONSTRAINT `excepciones_ibfk_2` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_excepciones_ficha1` FOREIGN KEY (`fichaId`) REFERENCES `ficha` (`idFicha`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `excusas`
--
ALTER TABLE `excusas`
  ADD CONSTRAINT `fk_Excusas_Inasistencias1` FOREIGN KEY (`inasistencias_idInasistencias`) REFERENCES `inasistencias` (`idInasistencias`),
  ADD CONSTRAINT `fk_excusas_usuario1` FOREIGN KEY (`usuario_idUsuarios`) REFERENCES `usuario` (`idUsuarios`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inasistencias`
--
ALTER TABLE `inasistencias`
  ADD CONSTRAINT `fk_Inasistencias_Usuario1` FOREIGN KEY (`usuario_idUsuarios`) REFERENCES `usuario` (`idUsuarios`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_has_ficha`
--
ALTER TABLE `usuario_has_ficha`
  ADD CONSTRAINT `fk_Usuario_has_Ficha_Ficha1` FOREIGN KEY (`ficha_idFicha`) REFERENCES `ficha` (`idFicha`),
  ADD CONSTRAINT `fk_Usuario_has_Ficha_Usuario` FOREIGN KEY (`usuario_idUsuarios`) REFERENCES `usuario` (`idUsuarios`);
COMMIT;

--
--  Eventos 'registrar inasistencias excluyendo a los que cuentas con excepciones'
--

CREATE EVENT registrar_inasistencias
ON SCHEDULE EVERY 1 DAY 
STARTS '2025-01-25 05:40:00' 
DO
BEGIN
   
    INSERT INTO inasistencias (fecha, hora, codigoNovedad, usuario_idUsuarios, idInstructor, status)
    SELECT 
        CURDATE() AS fecha,               
        CURTIME() AS hora,                
        0 AS codigoNovedad,               
        u.idUsuarios AS usuario_idUsuarios,
        NULL AS idInstructor,             
        1 AS status                       
    FROM 
        usuario u
    LEFT JOIN excepciones e
        ON (
            (e.usuarioId = u.idUsuarios AND CURDATE() BETWEEN DATE(e.fechaInicio) AND DATE(e.fechaFin)) 
            OR 
            (e.fichaId IN (SELECT ficha_idFicha FROM usuario_has_ficha WHERE usuario_idUsuarios = u.idUsuarios) AND CURDATE() BETWEEN DATE(e.fechaInicio) AND DATE(e.fechaFin)) 
            OR
            (e.usuarioId IS NULL AND e.fichaId IS NULL AND CURDATE() BETWEEN DATE(e.fechaInicio) AND DATE(e.fechaFin))
        )
    WHERE 
        u.rol = 'APRENDIZ'                
        AND u.status = 1                  
        AND e.idExcepciones IS NULL;      
END;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
