-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2025 a las 19:19:15
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
-- Base de datos: `crm_ilerna`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tlf` varchar(20) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id_client`, `nom_complet`, `email`, `tlf`, `empresa`, `fecha_registro`, `usuario_responsable`) VALUES
(1, 'caca punxada', 'a@gmail.com', '624193175', 'fsfds', '2025-11-19 17:03:05', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oportunitats`
--

CREATE TABLE `oportunitats` (
  `id_oportunitat` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `titol` varchar(150) NOT NULL,
  `descripcio` text DEFAULT NULL,
  `valor_estimat` decimal(10,2) DEFAULT NULL,
  `estat` enum('progres','guanyada','perduda') DEFAULT 'progres',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasques`
--

CREATE TABLE `tasques` (
  `id_tarea` int(11) NOT NULL,
  `id_oportunitat` int(11) NOT NULL,
  `descripcio` text NOT NULL,
  `fecha` date DEFAULT NULL,
  `estat` enum('pendent','completada') DEFAULT 'pendent',
  `usuario_responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

CREATE TABLE `usuaris` (
  `id_usuari` int(11) NOT NULL,
  `nom_usuari` varchar(50) NOT NULL,
  `contrasenya` varchar(100) NOT NULL,
  `nom_complet` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` enum('administrador','venedor') NOT NULL,
  `data_registre` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id_usuari`, `nom_usuari`, `contrasenya`, `nom_complet`, `email`, `rol`, `data_registre`) VALUES
(1, 'admin', '$2y$10$..LthyxHxZhqbbN8R0ETQ.9d8MfVz8HGN33upYDJzKLGRBf//wyLm', 'Administrador', 'admin@crm.com', 'administrador', '2025-11-18 16:44:10'),
(2, '', '$2y$10$Pew7bvidjRGrHojLCSjiJuMk2NPCUrst6xqVNyLGbdvUpAguYalJe', '', '', '', '2025-11-18 16:44:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `usuario_responsable` (`usuario_responsable`);

--
-- Indices de la tabla `oportunitats`
--
ALTER TABLE `oportunitats`
  ADD PRIMARY KEY (`id_oportunitat`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `usuario_responsable` (`usuario_responsable`);

--
-- Indices de la tabla `tasques`
--
ALTER TABLE `tasques`
  ADD PRIMARY KEY (`id_tarea`),
  ADD KEY `id_oportunitat` (`id_oportunitat`),
  ADD KEY `fk_tasques_usuari_resp` (`usuario_responsable`);

--
-- Indices de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  ADD PRIMARY KEY (`id_usuari`),
  ADD UNIQUE KEY `nom_usuari` (`nom_usuari`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `oportunitats`
--
ALTER TABLE `oportunitats`
  MODIFY `id_oportunitat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tasques`
--
ALTER TABLE `tasques`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  MODIFY `id_usuari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuaris` (`id_usuari`) ON DELETE SET NULL;

--
-- Filtros para la tabla `oportunitats`
--
ALTER TABLE `oportunitats`
  ADD CONSTRAINT `oportunitats_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `oportunitats_ibfk_2` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuaris` (`id_usuari`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tasques`
--
ALTER TABLE `tasques`
  ADD CONSTRAINT `fk_tasques_usuari_resp` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuaris` (`id_usuari`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasques_ibfk_1` FOREIGN KEY (`id_oportunitat`) REFERENCES `oportunitats` (`id_oportunitat`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
