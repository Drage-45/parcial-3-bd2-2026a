-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2026 a las 00:09:08
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
-- Base de datos: `cinema_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleto`
--

CREATE TABLE `boleto` (
  `id_boleto` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_funcion_butaca` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `boleto`
--

INSERT INTO `boleto` (`id_boleto`, `fecha_venta`, `precio`, `id_cliente`, `id_funcion_butaca`, `id_venta`) VALUES
(1, '2026-06-06 08:36:58', 14500.00, 1, 313, NULL),
(2, '2026-06-06 08:36:59', 14500.00, 1, 314, NULL),
(3, '2026-06-06 08:46:39', 16000.00, 1, 157, NULL),
(4, '2026-06-06 08:46:39', 16000.00, 1, 156, NULL),
(5, '2026-06-06 18:45:03', 14500.00, 1, 357, NULL),
(6, '2026-06-06 18:45:03', 14500.00, 1, 358, NULL),
(7, '2026-06-07 21:31:28', 14500.00, 1, 322, 2),
(8, '2026-06-07 21:31:29', 14500.00, 1, 323, 2),
(9, '2026-06-07 21:31:57', 15000.00, 1, 41, 3),
(10, '2026-06-07 21:31:57', 15000.00, 1, 42, 3),
(11, '2026-06-07 22:09:17', 14500.00, 1, 261, 4),
(12, '2026-06-07 22:09:18', 14500.00, 1, 262, 4),
(13, '2026-06-08 00:10:45', 14500.00, 1, 347, 5),
(14, '2026-06-08 00:10:45', 14500.00, 1, 348, 5),
(15, '2026-06-08 13:56:05', 16000.00, 1, 122, 6),
(16, '2026-06-08 13:56:05', 16000.00, 1, 123, 6),
(17, '2026-06-08 14:08:16', 14500.00, 1, 361, 7),
(18, '2026-06-08 14:08:16', 14500.00, 1, 362, 7),
(19, '2026-06-08 14:08:41', 14000.00, 1, 211, 8),
(20, '2026-06-08 14:08:41', 14000.00, 1, 212, 8),
(21, '2026-06-08 14:40:44', 14500.00, 1, 321, 9),
(22, '2026-06-08 14:40:44', 14500.00, 1, 316, 9),
(23, '2026-06-08 14:40:44', 14500.00, 1, 311, 9),
(24, '2026-06-08 14:40:58', 14500.00, 1, 263, 10),
(25, '2026-06-08 14:40:58', 14500.00, 1, 264, 10),
(26, '2026-06-08 22:48:18', 14500.00, 1, 267, 3),
(27, '2026-06-08 22:48:18', 14500.00, 1, 268, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `butaca`
--

CREATE TABLE `butaca` (
  `id_butaca` int(11) NOT NULL,
  `fila` varchar(5) NOT NULL,
  `numero` varchar(3) NOT NULL,
  `id_sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `butaca`
--

INSERT INTO `butaca` (`id_butaca`, `fila`, `numero`, `id_sala`) VALUES
(1, 'A', '1', 1),
(41, 'A', '1', 2),
(2, 'A', '2', 1),
(42, 'A', '2', 2),
(3, 'A', '3', 1),
(43, 'A', '3', 2),
(4, 'A', '4', 1),
(44, 'A', '4', 2),
(5, 'A', '5', 1),
(45, 'A', '5', 2),
(6, 'B', '1', 1),
(46, 'B', '1', 2),
(7, 'B', '2', 1),
(47, 'B', '2', 2),
(8, 'B', '3', 1),
(48, 'B', '3', 2),
(9, 'B', '4', 1),
(49, 'B', '4', 2),
(10, 'B', '5', 1),
(50, 'B', '5', 2),
(11, 'C', '1', 1),
(51, 'C', '1', 2),
(12, 'C', '2', 1),
(52, 'C', '2', 2),
(13, 'C', '3', 1),
(53, 'C', '3', 2),
(14, 'C', '4', 1),
(54, 'C', '4', 2),
(15, 'C', '5', 1),
(55, 'C', '5', 2),
(16, 'D', '1', 1),
(56, 'D', '1', 2),
(17, 'D', '2', 1),
(57, 'D', '2', 2),
(18, 'D', '3', 1),
(58, 'D', '3', 2),
(19, 'D', '4', 1),
(59, 'D', '4', 2),
(20, 'D', '5', 1),
(60, 'D', '5', 2),
(21, 'E', '1', 1),
(61, 'E', '1', 2),
(22, 'E', '2', 1),
(62, 'E', '2', 2),
(23, 'E', '3', 1),
(63, 'E', '3', 2),
(24, 'E', '4', 1),
(64, 'E', '4', 2),
(25, 'E', '5', 1),
(65, 'E', '5', 2),
(26, 'F', '1', 1),
(66, 'F', '1', 2),
(27, 'F', '2', 1),
(67, 'F', '2', 2),
(28, 'F', '3', 1),
(68, 'F', '3', 2),
(29, 'F', '4', 1),
(69, 'F', '4', 2),
(30, 'F', '5', 1),
(70, 'F', '5', 2),
(31, 'G', '1', 1),
(71, 'G', '1', 2),
(32, 'G', '2', 1),
(72, 'G', '2', 2),
(33, 'G', '3', 1),
(73, 'G', '3', 2),
(34, 'G', '4', 1),
(74, 'G', '4', 2),
(35, 'G', '5', 1),
(75, 'G', '5', 2),
(36, 'H', '1', 1),
(76, 'H', '1', 2),
(37, 'H', '2', 1),
(77, 'H', '2', 2),
(38, 'H', '3', 1),
(78, 'H', '3', 2),
(39, 'H', '4', 1),
(79, 'H', '4', 2),
(40, 'H', '5', 1),
(80, 'H', '5', 2),
(81, 'I', '1', 2),
(82, 'I', '2', 2),
(83, 'I', '3', 2),
(84, 'I', '4', 2),
(85, 'I', '5', 2),
(86, 'J', '1', 2),
(87, 'J', '2', 2),
(88, 'J', '3', 2),
(89, 'J', '4', 2),
(90, 'J', '5', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `correo`, `contrasena`, `telefono`, `fecha_registro`) VALUES
(1, 'camilo', 'der@prueba.com', '$2y$10$uy.7oboedWtAacoF.gON..WexNFjhWF9Y5PkjdHVumscBqDAmOKTG', '12412423', '2026-06-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcion`
--

CREATE TABLE `funcion` (
  `id_funcion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_pelicula` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcion`
--

INSERT INTO `funcion` (`id_funcion`, `fecha`, `hora`, `precio`, `id_pelicula`, `id_sala`) VALUES
(1, '2026-06-20', '17:00:00', 15000.00, 1, 1),
(2, '2026-06-20', '20:00:00', 15000.00, 1, 1),
(3, '2026-06-20', '15:00:00', 14000.00, 2, 2),
(4, '2026-06-20', '18:00:00', 14000.00, 2, 2),
(5, '2026-06-21', '16:00:00', 16000.00, 3, 1),
(6, '2026-06-21', '20:00:00', 16000.00, 3, 1),
(7, '2026-06-22', '14:00:00', 14500.00, 7, 2),
(8, '2026-06-22', '19:45:00', 14500.00, 7, 2),
(9, '2026-06-22', '22:15:00', 14500.00, 7, 2),
(10, '2026-06-13', '13:00:00', 15000.00, 9, 1),
(11, '2026-06-13', '15:00:00', 15000.00, 9, 2),
(12, '2026-06-13', '15:00:00', 15000.00, 10, 1),
(13, '2026-06-13', '13:15:00', 15000.00, 5, 2),
(14, '2026-06-14', '14:30:00', 14500.00, 6, 1),
(15, '2026-06-17', '13:15:00', 7500.00, 8, 1),
(16, '2026-06-17', '15:30:00', 7500.00, 8, 1),
(17, '2026-06-16', '13:30:00', 15000.00, 4, 1),
(18, '2026-06-16', '15:55:00', 15000.00, 4, 1),
(19, '2026-06-16', '14:14:00', 15000.00, 10, 2),
(20, '2026-06-16', '18:00:00', 15000.00, 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcion_butaca`
--

CREATE TABLE `funcion_butaca` (
  `id_funcion_butaca` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'Disponible',
  `id_funcion` int(11) NOT NULL,
  `id_butaca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcion_butaca`
--

INSERT INTO `funcion_butaca` (`id_funcion_butaca`, `estado`, `id_funcion`, `id_butaca`) VALUES
(1, 'Disponible', 1, 1),
(2, 'Disponible', 1, 2),
(3, 'Disponible', 1, 3),
(4, 'Disponible', 1, 4),
(5, 'Disponible', 1, 5),
(6, 'Disponible', 1, 6),
(7, 'Disponible', 1, 7),
(8, 'Disponible', 1, 8),
(9, 'Disponible', 1, 9),
(10, 'Disponible', 1, 10),
(11, 'Disponible', 1, 11),
(12, 'Disponible', 1, 12),
(13, 'Disponible', 1, 13),
(14, 'Disponible', 1, 14),
(15, 'Disponible', 1, 15),
(16, 'Disponible', 1, 16),
(17, 'Disponible', 1, 17),
(18, 'Disponible', 1, 18),
(19, 'Disponible', 1, 19),
(20, 'Disponible', 1, 20),
(21, 'Disponible', 1, 21),
(22, 'Disponible', 1, 22),
(23, 'Disponible', 1, 23),
(24, 'Disponible', 1, 24),
(25, 'Disponible', 1, 25),
(26, 'Disponible', 1, 26),
(27, 'Disponible', 1, 27),
(28, 'Disponible', 1, 28),
(29, 'Disponible', 1, 29),
(30, 'Disponible', 1, 30),
(31, 'Disponible', 1, 31),
(32, 'Disponible', 1, 32),
(33, 'Disponible', 1, 33),
(34, 'Disponible', 1, 34),
(35, 'Disponible', 1, 35),
(36, 'Disponible', 1, 36),
(37, 'Disponible', 1, 37),
(38, 'Disponible', 1, 38),
(39, 'Disponible', 1, 39),
(40, 'Disponible', 1, 40),
(41, 'Ocupado', 2, 1),
(42, 'Ocupado', 2, 2),
(43, 'Disponible', 2, 3),
(44, 'Disponible', 2, 4),
(45, 'Disponible', 2, 5),
(46, 'Disponible', 2, 6),
(47, 'Disponible', 2, 7),
(48, 'Disponible', 2, 8),
(49, 'Disponible', 2, 9),
(50, 'Disponible', 2, 10),
(51, 'Disponible', 2, 11),
(52, 'Disponible', 2, 12),
(53, 'Disponible', 2, 13),
(54, 'Disponible', 2, 14),
(55, 'Disponible', 2, 15),
(56, 'Disponible', 2, 16),
(57, 'Disponible', 2, 17),
(58, 'Disponible', 2, 18),
(59, 'Disponible', 2, 19),
(60, 'Disponible', 2, 20),
(61, 'Disponible', 2, 21),
(62, 'Disponible', 2, 22),
(63, 'Disponible', 2, 23),
(64, 'Disponible', 2, 24),
(65, 'Disponible', 2, 25),
(66, 'Disponible', 2, 26),
(67, 'Disponible', 2, 27),
(68, 'Disponible', 2, 28),
(69, 'Disponible', 2, 29),
(70, 'Disponible', 2, 30),
(71, 'Disponible', 2, 31),
(72, 'Disponible', 2, 32),
(73, 'Disponible', 2, 33),
(74, 'Disponible', 2, 34),
(75, 'Disponible', 2, 35),
(76, 'Disponible', 2, 36),
(77, 'Disponible', 2, 37),
(78, 'Disponible', 2, 38),
(79, 'Disponible', 2, 39),
(80, 'Disponible', 2, 40),
(81, 'Disponible', 5, 1),
(82, 'Disponible', 5, 2),
(83, 'Disponible', 5, 3),
(84, 'Disponible', 5, 4),
(85, 'Disponible', 5, 5),
(86, 'Disponible', 5, 6),
(87, 'Disponible', 5, 7),
(88, 'Disponible', 5, 8),
(89, 'Disponible', 5, 9),
(90, 'Disponible', 5, 10),
(91, 'Disponible', 5, 11),
(92, 'Disponible', 5, 12),
(93, 'Disponible', 5, 13),
(94, 'Disponible', 5, 14),
(95, 'Disponible', 5, 15),
(96, 'Disponible', 5, 16),
(97, 'Disponible', 5, 17),
(98, 'Disponible', 5, 18),
(99, 'Disponible', 5, 19),
(100, 'Disponible', 5, 20),
(101, 'Disponible', 5, 21),
(102, 'Disponible', 5, 22),
(103, 'Disponible', 5, 23),
(104, 'Disponible', 5, 24),
(105, 'Disponible', 5, 25),
(106, 'Disponible', 5, 26),
(107, 'Disponible', 5, 27),
(108, 'Disponible', 5, 28),
(109, 'Disponible', 5, 29),
(110, 'Disponible', 5, 30),
(111, 'Disponible', 5, 31),
(112, 'Disponible', 5, 32),
(113, 'Disponible', 5, 33),
(114, 'Disponible', 5, 34),
(115, 'Disponible', 5, 35),
(116, 'Disponible', 5, 36),
(117, 'Disponible', 5, 37),
(118, 'Disponible', 5, 38),
(119, 'Disponible', 5, 39),
(120, 'Disponible', 5, 40),
(121, 'Disponible', 6, 1),
(122, 'Ocupado', 6, 2),
(123, 'Ocupado', 6, 3),
(124, 'Disponible', 6, 4),
(125, 'Disponible', 6, 5),
(126, 'Disponible', 6, 6),
(127, 'Disponible', 6, 7),
(128, 'Disponible', 6, 8),
(129, 'Disponible', 6, 9),
(130, 'Disponible', 6, 10),
(131, 'Disponible', 6, 11),
(132, 'Disponible', 6, 12),
(133, 'Disponible', 6, 13),
(134, 'Disponible', 6, 14),
(135, 'Disponible', 6, 15),
(136, 'Disponible', 6, 16),
(137, 'Disponible', 6, 17),
(138, 'Disponible', 6, 18),
(139, 'Disponible', 6, 19),
(140, 'Disponible', 6, 20),
(141, 'Disponible', 6, 21),
(142, 'Disponible', 6, 22),
(143, 'Disponible', 6, 23),
(144, 'Disponible', 6, 24),
(145, 'Disponible', 6, 25),
(146, 'Disponible', 6, 26),
(147, 'Disponible', 6, 27),
(148, 'Disponible', 6, 28),
(149, 'Disponible', 6, 29),
(150, 'Disponible', 6, 30),
(151, 'Disponible', 6, 31),
(152, 'Disponible', 6, 32),
(153, 'Disponible', 6, 33),
(154, 'Disponible', 6, 34),
(155, 'Disponible', 6, 35),
(156, 'Ocupado', 6, 36),
(157, 'Ocupado', 6, 37),
(158, 'Disponible', 6, 38),
(159, 'Disponible', 6, 39),
(160, 'Disponible', 6, 40),
(161, 'Disponible', 3, 41),
(162, 'Disponible', 3, 42),
(163, 'Disponible', 3, 43),
(164, 'Disponible', 3, 44),
(165, 'Disponible', 3, 45),
(166, 'Disponible', 3, 46),
(167, 'Disponible', 3, 47),
(168, 'Disponible', 3, 48),
(169, 'Disponible', 3, 49),
(170, 'Disponible', 3, 50),
(171, 'Disponible', 3, 51),
(172, 'Disponible', 3, 52),
(173, 'Disponible', 3, 53),
(174, 'Disponible', 3, 54),
(175, 'Disponible', 3, 55),
(176, 'Disponible', 3, 56),
(177, 'Disponible', 3, 57),
(178, 'Disponible', 3, 58),
(179, 'Disponible', 3, 59),
(180, 'Disponible', 3, 60),
(181, 'Disponible', 3, 61),
(182, 'Disponible', 3, 62),
(183, 'Disponible', 3, 63),
(184, 'Disponible', 3, 64),
(185, 'Disponible', 3, 65),
(186, 'Disponible', 3, 66),
(187, 'Disponible', 3, 67),
(188, 'Disponible', 3, 68),
(189, 'Disponible', 3, 69),
(190, 'Disponible', 3, 70),
(191, 'Disponible', 3, 71),
(192, 'Disponible', 3, 72),
(193, 'Disponible', 3, 73),
(194, 'Disponible', 3, 74),
(195, 'Disponible', 3, 75),
(196, 'Disponible', 3, 76),
(197, 'Disponible', 3, 77),
(198, 'Disponible', 3, 78),
(199, 'Disponible', 3, 79),
(200, 'Disponible', 3, 80),
(201, 'Disponible', 3, 81),
(202, 'Disponible', 3, 82),
(203, 'Disponible', 3, 83),
(204, 'Disponible', 3, 84),
(205, 'Disponible', 3, 85),
(206, 'Disponible', 3, 86),
(207, 'Disponible', 3, 87),
(208, 'Disponible', 3, 88),
(209, 'Disponible', 3, 89),
(210, 'Disponible', 3, 90),
(211, 'Ocupado', 4, 41),
(212, 'Ocupado', 4, 42),
(213, 'Disponible', 4, 43),
(214, 'Disponible', 4, 44),
(215, 'Disponible', 4, 45),
(216, 'Disponible', 4, 46),
(217, 'Disponible', 4, 47),
(218, 'Disponible', 4, 48),
(219, 'Disponible', 4, 49),
(220, 'Disponible', 4, 50),
(221, 'Disponible', 4, 51),
(222, 'Disponible', 4, 52),
(223, 'Disponible', 4, 53),
(224, 'Disponible', 4, 54),
(225, 'Disponible', 4, 55),
(226, 'Disponible', 4, 56),
(227, 'Disponible', 4, 57),
(228, 'Disponible', 4, 58),
(229, 'Disponible', 4, 59),
(230, 'Disponible', 4, 60),
(231, 'Disponible', 4, 61),
(232, 'Disponible', 4, 62),
(233, 'Disponible', 4, 63),
(234, 'Disponible', 4, 64),
(235, 'Disponible', 4, 65),
(236, 'Disponible', 4, 66),
(237, 'Disponible', 4, 67),
(238, 'Disponible', 4, 68),
(239, 'Disponible', 4, 69),
(240, 'Disponible', 4, 70),
(241, 'Disponible', 4, 71),
(242, 'Disponible', 4, 72),
(243, 'Disponible', 4, 73),
(244, 'Disponible', 4, 74),
(245, 'Disponible', 4, 75),
(246, 'Disponible', 4, 76),
(247, 'Disponible', 4, 77),
(248, 'Disponible', 4, 78),
(249, 'Disponible', 4, 79),
(250, 'Disponible', 4, 80),
(251, 'Disponible', 4, 81),
(252, 'Disponible', 4, 82),
(253, 'Disponible', 4, 83),
(254, 'Disponible', 4, 84),
(255, 'Disponible', 4, 85),
(256, 'Disponible', 4, 86),
(257, 'Disponible', 4, 87),
(258, 'Disponible', 4, 88),
(259, 'Disponible', 4, 89),
(260, 'Disponible', 4, 90),
(261, 'Ocupado', 7, 41),
(262, 'Ocupado', 7, 42),
(263, 'Ocupado', 7, 43),
(264, 'Ocupado', 7, 44),
(265, 'Disponible', 7, 45),
(266, 'Disponible', 7, 46),
(267, 'Ocupado', 7, 47),
(268, 'Ocupado', 7, 48),
(269, 'Disponible', 7, 49),
(270, 'Disponible', 7, 50),
(271, 'Disponible', 7, 51),
(272, 'Disponible', 7, 52),
(273, 'Disponible', 7, 53),
(274, 'Disponible', 7, 54),
(275, 'Disponible', 7, 55),
(276, 'Disponible', 7, 56),
(277, 'Disponible', 7, 57),
(278, 'Disponible', 7, 58),
(279, 'Disponible', 7, 59),
(280, 'Disponible', 7, 60),
(281, 'Disponible', 7, 61),
(282, 'Disponible', 7, 62),
(283, 'Disponible', 7, 63),
(284, 'Disponible', 7, 64),
(285, 'Disponible', 7, 65),
(286, 'Disponible', 7, 66),
(287, 'Disponible', 7, 67),
(288, 'Disponible', 7, 68),
(289, 'Disponible', 7, 69),
(290, 'Disponible', 7, 70),
(291, 'Disponible', 7, 71),
(292, 'Disponible', 7, 72),
(293, 'Disponible', 7, 73),
(294, 'Disponible', 7, 74),
(295, 'Disponible', 7, 75),
(296, 'Disponible', 7, 76),
(297, 'Disponible', 7, 77),
(298, 'Disponible', 7, 78),
(299, 'Disponible', 7, 79),
(300, 'Disponible', 7, 80),
(301, 'Disponible', 7, 81),
(302, 'Disponible', 7, 82),
(303, 'Disponible', 7, 83),
(304, 'Disponible', 7, 84),
(305, 'Disponible', 7, 85),
(306, 'Disponible', 7, 86),
(307, 'Disponible', 7, 87),
(308, 'Disponible', 7, 88),
(309, 'Disponible', 7, 89),
(310, 'Disponible', 7, 90),
(311, 'Ocupado', 8, 41),
(312, 'Disponible', 8, 42),
(313, 'Ocupado', 8, 43),
(314, 'Ocupado', 8, 44),
(315, 'Disponible', 8, 45),
(316, 'Ocupado', 8, 46),
(317, 'Disponible', 8, 47),
(318, 'Disponible', 8, 48),
(319, 'Disponible', 8, 49),
(320, 'Disponible', 8, 50),
(321, 'Ocupado', 8, 51),
(322, 'Ocupado', 8, 52),
(323, 'Ocupado', 8, 53),
(324, 'Disponible', 8, 54),
(325, 'Disponible', 8, 55),
(326, 'Disponible', 8, 56),
(327, 'Disponible', 8, 57),
(328, 'Disponible', 8, 58),
(329, 'Disponible', 8, 59),
(330, 'Disponible', 8, 60),
(331, 'Disponible', 8, 61),
(332, 'Disponible', 8, 62),
(333, 'Disponible', 8, 63),
(334, 'Disponible', 8, 64),
(335, 'Disponible', 8, 65),
(336, 'Disponible', 8, 66),
(337, 'Disponible', 8, 67),
(338, 'Disponible', 8, 68),
(339, 'Disponible', 8, 69),
(340, 'Disponible', 8, 70),
(341, 'Disponible', 8, 71),
(342, 'Disponible', 8, 72),
(343, 'Disponible', 8, 73),
(344, 'Disponible', 8, 74),
(345, 'Disponible', 8, 75),
(346, 'Disponible', 8, 76),
(347, 'Ocupado', 8, 77),
(348, 'Ocupado', 8, 78),
(349, 'Disponible', 8, 79),
(350, 'Disponible', 8, 80),
(351, 'Disponible', 8, 81),
(352, 'Disponible', 8, 82),
(353, 'Disponible', 8, 83),
(354, 'Disponible', 8, 84),
(355, 'Disponible', 8, 85),
(356, 'Disponible', 8, 86),
(357, 'Ocupado', 8, 87),
(358, 'Ocupado', 8, 88),
(359, 'Disponible', 8, 89),
(360, 'Disponible', 8, 90),
(361, 'Ocupado', 9, 41),
(362, 'Ocupado', 9, 42),
(363, 'Disponible', 9, 43),
(364, 'Disponible', 9, 44),
(365, 'Disponible', 9, 45),
(366, 'Disponible', 9, 46),
(367, 'Disponible', 9, 47),
(368, 'Disponible', 9, 48),
(369, 'Disponible', 9, 49),
(370, 'Disponible', 9, 50),
(371, 'Disponible', 9, 51),
(372, 'Disponible', 9, 52),
(373, 'Disponible', 9, 53),
(374, 'Disponible', 9, 54),
(375, 'Disponible', 9, 55),
(376, 'Disponible', 9, 56),
(377, 'Disponible', 9, 57),
(378, 'Disponible', 9, 58),
(379, 'Disponible', 9, 59),
(380, 'Disponible', 9, 60),
(381, 'Disponible', 9, 61),
(382, 'Disponible', 9, 62),
(383, 'Disponible', 9, 63),
(384, 'Disponible', 9, 64),
(385, 'Disponible', 9, 65),
(386, 'Disponible', 9, 66),
(387, 'Disponible', 9, 67),
(388, 'Disponible', 9, 68),
(389, 'Disponible', 9, 69),
(390, 'Disponible', 9, 70),
(391, 'Disponible', 9, 71),
(392, 'Disponible', 9, 72),
(393, 'Disponible', 9, 73),
(394, 'Disponible', 9, 74),
(395, 'Disponible', 9, 75),
(396, 'Disponible', 9, 76),
(397, 'Disponible', 9, 77),
(398, 'Disponible', 9, 78),
(399, 'Disponible', 9, 79),
(400, 'Disponible', 9, 80),
(401, 'Disponible', 9, 81),
(402, 'Disponible', 9, 82),
(403, 'Disponible', 9, 83),
(404, 'Disponible', 9, 84),
(405, 'Disponible', 9, 85),
(406, 'Disponible', 9, 86),
(407, 'Disponible', 9, 87),
(408, 'Disponible', 9, 88),
(409, 'Disponible', 9, 89),
(410, 'Disponible', 9, 90),
(411, 'Disponible', 10, 1),
(412, 'Disponible', 10, 2),
(413, 'Disponible', 10, 3),
(414, 'Disponible', 10, 4),
(415, 'Disponible', 10, 5),
(416, 'Disponible', 10, 6),
(417, 'Disponible', 10, 7),
(418, 'Disponible', 10, 8),
(419, 'Disponible', 10, 9),
(420, 'Disponible', 10, 10),
(421, 'Disponible', 10, 11),
(422, 'Disponible', 10, 12),
(423, 'Disponible', 10, 13),
(424, 'Disponible', 10, 14),
(425, 'Disponible', 10, 15),
(426, 'Disponible', 10, 16),
(427, 'Disponible', 10, 17),
(428, 'Disponible', 10, 18),
(429, 'Disponible', 10, 19),
(430, 'Disponible', 10, 20),
(431, 'Disponible', 10, 21),
(432, 'Disponible', 10, 22),
(433, 'Disponible', 10, 23),
(434, 'Disponible', 10, 24),
(435, 'Disponible', 10, 25),
(436, 'Disponible', 10, 26),
(437, 'Disponible', 10, 27),
(438, 'Disponible', 10, 28),
(439, 'Disponible', 10, 29),
(440, 'Disponible', 10, 30),
(441, 'Disponible', 10, 31),
(442, 'Disponible', 10, 32),
(443, 'Disponible', 10, 33),
(444, 'Disponible', 10, 34),
(445, 'Disponible', 10, 35),
(446, 'Disponible', 10, 36),
(447, 'Disponible', 10, 37),
(448, 'Disponible', 10, 38),
(449, 'Disponible', 10, 39),
(450, 'Disponible', 10, 40),
(451, 'Disponible', 11, 41),
(452, 'Disponible', 11, 42),
(453, 'Disponible', 11, 43),
(454, 'Disponible', 11, 44),
(455, 'Disponible', 11, 45),
(456, 'Disponible', 11, 46),
(457, 'Disponible', 11, 47),
(458, 'Disponible', 11, 48),
(459, 'Disponible', 11, 49),
(460, 'Disponible', 11, 50),
(461, 'Disponible', 11, 51),
(462, 'Disponible', 11, 52),
(463, 'Disponible', 11, 53),
(464, 'Disponible', 11, 54),
(465, 'Disponible', 11, 55),
(466, 'Disponible', 11, 56),
(467, 'Disponible', 11, 57),
(468, 'Disponible', 11, 58),
(469, 'Disponible', 11, 59),
(470, 'Disponible', 11, 60),
(471, 'Disponible', 11, 61),
(472, 'Disponible', 11, 62),
(473, 'Disponible', 11, 63),
(474, 'Disponible', 11, 64),
(475, 'Disponible', 11, 65),
(476, 'Disponible', 11, 66),
(477, 'Disponible', 11, 67),
(478, 'Disponible', 11, 68),
(479, 'Disponible', 11, 69),
(480, 'Disponible', 11, 70),
(481, 'Disponible', 11, 71),
(482, 'Disponible', 11, 72),
(483, 'Disponible', 11, 73),
(484, 'Disponible', 11, 74),
(485, 'Disponible', 11, 75),
(486, 'Disponible', 11, 76),
(487, 'Disponible', 11, 77),
(488, 'Disponible', 11, 78),
(489, 'Disponible', 11, 79),
(490, 'Disponible', 11, 80),
(491, 'Disponible', 11, 81),
(492, 'Disponible', 11, 82),
(493, 'Disponible', 11, 83),
(494, 'Disponible', 11, 84),
(495, 'Disponible', 11, 85),
(496, 'Disponible', 11, 86),
(497, 'Disponible', 11, 87),
(498, 'Disponible', 11, 88),
(499, 'Disponible', 11, 89),
(500, 'Disponible', 11, 90),
(501, 'Disponible', 12, 1),
(502, 'Disponible', 12, 2),
(503, 'Disponible', 12, 3),
(504, 'Disponible', 12, 4),
(505, 'Disponible', 12, 5),
(506, 'Disponible', 12, 6),
(507, 'Disponible', 12, 7),
(508, 'Disponible', 12, 8),
(509, 'Disponible', 12, 9),
(510, 'Disponible', 12, 10),
(511, 'Disponible', 12, 11),
(512, 'Disponible', 12, 12),
(513, 'Disponible', 12, 13),
(514, 'Disponible', 12, 14),
(515, 'Disponible', 12, 15),
(516, 'Disponible', 12, 16),
(517, 'Disponible', 12, 17),
(518, 'Disponible', 12, 18),
(519, 'Disponible', 12, 19),
(520, 'Disponible', 12, 20),
(521, 'Disponible', 12, 21),
(522, 'Disponible', 12, 22),
(523, 'Disponible', 12, 23),
(524, 'Disponible', 12, 24),
(525, 'Disponible', 12, 25),
(526, 'Disponible', 12, 26),
(527, 'Disponible', 12, 27),
(528, 'Disponible', 12, 28),
(529, 'Disponible', 12, 29),
(530, 'Disponible', 12, 30),
(531, 'Disponible', 12, 31),
(532, 'Disponible', 12, 32),
(533, 'Disponible', 12, 33),
(534, 'Disponible', 12, 34),
(535, 'Disponible', 12, 35),
(536, 'Disponible', 12, 36),
(537, 'Disponible', 12, 37),
(538, 'Disponible', 12, 38),
(539, 'Disponible', 12, 39),
(540, 'Disponible', 12, 40),
(541, 'Disponible', 13, 41),
(542, 'Disponible', 13, 42),
(543, 'Disponible', 13, 43),
(544, 'Disponible', 13, 44),
(545, 'Disponible', 13, 45),
(546, 'Disponible', 13, 46),
(547, 'Disponible', 13, 47),
(548, 'Disponible', 13, 48),
(549, 'Disponible', 13, 49),
(550, 'Disponible', 13, 50),
(551, 'Disponible', 13, 51),
(552, 'Disponible', 13, 52),
(553, 'Disponible', 13, 53),
(554, 'Disponible', 13, 54),
(555, 'Disponible', 13, 55),
(556, 'Disponible', 13, 56),
(557, 'Disponible', 13, 57),
(558, 'Disponible', 13, 58),
(559, 'Disponible', 13, 59),
(560, 'Disponible', 13, 60),
(561, 'Disponible', 13, 61),
(562, 'Disponible', 13, 62),
(563, 'Disponible', 13, 63),
(564, 'Disponible', 13, 64),
(565, 'Disponible', 13, 65),
(566, 'Disponible', 13, 66),
(567, 'Disponible', 13, 67),
(568, 'Disponible', 13, 68),
(569, 'Disponible', 13, 69),
(570, 'Disponible', 13, 70),
(571, 'Disponible', 13, 71),
(572, 'Disponible', 13, 72),
(573, 'Disponible', 13, 73),
(574, 'Disponible', 13, 74),
(575, 'Disponible', 13, 75),
(576, 'Disponible', 13, 76),
(577, 'Disponible', 13, 77),
(578, 'Disponible', 13, 78),
(579, 'Disponible', 13, 79),
(580, 'Disponible', 13, 80),
(581, 'Disponible', 13, 81),
(582, 'Disponible', 13, 82),
(583, 'Disponible', 13, 83),
(584, 'Disponible', 13, 84),
(585, 'Disponible', 13, 85),
(586, 'Disponible', 13, 86),
(587, 'Disponible', 13, 87),
(588, 'Disponible', 13, 88),
(589, 'Disponible', 13, 89),
(590, 'Disponible', 13, 90),
(591, 'Disponible', 14, 1),
(592, 'Disponible', 14, 2),
(593, 'Disponible', 14, 3),
(594, 'Disponible', 14, 4),
(595, 'Disponible', 14, 5),
(596, 'Disponible', 14, 6),
(597, 'Disponible', 14, 7),
(598, 'Disponible', 14, 8),
(599, 'Disponible', 14, 9),
(600, 'Disponible', 14, 10),
(601, 'Disponible', 14, 11),
(602, 'Disponible', 14, 12),
(603, 'Disponible', 14, 13),
(604, 'Disponible', 14, 14),
(605, 'Disponible', 14, 15),
(606, 'Disponible', 14, 16),
(607, 'Disponible', 14, 17),
(608, 'Disponible', 14, 18),
(609, 'Disponible', 14, 19),
(610, 'Disponible', 14, 20),
(611, 'Disponible', 14, 21),
(612, 'Disponible', 14, 22),
(613, 'Disponible', 14, 23),
(614, 'Disponible', 14, 24),
(615, 'Disponible', 14, 25),
(616, 'Disponible', 14, 26),
(617, 'Disponible', 14, 27),
(618, 'Disponible', 14, 28),
(619, 'Disponible', 14, 29),
(620, 'Disponible', 14, 30),
(621, 'Disponible', 14, 31),
(622, 'Disponible', 14, 32),
(623, 'Disponible', 14, 33),
(624, 'Disponible', 14, 34),
(625, 'Disponible', 14, 35),
(626, 'Disponible', 14, 36),
(627, 'Disponible', 14, 37),
(628, 'Disponible', 14, 38),
(629, 'Disponible', 14, 39),
(630, 'Disponible', 14, 40),
(631, 'Disponible', 15, 1),
(632, 'Disponible', 15, 2),
(633, 'Disponible', 15, 3),
(634, 'Disponible', 15, 4),
(635, 'Disponible', 15, 5),
(636, 'Disponible', 15, 6),
(637, 'Disponible', 15, 7),
(638, 'Disponible', 15, 8),
(639, 'Disponible', 15, 9),
(640, 'Disponible', 15, 10),
(641, 'Disponible', 15, 11),
(642, 'Disponible', 15, 12),
(643, 'Disponible', 15, 13),
(644, 'Disponible', 15, 14),
(645, 'Disponible', 15, 15),
(646, 'Disponible', 15, 16),
(647, 'Disponible', 15, 17),
(648, 'Disponible', 15, 18),
(649, 'Disponible', 15, 19),
(650, 'Disponible', 15, 20),
(651, 'Disponible', 15, 21),
(652, 'Disponible', 15, 22),
(653, 'Disponible', 15, 23),
(654, 'Disponible', 15, 24),
(655, 'Disponible', 15, 25),
(656, 'Disponible', 15, 26),
(657, 'Disponible', 15, 27),
(658, 'Disponible', 15, 28),
(659, 'Disponible', 15, 29),
(660, 'Disponible', 15, 30),
(661, 'Disponible', 15, 31),
(662, 'Disponible', 15, 32),
(663, 'Disponible', 15, 33),
(664, 'Disponible', 15, 34),
(665, 'Disponible', 15, 35),
(666, 'Disponible', 15, 36),
(667, 'Disponible', 15, 37),
(668, 'Disponible', 15, 38),
(669, 'Disponible', 15, 39),
(670, 'Disponible', 15, 40),
(671, 'Disponible', 16, 1),
(672, 'Disponible', 16, 2),
(673, 'Disponible', 16, 3),
(674, 'Disponible', 16, 4),
(675, 'Disponible', 16, 5),
(676, 'Disponible', 16, 6),
(677, 'Disponible', 16, 7),
(678, 'Disponible', 16, 8),
(679, 'Disponible', 16, 9),
(680, 'Disponible', 16, 10),
(681, 'Disponible', 16, 11),
(682, 'Disponible', 16, 12),
(683, 'Disponible', 16, 13),
(684, 'Disponible', 16, 14),
(685, 'Disponible', 16, 15),
(686, 'Disponible', 16, 16),
(687, 'Disponible', 16, 17),
(688, 'Disponible', 16, 18),
(689, 'Disponible', 16, 19),
(690, 'Disponible', 16, 20),
(691, 'Disponible', 16, 21),
(692, 'Disponible', 16, 22),
(693, 'Disponible', 16, 23),
(694, 'Disponible', 16, 24),
(695, 'Disponible', 16, 25),
(696, 'Disponible', 16, 26),
(697, 'Disponible', 16, 27),
(698, 'Disponible', 16, 28),
(699, 'Disponible', 16, 29),
(700, 'Disponible', 16, 30),
(701, 'Disponible', 16, 31),
(702, 'Disponible', 16, 32),
(703, 'Disponible', 16, 33),
(704, 'Disponible', 16, 34),
(705, 'Disponible', 16, 35),
(706, 'Disponible', 16, 36),
(707, 'Disponible', 16, 37),
(708, 'Disponible', 16, 38),
(709, 'Disponible', 16, 39),
(710, 'Disponible', 16, 40),
(711, 'Disponible', 17, 1),
(712, 'Disponible', 17, 2),
(713, 'Disponible', 17, 3),
(714, 'Disponible', 17, 4),
(715, 'Disponible', 17, 5),
(716, 'Disponible', 17, 6),
(717, 'Disponible', 17, 7),
(718, 'Disponible', 17, 8),
(719, 'Disponible', 17, 9),
(720, 'Disponible', 17, 10),
(721, 'Disponible', 17, 11),
(722, 'Disponible', 17, 12),
(723, 'Disponible', 17, 13),
(724, 'Disponible', 17, 14),
(725, 'Disponible', 17, 15),
(726, 'Disponible', 17, 16),
(727, 'Disponible', 17, 17),
(728, 'Disponible', 17, 18),
(729, 'Disponible', 17, 19),
(730, 'Disponible', 17, 20),
(731, 'Disponible', 17, 21),
(732, 'Disponible', 17, 22),
(733, 'Disponible', 17, 23),
(734, 'Disponible', 17, 24),
(735, 'Disponible', 17, 25),
(736, 'Disponible', 17, 26),
(737, 'Disponible', 17, 27),
(738, 'Disponible', 17, 28),
(739, 'Disponible', 17, 29),
(740, 'Disponible', 17, 30),
(741, 'Disponible', 17, 31),
(742, 'Disponible', 17, 32),
(743, 'Disponible', 17, 33),
(744, 'Disponible', 17, 34),
(745, 'Disponible', 17, 35),
(746, 'Disponible', 17, 36),
(747, 'Disponible', 17, 37),
(748, 'Disponible', 17, 38),
(749, 'Disponible', 17, 39),
(750, 'Disponible', 17, 40),
(751, 'Disponible', 18, 1),
(752, 'Disponible', 18, 2),
(753, 'Disponible', 18, 3),
(754, 'Disponible', 18, 4),
(755, 'Disponible', 18, 5),
(756, 'Disponible', 18, 6),
(757, 'Disponible', 18, 7),
(758, 'Disponible', 18, 8),
(759, 'Disponible', 18, 9),
(760, 'Disponible', 18, 10),
(761, 'Disponible', 18, 11),
(762, 'Disponible', 18, 12),
(763, 'Disponible', 18, 13),
(764, 'Disponible', 18, 14),
(765, 'Disponible', 18, 15),
(766, 'Disponible', 18, 16),
(767, 'Disponible', 18, 17),
(768, 'Disponible', 18, 18),
(769, 'Disponible', 18, 19),
(770, 'Disponible', 18, 20),
(771, 'Disponible', 18, 21),
(772, 'Disponible', 18, 22),
(773, 'Disponible', 18, 23),
(774, 'Disponible', 18, 24),
(775, 'Disponible', 18, 25),
(776, 'Disponible', 18, 26),
(777, 'Disponible', 18, 27),
(778, 'Disponible', 18, 28),
(779, 'Disponible', 18, 29),
(780, 'Disponible', 18, 30),
(781, 'Disponible', 18, 31),
(782, 'Disponible', 18, 32),
(783, 'Disponible', 18, 33),
(784, 'Disponible', 18, 34),
(785, 'Disponible', 18, 35),
(786, 'Disponible', 18, 36),
(787, 'Disponible', 18, 37),
(788, 'Disponible', 18, 38),
(789, 'Disponible', 18, 39),
(790, 'Disponible', 18, 40),
(791, 'Disponible', 19, 41),
(792, 'Disponible', 19, 42),
(793, 'Disponible', 19, 43),
(794, 'Disponible', 19, 44),
(795, 'Disponible', 19, 45),
(796, 'Disponible', 19, 46),
(797, 'Disponible', 19, 47),
(798, 'Disponible', 19, 48),
(799, 'Disponible', 19, 49),
(800, 'Disponible', 19, 50),
(801, 'Disponible', 19, 51),
(802, 'Disponible', 19, 52),
(803, 'Disponible', 19, 53),
(804, 'Disponible', 19, 54),
(805, 'Disponible', 19, 55),
(806, 'Disponible', 19, 56),
(807, 'Disponible', 19, 57),
(808, 'Disponible', 19, 58),
(809, 'Disponible', 19, 59),
(810, 'Disponible', 19, 60),
(811, 'Disponible', 19, 61),
(812, 'Disponible', 19, 62),
(813, 'Disponible', 19, 63),
(814, 'Disponible', 19, 64),
(815, 'Disponible', 19, 65),
(816, 'Disponible', 19, 66),
(817, 'Disponible', 19, 67),
(818, 'Disponible', 19, 68),
(819, 'Disponible', 19, 69),
(820, 'Disponible', 19, 70),
(821, 'Disponible', 19, 71),
(822, 'Disponible', 19, 72),
(823, 'Disponible', 19, 73),
(824, 'Disponible', 19, 74),
(825, 'Disponible', 19, 75),
(826, 'Disponible', 19, 76),
(827, 'Disponible', 19, 77),
(828, 'Disponible', 19, 78),
(829, 'Disponible', 19, 79),
(830, 'Disponible', 19, 80),
(831, 'Disponible', 19, 81),
(832, 'Disponible', 19, 82),
(833, 'Disponible', 19, 83),
(834, 'Disponible', 19, 84),
(835, 'Disponible', 19, 85),
(836, 'Disponible', 19, 86),
(837, 'Disponible', 19, 87),
(838, 'Disponible', 19, 88),
(839, 'Disponible', 19, 89),
(840, 'Disponible', 19, 90),
(841, 'Disponible', 20, 41),
(842, 'Disponible', 20, 42),
(843, 'Disponible', 20, 43),
(844, 'Disponible', 20, 44),
(845, 'Disponible', 20, 45),
(846, 'Disponible', 20, 46),
(847, 'Disponible', 20, 47),
(848, 'Disponible', 20, 48),
(849, 'Disponible', 20, 49),
(850, 'Disponible', 20, 50),
(851, 'Disponible', 20, 51),
(852, 'Disponible', 20, 52),
(853, 'Disponible', 20, 53),
(854, 'Disponible', 20, 54),
(855, 'Disponible', 20, 55),
(856, 'Disponible', 20, 56),
(857, 'Disponible', 20, 57),
(858, 'Disponible', 20, 58),
(859, 'Disponible', 20, 59),
(860, 'Disponible', 20, 60),
(861, 'Disponible', 20, 61),
(862, 'Disponible', 20, 62),
(863, 'Disponible', 20, 63),
(864, 'Disponible', 20, 64),
(865, 'Disponible', 20, 65),
(866, 'Disponible', 20, 66),
(867, 'Disponible', 20, 67),
(868, 'Disponible', 20, 68),
(869, 'Disponible', 20, 69),
(870, 'Disponible', 20, 70),
(871, 'Disponible', 20, 71),
(872, 'Disponible', 20, 72),
(873, 'Disponible', 20, 73),
(874, 'Disponible', 20, 74),
(875, 'Disponible', 20, 75),
(876, 'Disponible', 20, 76),
(877, 'Disponible', 20, 77),
(878, 'Disponible', 20, 78),
(879, 'Disponible', 20, 79),
(880, 'Disponible', 20, 80),
(881, 'Disponible', 20, 81),
(882, 'Disponible', 20, 82),
(883, 'Disponible', 20, 83),
(884, 'Disponible', 20, 84),
(885, 'Disponible', 20, 85),
(886, 'Disponible', 20, 86),
(887, 'Disponible', 20, 87),
(888, 'Disponible', 20, 88),
(889, 'Disponible', 20, 89),
(890, 'Disponible', 20, 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(1, 'Acción'),
(2, 'Animación'),
(3, 'Terror'),
(4, 'Ciencia Ficción'),
(5, 'Drama'),
(6, 'Bibliográfica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelicula`
--

CREATE TABLE `pelicula` (
  `id_pelicula` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `duracion` varchar(15) NOT NULL,
  `clasificacion` varchar(20) DEFAULT NULL,
  `sinopsis` text DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pelicula`
--

INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `duracion`, `clasificacion`, `sinopsis`, `imagen`, `id_genero`) VALUES
(1, 'The Mandalorian and Grogu', '120min', '+13', 'Nueva aventura galáctica protagonizada por Din Djarin y Grogu.', 'Images/mandalorian-poster.jpeg', 1),
(2, 'Super Mario Galaxy', '95 min', 'A', 'Mario y Luigi deben salvar el universo de un nuevo enemigo.', 'Images/mario-poster.jpg', 2),
(3, 'Scream 7', '110 min', '+16', 'Ghostface regresa para una nueva ola de terror.', 'Images/scream.jpg', 3),
(4, 'Proyecto Fin del Mundo', '120 min', '+14', 'Un astronauta debe salvar a la humanidad cuando el Sol comienza a apagarse.', 'Images/Proyecto.jpg', 4),
(5, 'Boda Sangrienta 2', '108min', '+16', 'La noche de bodas se convierte en una pesadilla cuando la familia revela su verdadero rostro.', 'Images/boda.jpg', 3),
(6, 'La Posesion de la Momia', '134 min', '+18', 'Un grupo de arqueólogos despierta una maldición ancestral al descubrir una tumba prohibida.', 'Images/momia.jpg', 3),
(7, 'Michael', '127 min', '+12', 'Un vistazo profundo a la vida y el legado del Rey del Pop, Michael Jackson.', 'Images/michael-poster.jpg', 6),
(8, 'El Diablo Viste a la Moda 2', '119 min', '+13', 'Miranda Priestly regresa con nuevos desafíos en el mundo de la alta costura.', 'Images/moda-poster.jpeg', 5),
(9, 'Backrooms: Sin salida', '105 min', '+15', 'Una extraña puerta aparece en el sótano de una sala de exposición de muebles.', 'Images/backrooms.jpg', 3),
(10, 'Hoppers: Operacion Castor', '105 min', '+12', 'Una estudiante universitaria amante de los animales, que transfiere su mente a un castor robótico de apariencia humana para comunicarse con los animales y salvar su hábitat de la destrucción humana.', 'Images/hoppers-poster.jpg', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `id_sala` int(11) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `capacidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sala`
--

INSERT INTO `sala` (`id_sala`, `nombre`, `capacidad`) VALUES
(1, 'Sala 1', 40),
(2, 'Sala 2', 50);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boleto`
--
ALTER TABLE `boleto`
  ADD PRIMARY KEY (`id_boleto`),
  ADD UNIQUE KEY `id_funcion_butaca` (`id_funcion_butaca`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `butaca`
--
ALTER TABLE `butaca`
  ADD PRIMARY KEY (`id_butaca`),
  ADD UNIQUE KEY `fila` (`fila`,`numero`,`id_sala`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `funcion`
--
ALTER TABLE `funcion`
  ADD PRIMARY KEY (`id_funcion`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_pelicula` (`id_pelicula`);

--
-- Indices de la tabla `funcion_butaca`
--
ALTER TABLE `funcion_butaca`
  ADD PRIMARY KEY (`id_funcion_butaca`),
  ADD UNIQUE KEY `id_funcion` (`id_funcion`,`id_butaca`),
  ADD KEY `id_butaca` (`id_butaca`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD PRIMARY KEY (`id_pelicula`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id_sala`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boleto`
--
ALTER TABLE `boleto`
  MODIFY `id_boleto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `butaca`
--
ALTER TABLE `butaca`
  MODIFY `id_butaca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `funcion`
--
ALTER TABLE `funcion`
  MODIFY `id_funcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `funcion_butaca`
--
ALTER TABLE `funcion_butaca`
  MODIFY `id_funcion_butaca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=891;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pelicula`
--
ALTER TABLE `pelicula`
  MODIFY `id_pelicula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sala`
--
ALTER TABLE `sala`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boleto`
--
ALTER TABLE `boleto`
  ADD CONSTRAINT `boleto_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `boleto_ibfk_2` FOREIGN KEY (`id_funcion_butaca`) REFERENCES `funcion_butaca` (`id_funcion_butaca`);

--
-- Filtros para la tabla `butaca`
--
ALTER TABLE `butaca`
  ADD CONSTRAINT `butaca_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);

--
-- Filtros para la tabla `funcion`
--
ALTER TABLE `funcion`
  ADD CONSTRAINT `funcion_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`),
  ADD CONSTRAINT `funcion_ibfk_2` FOREIGN KEY (`id_pelicula`) REFERENCES `pelicula` (`id_pelicula`);

--
-- Filtros para la tabla `funcion_butaca`
--
ALTER TABLE `funcion_butaca`
  ADD CONSTRAINT `funcion_butaca_ibfk_1` FOREIGN KEY (`id_funcion`) REFERENCES `funcion` (`id_funcion`),
  ADD CONSTRAINT `funcion_butaca_ibfk_2` FOREIGN KEY (`id_butaca`) REFERENCES `butaca` (`id_butaca`);

--
-- Filtros para la tabla `pelicula`
--
ALTER TABLE `pelicula`
  ADD CONSTRAINT `pelicula_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
