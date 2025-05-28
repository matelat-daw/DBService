-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2023 a las 17:11:36
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web_service`
--
CREATE DATABASE IF NOT EXISTS `web_service` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `web_service`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bday` date NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `path` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id`, `name`, `phone`, `email`, `pass`, `bday`, `gender`, `path`) VALUES
(1, 'Juan Perez', '651234567', 'juan@mail.com', '$2y$10$y33WPOB68oPJ1.znfJOcaegje9xiPWULJvfNXjd52oes0uu0kzgDe', '0000-00-00', 0, 'img/female.jpg'),
(2, 'Pedro Gomez', '5512345698', 'pedro@mail.com', '', '0000-00-00', 0, 'img/female.jpg'),
(3, 'Ezequiel Ramirez', '5512345687', 'ezequiel@mail.com', '', '0000-00-00', 0, 'img/female.jpg'),
(4, 'Maria Solano', '5512345663', 'maria@mail.com', '$2y$10$tkrWzOUYdJ48RRrVkWJWUOie7/FwIkfQiDLMsier7pcCKzs8hegz2', '0000-00-00', 0, 'img/female.jpg'),
(5, 'Ana Martinez', '5512345612', 'ana@mail.com', '', '0000-00-00', 0, 'img/female.jpg'),
(6, 'César Matelat Borneo', '664774821', 'matelat@gmail.com', '$2y$10$.DswSzOhzIaHLIVIHKhUhuO.bKVzq3IMo2LyGs9brqjvF.G2tgH3O', '0000-00-00', 0, 'img/female.jpg'),
(7, 'Osvaldo Borneo', '664774822', 'orions@gmx.net', '$2y$10$whN5QTokTtZsryA5g/WI0.BFb9nwi9ZA2lN96HL9KvTImbZASyrtu', '0000-00-00', 0, 'img/female.jpg'),
(8, 'Osvaldo Borneo', '664774823', 'orions68@gmail.com', '$2y$10$3X9883HTNBzePVcjLeSx0esKj2LHWiwh.aGWr9iCqCYTOESM4SeZy', '1968-04-05', 1, 'img/male.jpg'),
(9, 'Primero Firsthy', '611111111', '1@1.com', '$2y$10$Zb0/q4TgLbu3NgXV2Ok.sOSqk7zcMRX3MdodnPiH8v57VjnnPUcVC', '1968-04-05', 1, 'img/male.jpg'),
(10, 'Segundo Secondo', '622222222', '2@2.com', '$2y$10$vP8SXf5nSOP2RVR14H6yo.P63SBStdVIWuaE47vp/KHBFsA67tpS2', '1968-04-05', 1, 'img/male.jpg'),
(11, 'Tercero Thirdthy', '633333333', '3@3.com', '$2y$10$DzltB8fHRItIm0hY9m7LsufVB3Rh4JISTDTW/Z/2aLnM5gbOXjoCK', '1968-04-05', 1, 'img/male.jpg'),
(12, 'Cuarta Fourthy', '644444444', '4@4.com', '$2y$10$/T9Y6B7qN71.iAaeHmLo1OtJ5VTK5rrxrMZVHhizmS5WDVeUhIP0K', '1968-04-05', 0, 'img/female.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telefono` (`phone`,`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
