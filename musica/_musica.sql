
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `agenda`
--
CREATE DATABASE IF NOT EXISTS `musica` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `musica`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artista`
--

DROP TABLE IF EXISTS `artista`;
CREATE TABLE IF NOT EXISTS `artista` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `artista`
--

INSERT INTO `artista` (`id`, `nombre`) VALUES
(1, 'Recycled J'),
(2, 'Skyhook'),
(3, 'Dano'),
(4, 'Ebano'),
(5, 'Imagine Dragons'),
(6, 'C. Tangana'),
(7, 'Estopa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancion`
--

DROP TABLE IF EXISTS `cancion`;
CREATE TABLE IF NOT EXISTS `cancion` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
            `album` varchar(45) DEFAULT NULL,
            `duracion` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
            `favoritos` tinyint(1) NOT NULL DEFAULT 0,
            `artistaId` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_artistaIdIdx` (`artistaId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cancion`
--

INSERT INTO `cancion` (`id`, `nombre`, `album`, `duracion`, `favoritos`, `artistaId`) VALUES
(1, 'Guiness', 'City Pop', '2:47', 1, 1),
(2, 'A Escondidas', 'Moonchies', '3:38', 1, 2),
(3, 'El Igloo', 'Istmo', '4:07', 1, 3),
(4, 'Overdosin', 'L2POE', '3:53', 0, 4),
(5, 'Joven Jugador', 'L2POE', '3:09', 0, 4),
(6, 'Thunder', 'Evolve', '3:07', 1, 5),
(7, 'Tu me dejaste de querer', 'Sencillo', '3:18', 1, 6),
(8, 'Ronin', 'Until You Get Here', '3:13', 1, 2),
(9, 'Believer', 'Evolve', '3:24', 1, 5),
(10, 'Fuego', 'Fuego', '3:15', 1, 7);



--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancion`
--
ALTER TABLE `cancion`
    ADD CONSTRAINT `fk_artistaId` FOREIGN KEY (`artistaId`) REFERENCES `artista` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;