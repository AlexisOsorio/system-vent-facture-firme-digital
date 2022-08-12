-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-08-2022 a las 00:12:46
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)  BEGIN
    	DECLARE nueva_existencia int;
        DECLARE nuevo_total  decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        
        DECLARE actual_existencia int;
        DECLARE actual_precio decimal(10,2);
                
        SELECT precio,existencia INTO actual_precio,actual_existencia FROM producto WHERE codproducto = codigo;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        
        UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;
        
        SELECT nueva_existencia,nuevo_precio;
        
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50))  BEGIN
       DECLARE precio_actual decimal(10,2);
       SELECT precio INTO precio_actual FROM producto WHERE codproducto = codigo;
       
       INSERT INTO detalle_temp(token_user,codproducto,cantidad,precio_venta)VALUES(token_user,codigo,cantidad,precio_actual);
       
       SELECT tmp.correlativo,tmp.codproducto,p.descripcion,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
        INNER JOIN producto p
       ON tmp.codproducto = p.codproducto
       WHERE tmp.token_user = token_user;
       
   END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datos_dashboard` ()  BEGIN
    	DECLARE usuarios int;
        DECLARE clientes int;
        DECLARE proveedores int;
        DECLARE productos int;
        DECLARE ventas int;
        
        SELECT COUNT(*) INTO usuarios FROM usuario WHERE estatus != 10;
        SELECT COUNT(*) INTO clientes FROM cliente WHERE estatus != 10;
        SELECT COUNT(*) INTO proveedores FROM proveedor WHERE estatus != 10;
        SELECT COUNT(*) INTO productos FROM producto WHERE estatus != 10;
        SELECT COUNT(*) INTO ventas FROM factura WHERE  estatus != 10;
        
        SELECT usuarios, clientes, proveedores, productos, ventas;
        
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (IN `id_detalle` INT, IN `token` VARCHAR(50))  BEGIN 
    	DELETE FROM detalle_temp WHERE correlativo = id_detalle;
        
        SELECT tmp.correlativo,tmp.codproducto,p.descripcion,tmp.cantidad,tmp.precio_venta FROM detalle_temp tmp
		INNER JOIN producto p
        ON tmp.codproducto = p.codproducto
        WHERE tmp.token_user;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))  BEGIN
DECLARE factura INT;
DECLARE registros INT;
DECLARE total DECIMAL(10,2);
DECLARE nueva_existencia int;
DECLARE existencia_actual int;

DECLARE tmp_cod_producto int;
DECLARE tmp_cant_producto int;
DECLARE a int;
SET a = 1;

CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cod_prod BIGINT,
    cant_prod int);
SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
IF registros > 0 THEN
INSERT INTO tbl_tmp_tokenuser(cod_prod, cant_prod) SELECT codproducto, cantidad FROM detalle_temp WHERE token_user = token;
INSERT INTO factura (usuario,codcliente) VALUES (cod_usuario, cod_cliente);
SET factura = LAST_INSERT_ID();

INSERT INTO detallefactura(nofactura,codproducto,cantidad,precio_venta) SELECT (factura) AS nofactura, codproducto, cantidad,precio_venta FROM detalle_temp WHERE token_user = token;
WHILE a <= registros DO
	SELECT cod_prod, cant_prod INTO tmp_cod_producto,tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
    SELECT existencia INTO existencia_actual FROM producto WHERE codproducto = tmp_cod_producto;
    SET nueva_existencia = existencia_actual - tmp_cant_producto;
    UPDATE producto SET existencia = nueva_existencia WHERE codproducto = tmp_cod_producto;
    SET a=a+1;
END WHILE;
SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
UPDATE factura SET totalfactura = total WHERE nofactura = factura;
DELETE FROM detalle_temp WHERE token_user = token;
TRUNCATE TABLE tbl_tmp_tokenuser;
SELECT * FROM factura WHERE nofactura = factura;
ELSE
SELECT 0;
END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `ruc` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `dateadd` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `ruc`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`, `estatus`) VALUES
(1, 9999999999, 'Consumidor Final', 0999999999, 'Amazonas', '2022-06-17 22:34:20', 1, 1),
(2, 1234567894, 'Marilin Gallo', 0987562135, 'Av. 19 de Mayo', '2022-06-17 23:02:15', 6, 1),
(3, 0546789123, 'Julio martines', 0968574120, 'Amazonas', '2022-06-18 15:32:57', 1, 1),
(4, 1987456230, 'Alexa Torres', 0987451203, 'La Maná', '2022-06-18 16:49:33', 1, 1),
(5, 0504321829, 'Kevin Alexi Osorio Travez', 0959752902, 'La Maná', '2022-06-26 14:44:56', 1, 1),
(6, 0502350192, 'APEX MOVIL', 1234567895, 'Quito', '2022-06-26 17:35:56', 1, 1),
(7, 1250487506, 'Adriana Romero', 0985621755, 'Quito', '2022-06-26 17:44:19', 1, 1),
(8, 0502294813, 'Juana Travez Hinojosa', 0981134071, 'La Maná', '2022-07-01 00:50:42', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(10) NOT NULL,
  `cedula` bigint(13) UNSIGNED ZEROFILL NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `razon_social` varchar(100) CHARACTER SET latin1 NOT NULL,
  `telefono` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `direccion` text CHARACTER SET latin1 NOT NULL,
  `iva` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `cedula`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`) VALUES
(1, 1234567898001, 'Repuestos Electronicos Ivan', 'Venta de Repuestos electronicos', 0987451392, 'ejemplo@gmail.com', 'Amazonas, La Maná, Cotopaxi, Ecuador', '12.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) UNSIGNED ZEROFILL DEFAULT NULL,
  `codproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detallefactura`
--

INSERT INTO `detallefactura` (`correlativo`, `nofactura`, `codproducto`, `cantidad`, `precio_venta`) VALUES
(1, 00000000001, 4, 1, '1250.00'),
(2, 00000000001, 7, 10, '150.00'),
(3, 00000000001, 9, 1, '50.00'),
(4, 00000000002, 2, 1, '13.00'),
(5, 00000000002, 1, 1, '18.43'),
(6, 00000000002, 9, 1, '50.00'),
(7, 00000000003, 9, 10, '50.00'),
(8, 00000000003, 8, 5, '120.00'),
(9, 00000000004, 1, 1, '18.43'),
(10, 00000000005, 15, 1, '25.00'),
(11, 00000000005, 14, 1, '1500.00'),
(13, 00000000006, 13, 5, '3.67'),
(14, 00000000007, 13, 1, '3.67'),
(15, 00000000008, 5, 1, '1500.00'),
(16, 00000000009, 15, 1, '25.00'),
(17, 00000000009, 13, 1, '3.67'),
(19, 00000000010, 9, 1, '50.00'),
(20, 00000000011, 6, 1, '1300.00'),
(21, 00000000012, 13, 1, '3.67'),
(22, 00000000013, 13, 1, '3.67'),
(23, 00000000014, 3, 20, '13.19'),
(24, 00000000015, 15, 1, '25.00'),
(25, 00000000016, 13, 2, '3.67'),
(26, 00000000017, 15, 2, '25.00'),
(27, 00000000017, 13, 1, '3.67'),
(29, 00000000018, 10, 2, '2500.00'),
(30, 00000000019, 13, 3, '3.67'),
(31, 00000000020, 15, 2, '20.50'),
(32, 00000000021, 5, 1, '1500.00'),
(33, 00000000022, 6, 1, '1300.00'),
(34, 00000000023, 1, 1, '18.43'),
(35, 00000000023, 2, 2, '13.00'),
(37, 00000000024, 1, 10, '18.43'),
(38, 00000000025, 5, 1, '1500.00'),
(39, 00000000025, 4, 1, '1250.00'),
(41, 00000000026, 5, 2, '1500.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `codproducto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(1, 1, '2022-06-22 14:40:06', 24, '18.00', 1),
(2, 2, '2022-06-22 14:43:26', 36, '13.00', 1),
(3, 3, '2022-06-22 14:54:59', 24, '12.00', 1),
(4, 4, '2022-06-22 14:55:59', 50, '1250.00', 1),
(5, 5, '2022-06-22 14:57:19', 25, '1500.00', 1),
(6, 6, '2022-06-22 14:58:12', 50, '1300.00', 1),
(7, 7, '2022-06-22 15:30:43', 50, '150.00', 1),
(8, 8, '2022-06-22 15:31:07', 50, '120.00', 1),
(9, 9, '2022-06-22 15:32:24', 100, '50.00', 1),
(10, 10, '2022-06-22 15:33:03', 50, '2500.00', 1),
(11, 11, '2022-06-22 15:33:46', 100, '3000.00', 1),
(12, 12, '2022-06-22 15:35:47', 150, '3500.00', 1),
(13, 13, '2022-06-22 16:32:13', 20, '3.00', 1),
(14, 14, '2022-06-22 16:33:53', 50, '1500.00', 1),
(15, 15, '2022-06-22 16:38:50', 10, '25.00', 1),
(16, 13, '2022-06-23 18:50:33', 6, '5.00', 1),
(17, 13, '2022-06-23 20:18:07', 4, '5.00', 1),
(18, 15, '2022-06-23 20:21:08', 10, '25.00', 1),
(19, 11, '2022-06-23 20:24:56', 50, '3100.00', 1),
(20, 13, '2022-06-23 20:26:43', 10, '4.50', 1),
(21, 3, '2022-06-23 20:31:11', 12, '15.00', 1),
(22, 3, '2022-06-23 20:31:33', 1, '20.00', 1),
(23, 13, '2022-06-23 20:37:07', 10, '5.00', 1),
(24, 13, '2022-06-23 22:45:28', 10, '3.00', 1),
(25, 15, '2022-07-01 22:25:27', 2, '20.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) UNSIGNED ZEROFILL NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) DEFAULT NULL,
  `codcliente` int(11) DEFAULT NULL,
  `totalfactura` decimal(10,2) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`nofactura`, `fecha`, `usuario`, `codcliente`, `totalfactura`, `estatus`) VALUES
(00000000001, '2022-06-29 01:49:56', 1, 3, '2800.00', 1),
(00000000002, '2022-06-30 11:29:43', 1, 1, '81.43', 1),
(00000000003, '2022-06-30 11:37:35', 1, 3, '1100.00', 1),
(00000000004, '2022-06-30 21:49:51', 1, 5, '18.43', 1),
(00000000005, '2022-06-30 21:50:55', 1, 2, '1525.00', 1),
(00000000006, '2022-06-30 21:53:43', 1, 6, '18.35', 1),
(00000000007, '2022-06-30 21:55:27', 1, 2, '3.67', 1),
(00000000008, '2022-06-30 22:00:59', 1, 1, '1500.00', 1),
(00000000009, '2022-06-30 22:13:45', 1, 1, '28.67', 1),
(00000000010, '2022-06-30 22:24:59', 1, 6, '50.00', 1),
(00000000011, '2022-06-30 22:58:43', 1, 1, '1300.00', 1),
(00000000012, '2022-06-30 23:36:09', 1, 1, '3.67', 1),
(00000000013, '2022-06-30 23:36:35', 1, 1, '3.67', 1),
(00000000014, '2022-06-30 23:44:25', 1, 2, '263.80', 1),
(00000000015, '2022-06-30 23:45:30', 1, 6, '25.00', 1),
(00000000016, '2022-07-01 00:51:02', 1, 8, '7.34', 1),
(00000000017, '2022-07-01 22:06:25', 1, 2, '53.67', 1),
(00000000018, '2022-07-01 22:09:13', 1, 1, '5000.00', 1),
(00000000019, '2022-07-01 22:18:45', 1, 5, '11.01', 1),
(00000000020, '2022-07-01 22:51:44', 1, 6, '41.00', 1),
(00000000021, '2022-07-01 23:00:57', 1, 4, '1500.00', 1),
(00000000022, '2022-07-01 23:08:44', 1, 4, '1300.00', 1),
(00000000023, '2022-07-01 23:14:00', 1, 1, '44.43', 1),
(00000000024, '2022-07-03 13:44:48', 1, 1, '184.30', 1),
(00000000025, '2022-07-07 16:55:31', 1, 1, '2750.00', 1),
(00000000026, '2022-07-07 17:01:13', 1, 1, '3000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estatus`, `foto`) VALUES
(1, 'Wanda Base negro', 12, '18.43', 43, '2022-06-22 14:40:06', 1, 1, 'img_6cdc40494add2af48193dd94f93ad23a.jpg'),
(2, 'Wanda Base Rojo 1/2L', 12, '13.00', 33, '2022-06-22 14:43:26', 1, 1, 'img_a010a98fc0445656a4642af1905c64e7.jpg'),
(3, 'Wanda Base barnis', 12, '13.19', 17, '2022-06-22 14:54:59', 1, 1, 'img_3f8e105e2cf2a2a39321571973efe6ee.jpg'),
(4, 'Laptop hp 15 pulgadas ', 11, '1250.00', 48, '2022-06-22 14:55:59', 1, 1, 'img_ca7d2b120e2f0ea14cd03907f87aa30b.jpg'),
(5, 'Laptop 15 AMD 5 gen', 11, '1500.00', 20, '2022-06-22 14:57:19', 1, 1, 'img_36df9732c0c68087f35a4b61286b40bd.jpg'),
(6, 'Laptop hp i7 de 15 pulgadas', 11, '1300.00', 48, '2022-06-22 14:58:12', 1, 1, 'img_f144ddf514f3cdd230f20bea4b97d8f8.jpg'),
(7, 'Tela roja', 14, '150.00', 40, '2022-06-22 15:30:43', 1, 1, 'img_75e8cdc1f8cb64715e533c8b4398a75f.jpg'),
(8, 'Tela azul', 14, '120.00', 45, '2022-06-22 15:31:07', 1, 1, 'img_a0302c5dabbae362a7657f9aa3cf1c12.jpg'),
(9, 'tela por metro ', 14, '50.00', 87, '2022-06-22 15:32:24', 1, 1, 'img_d3d49698f37d963c7de34fbefc7041ed.jpg'),
(10, 'televisor lg de 50PG', 8, '2500.00', 48, '2022-06-22 15:33:03', 1, 1, 'img_ec208207de32a8a81a884f62ab18a810.jpg'),
(11, 'Televisor plasma de 70PG', 3, '3200.00', 150, '2022-06-22 15:33:46', 1, 1, 'img_bda6d4646035849e48e293f5d6a749c9.jpg'),
(12, 'Televisor plama samsung 90PG 4k full hd', 8, '3500.00', 150, '2022-06-22 15:35:47', 1, 1, 'img_6b04790ad34b2616072d57533bf648fc.jpg'),
(13, 'Coca Cola de 3L', 13, '3.67', 15, '2022-06-22 16:32:13', 1, 1, 'imgproducto.png'),
(14, 'Laptop DELL 15PG I7', 4, '1500.00', 49, '2022-06-22 16:33:53', 1, 1, 'img_021fa9f0cf7555264f106b66e8d17981.jpg'),
(15, 'Calculadora f85', 2, '20.50', 15, '2022-06-22 16:38:50', 1, 1, 'imgproducto.png');

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_Af_In` AFTER INSERT ON `producto` FOR EACH ROW BEGIN 
 	INSERT INTO entradas(codproducto,cantidad,precio,usuario_id)
    VALUES(new.codproducto,new.existencia,new.precio,new.usuario_id);
 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estatus`) VALUES
(1, 'BIC', 'Claudia Rosales', 0989877889, 'Ecuador', '2022-06-19 18:56:26', 1, 1),
(2, 'CASIO', 'Jorge Herrera', 0965656565, 'Calzada Las Flores', '2022-06-19 18:56:26', 1, 1),
(3, 'Omega', 'Julio Estrada', 0982877489, 'Avenida Elena Zona 4, Guatemala', '2022-06-19 18:56:26', 1, 1),
(4, 'Dell Compani', 'Roberto Estrada', 0947483647, 'Guatemala, Guatemala', '2022-06-19 18:56:26', 1, 1),
(5, 'Olimpia S.A', 'Elena Franco Morales', 0964535676, '5ta. Avenida Zona 4 Ciudad', '2022-06-19 18:56:26', 7, 1),
(6, 'Oster', 'Fernando Guerra', 0978987678, 'Calzada La Paz, Guatemala', '2022-06-19 18:56:26', 7, 1),
(7, 'ACELTECSA S.A', 'Ruben PÃ©rez', 0989879889, 'Colonia las Victorias', '2022-06-19 18:56:26', 1, 1),
(8, 'Sony', 'Julieta Contreras', 0989476787, 'Antigua Guatemala', '2022-06-19 18:56:26', 7, 1),
(9, 'VAIO', 'Felix Arnoldo Rojas', 0976378276, 'Avenida las Americas Zona 13', '2022-06-19 18:56:26', 1, 1),
(10, 'SUMAR', 'Oscar Maldonado', 0988376787, 'Colonia San Jose, Zona 5 Guatemala', '2022-06-19 18:56:26', 7, 1),
(11, 'HP', 'Angel Cardona', 0947483647, '5ta. calle zona 4 Guatemala', '2022-06-19 18:56:26', 1, 1),
(12, 'WANDA', 'Angel Minda', 0987451203, 'La Maná', '2022-06-20 00:30:22', 7, 1),
(13, 'COCA COLA', 'Marco Dejanon', 0968457120, 'Quito', '2022-06-20 01:24:43', 7, 1),
(14, 'Neymatex', 'Susana Reyes', 0987451203, 'Guayaquil', '2022-06-21 23:36:02', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estatus`) VALUES
(1, 'ALX OS', 'admin@admin.com', 'ADMIN', 'f6fdffe48c908deb0f4c3bd36c032e72', 1, 1),
(2, 'Alx Osorio', 'alxos@gmail.com', 'ALEXISS', '827ccb0eea8a706c4c34a16891f84e7b', 3, 1),
(3, 'Juan Vaigor H', 'juanH.122@gmail.com', 'juanitoS', '827ccb0eea8a706c4c34a16891f84e7b', 3, 1),
(6, 'alexa', 'info@gmail.com', 'HOLA', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(7, 'Alex', 'alex@gmail.com', 'ALEX', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(8, 'Aylin ', 'ailin122@gmail.com', 'AYLIN', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(9, 'Maria Toaquisa', 'mt20@gmail.com', 'maria T', '827ccb0eea8a706c4c34a16891f84e7b', 3, 1),
(10, 'Kevin Alexi Osorio Travez', 'kevin.osorio1829@utc.edu.ec', 'KAOT', 'e123a8a4c5b24d904dfaa58478bb3b28', 2, 1),
(11, 'Erick Iza', 'erick.iza@gmail.com', 'Iza', '827ccb0eea8a706c4c34a16891f84e7b', 3, 1),
(12, 'Clara Maria', 'clar542@gmail.com', 'ClaraM', '01cfcd4f6b8770febfb40cb906715822', 2, 1),
(13, 'Gabriel Osorio', 'gb.@gmail.com', 'Gabriel', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1),
(14, 'Marco', 'mk.@gmail.com', 'Marco S', '827ccb0eea8a706c4c34a16891f84e7b', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `nofactura` (`token_user`),
  ADD KEY `codproducto` (`codproducto`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `codcliente` (`codcliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallefactura_ibfk_3` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `cliente` (`idcliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`codproveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
