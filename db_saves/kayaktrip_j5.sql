-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 15, 2025 at 05:43 PM
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
-- Database: `kayaktrip_j`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `id` int(11) NOT NULL,
  `stop_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `base_price_per_night` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accommodations`
--

INSERT INTO `accommodations` (`id`, `stop_id`, `name`, `description`, `base_price_per_night`) VALUES
(1, 1, 'Hébergement 1 - Le Puy-en-Velay', 'Hébergement confortable situé à Le Puy-en-Velay.', 90.35),
(2, 1, 'Hébergement 2 - Le Puy-en-Velay', 'Hébergement confortable situé à Le Puy-en-Velay.', 93.34),
(3, 2, 'Hébergement 1 - Roanne', 'Hébergement confortable situé à Roanne.', 86.49),
(4, 2, 'Hébergement 2 - Roanne', 'Hébergement confortable situé à Roanne.', 96.35),
(5, 3, 'Hébergement 1 - Feurs', 'Hébergement confortable situé à Feurs.', 66.86),
(6, 3, 'Hébergement 2 - Feurs', 'Hébergement confortable situé à Feurs.', 51.07),
(7, 4, 'Hébergement 1 - Saint-Étienne', 'Hébergement confortable situé à Saint-Étienne.', 116.45),
(8, 4, 'Hébergement 2 - Saint-Étienne', 'Hébergement confortable situé à Saint-Étienne.', 67.13),
(9, 5, 'Hébergement 1 - Nevers', 'Hébergement confortable situé à Nevers.', 66.13),
(10, 5, 'Hébergement 2 - Nevers', 'Hébergement confortable situé à Nevers.', 70.22),
(11, 6, 'Hébergement 1 - La Charité-sur-Loire', 'Hébergement confortable situé à La Charité-sur-Loire.', 58.98),
(12, 6, 'Hébergement 2 - La Charité-sur-Loire', 'Hébergement confortable situé à La Charité-sur-Loire.', 62.76),
(13, 7, 'Hébergement 1 - Cosne-Cours-sur-Loire', 'Hébergement confortable situé à Cosne-Cours-sur-Loire.', 108.69),
(14, 7, 'Hébergement 2 - Cosne-Cours-sur-Loire', 'Hébergement confortable situé à Cosne-Cours-sur-Loire.', 114.37),
(15, 8, 'Hébergement 1 - Gien', 'Hébergement confortable situé à Gien.', 58.10),
(16, 8, 'Hébergement 2 - Gien', 'Hébergement confortable situé à Gien.', 111.55),
(17, 9, 'Hébergement 1 - Briare', 'Hébergement confortable situé à Briare.', 75.29),
(18, 9, 'Hébergement 2 - Briare', 'Hébergement confortable situé à Briare.', 102.01),
(19, 10, 'Hébergement 1 - Orléans', 'Hébergement confortable situé à Orléans.', 77.48),
(20, 10, 'Hébergement 2 - Orléans', 'Hébergement confortable situé à Orléans.', 50.11),
(21, 11, 'Hébergement 1 - Blois', 'Hébergement confortable situé à Blois.', 60.54),
(22, 11, 'Hébergement 2 - Blois', 'Hébergement confortable situé à Blois.', 70.27),
(23, 12, 'Hébergement 1 - Amboise', 'Hébergement confortable situé à Amboise.', 117.14),
(24, 12, 'Hébergement 2 - Amboise', 'Hébergement confortable situé à Amboise.', 98.79),
(25, 13, 'Hébergement 1 - Tours', 'Hébergement confortable situé à Tours.', 114.06),
(26, 13, 'Hébergement 2 - Tours', 'Hébergement confortable situé à Tours.', 71.00),
(27, 14, 'Hébergement 1 - Chinon', 'Hébergement confortable situé à Chinon.', 73.49),
(28, 14, 'Hébergement 2 - Chinon', 'Hébergement confortable situé à Chinon.', 70.19),
(29, 15, 'Hébergement 1 - Saumur', 'Hébergement confortable situé à Saumur.', 73.05),
(30, 15, 'Hébergement 2 - Saumur', 'Hébergement confortable situé à Saumur.', 84.94),
(31, 16, 'Hébergement 1 - Angers', 'Hébergement confortable situé à Angers.', 79.13),
(32, 16, 'Hébergement 2 - Angers', 'Hébergement confortable situé à Angers.', 115.65),
(33, 17, 'Hébergement 1 - Ancenis', 'Hébergement confortable situé à Ancenis.', 106.66),
(34, 17, 'Hébergement 2 - Ancenis', 'Hébergement confortable situé à Ancenis.', 94.10),
(35, 18, 'Hébergement 1 - Nantes', 'Hébergement confortable situé à Nantes.', 53.00),
(36, 18, 'Hébergement 2 - Nantes', 'Hébergement confortable situé à Nantes.', 84.06),
(37, 19, 'Hébergement 1 - Saint-Nazaire', 'Hébergement confortable situé à Saint-Nazaire.', 63.32),
(38, 19, 'Hébergement 2 - Saint-Nazaire', 'Hébergement confortable situé à Saint-Nazaire.', 64.65),
(39, 20, 'Hébergement 1 - Montoir-de-Bretagne', 'Hébergement confortable situé à Montoir-de-Bretagne.', 115.49),
(40, 20, 'Hébergement 2 - Montoir-de-Bretagne', 'Hébergement confortable situé à Montoir-de-Bretagne.', 101.88),
(41, 21, 'Hébergement 1 - Decize', 'Hébergement confortable situé à Decize.', 105.36),
(42, 21, 'Hébergement 2 - Decize', 'Hébergement confortable situé à Decize.', 70.15);

-- --------------------------------------------------------

--
-- Table structure for table `accommodation_availability`
--

CREATE TABLE `accommodation_availability` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `price_override` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accommodation_closures`
--

CREATE TABLE `accommodation_closures` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `api_key` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `user_id`, `api_key`) VALUES
(1, 1, 'a425a93ea2c71d17d8bf64df39f52c19a8ef1ad458c5372f6cfcf3d62beb666e');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `total_price` decimal(10,2) DEFAULT NULL,
  `promotion_code_used` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_accommodations`
--

CREATE TABLE `booking_accommodations` (
  `booking_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `people_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `people_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_stops`
--

CREATE TABLE `booking_stops` (
  `booking_id` int(11) NOT NULL,
  `stop_id` int(11) NOT NULL,
  `arrival_date` date DEFAULT NULL,
  `departure_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `sender_type` enum('client','admin') DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_threads`
--

CREATE TABLE `chat_threads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `is_closed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profiles`
--

CREATE TABLE `customer_profiles` (
  `user_id` int(11) NOT NULL,
  `address` text DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `first_booking_code_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packs`
--

CREATE TABLE `packs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `packs`
--

INSERT INTO `packs` (`id`, `name`, `duration`, `description`, `price`) VALUES
(1, 'Pack D&eacute;couverte', 3, 'Pack d&eacute;di&eacute; &agrave; la d&eacute;couverte du kayak et de la Loire', 18.90),
(2, 'Pack Confirm&eacute;', 5, 'Une aventure sportive pour les kayakiste d&eacute;termin&eacute;s !', 24.90);

-- --------------------------------------------------------

--
-- Table structure for table `pack_templates`
--

CREATE TABLE `pack_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `available_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pack_template_stops`
--

CREATE TABLE `pack_template_stops` (
  `pack_template_id` int(11) NOT NULL,
  `stop_id` int(11) NOT NULL,
  `order_in_pack` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_value` tinyint(4) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `first_time_only` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`code`, `description`, `discount_value`, `valid_from`, `valid_to`, `first_time_only`) VALUES
('KAYAKTROPBIEN', 'Promotion disponible à la première commande', 15, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `room_name` varchar(100) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `accommodation_id`, `room_name`, `capacity`, `base_price`) VALUES
(1, 1, 'Chambre 1 - Hébergement 1 - Le Puy-en-Velay', 2, 76.93),
(2, 1, 'Chambre 2 - Hébergement 1 - Le Puy-en-Velay', 4, 82.15),
(3, 2, 'Chambre 1 - Hébergement 2 - Le Puy-en-Velay', 3, 70.84),
(4, 2, 'Chambre 2 - Hébergement 2 - Le Puy-en-Velay', 4, 45.87),
(5, 3, 'Chambre 1 - Hébergement 1 - Roanne', 2, 44.11),
(6, 3, 'Chambre 2 - Hébergement 1 - Roanne', 4, 44.13),
(7, 4, 'Chambre 1 - Hébergement 2 - Roanne', 4, 56.78),
(8, 4, 'Chambre 2 - Hébergement 2 - Roanne', 3, 76.06),
(9, 5, 'Chambre 1 - Hébergement 1 - Feurs', 4, 53.95),
(10, 5, 'Chambre 2 - Hébergement 1 - Feurs', 2, 55.24),
(11, 6, 'Chambre 1 - Hébergement 2 - Feurs', 3, 46.11),
(12, 6, 'Chambre 2 - Hébergement 2 - Feurs', 4, 49.24),
(13, 7, 'Chambre 1 - Hébergement 1 - Saint-Étienne', 3, 81.33),
(14, 7, 'Chambre 2 - Hébergement 1 - Saint-Étienne', 3, 64.37),
(15, 8, 'Chambre 1 - Hébergement 2 - Saint-Étienne', 2, 43.35),
(16, 8, 'Chambre 2 - Hébergement 2 - Saint-Étienne', 3, 43.96),
(17, 9, 'Chambre 1 - Hébergement 1 - Nevers', 2, 57.54),
(18, 9, 'Chambre 2 - Hébergement 1 - Nevers', 2, 62.21),
(19, 10, 'Chambre 1 - Hébergement 2 - Nevers', 2, 48.45),
(20, 10, 'Chambre 2 - Hébergement 2 - Nevers', 2, 64.83),
(21, 11, 'Chambre 1 - Hébergement 1 - La Charité-sur-Loire', 3, 55.94),
(22, 11, 'Chambre 2 - Hébergement 1 - La Charité-sur-Loire', 2, 58.78),
(23, 12, 'Chambre 1 - Hébergement 2 - La Charité-sur-Loire', 2, 57.58),
(24, 12, 'Chambre 2 - Hébergement 2 - La Charité-sur-Loire', 3, 52.98),
(25, 13, 'Chambre 1 - Hébergement 1 - Cosne-Cours-sur-Loire', 3, 86.57),
(26, 13, 'Chambre 2 - Hébergement 1 - Cosne-Cours-sur-Loire', 2, 102.70),
(27, 14, 'Chambre 1 - Hébergement 2 - Cosne-Cours-sur-Loire', 2, 113.79),
(28, 14, 'Chambre 2 - Hébergement 2 - Cosne-Cours-sur-Loire', 2, 94.82),
(29, 15, 'Chambre 1 - Hébergement 1 - Gien', 2, 51.79),
(30, 15, 'Chambre 2 - Hébergement 1 - Gien', 4, 47.72),
(31, 16, 'Chambre 1 - Hébergement 2 - Gien', 2, 68.12),
(32, 16, 'Chambre 2 - Hébergement 2 - Gien', 2, 40.78),
(33, 17, 'Chambre 1 - Hébergement 1 - Briare', 2, 52.03),
(34, 17, 'Chambre 2 - Hébergement 1 - Briare', 2, 71.76),
(35, 18, 'Chambre 1 - Hébergement 2 - Briare', 4, 100.89),
(36, 18, 'Chambre 2 - Hébergement 2 - Briare', 4, 72.30),
(37, 19, 'Chambre 1 - Hébergement 1 - Orléans', 2, 64.16),
(38, 19, 'Chambre 2 - Hébergement 1 - Orléans', 2, 64.43),
(39, 20, 'Chambre 1 - Hébergement 2 - Orléans', 2, 41.95),
(40, 20, 'Chambre 2 - Hébergement 2 - Orléans', 3, 49.73),
(41, 21, 'Chambre 1 - Hébergement 1 - Blois', 4, 44.62),
(42, 21, 'Chambre 2 - Hébergement 1 - Blois', 3, 54.75),
(43, 22, 'Chambre 1 - Hébergement 2 - Blois', 2, 62.52),
(44, 22, 'Chambre 2 - Hébergement 2 - Blois', 2, 53.35),
(45, 23, 'Chambre 1 - Hébergement 1 - Amboise', 3, 106.07),
(46, 23, 'Chambre 2 - Hébergement 1 - Amboise', 2, 72.12),
(47, 24, 'Chambre 1 - Hébergement 2 - Amboise', 4, 62.00),
(48, 24, 'Chambre 2 - Hébergement 2 - Amboise', 2, 89.50),
(49, 25, 'Chambre 1 - Hébergement 1 - Tours', 3, 86.52),
(50, 25, 'Chambre 2 - Hébergement 1 - Tours', 3, 70.62),
(51, 26, 'Chambre 1 - Hébergement 2 - Tours', 4, 62.04),
(52, 26, 'Chambre 2 - Hébergement 2 - Tours', 2, 58.69),
(53, 27, 'Chambre 1 - Hébergement 1 - Chinon', 4, 71.37),
(54, 27, 'Chambre 2 - Hébergement 1 - Chinon', 2, 61.75),
(55, 28, 'Chambre 1 - Hébergement 2 - Chinon', 4, 49.42),
(56, 28, 'Chambre 2 - Hébergement 2 - Chinon', 4, 58.46),
(57, 29, 'Chambre 1 - Hébergement 1 - Saumur', 2, 44.25),
(58, 29, 'Chambre 2 - Hébergement 1 - Saumur', 4, 54.18),
(59, 30, 'Chambre 1 - Hébergement 2 - Saumur', 3, 73.91),
(60, 30, 'Chambre 2 - Hébergement 2 - Saumur', 2, 82.93),
(61, 31, 'Chambre 1 - Hébergement 1 - Angers', 4, 41.91),
(62, 31, 'Chambre 2 - Hébergement 1 - Angers', 4, 76.72),
(63, 32, 'Chambre 1 - Hébergement 2 - Angers', 4, 53.49),
(64, 32, 'Chambre 2 - Hébergement 2 - Angers', 4, 102.26),
(65, 33, 'Chambre 1 - Hébergement 1 - Ancenis', 3, 60.33),
(66, 33, 'Chambre 2 - Hébergement 1 - Ancenis', 2, 40.70),
(67, 34, 'Chambre 1 - Hébergement 2 - Ancenis', 3, 51.34),
(68, 34, 'Chambre 2 - Hébergement 2 - Ancenis', 3, 43.91),
(69, 35, 'Chambre 1 - Hébergement 1 - Nantes', 3, 43.15),
(70, 35, 'Chambre 2 - Hébergement 1 - Nantes', 2, 42.46),
(71, 36, 'Chambre 1 - Hébergement 2 - Nantes', 3, 41.66),
(72, 36, 'Chambre 2 - Hébergement 2 - Nantes', 2, 67.49),
(73, 37, 'Chambre 1 - Hébergement 1 - Saint-Nazaire', 2, 43.63),
(74, 37, 'Chambre 2 - Hébergement 1 - Saint-Nazaire', 4, 56.61),
(75, 38, 'Chambre 1 - Hébergement 2 - Saint-Nazaire', 4, 58.58),
(76, 38, 'Chambre 2 - Hébergement 2 - Saint-Nazaire', 4, 56.28),
(77, 39, 'Chambre 1 - Hébergement 1 - Montoir-de-Bretagne', 4, 68.61),
(78, 39, 'Chambre 2 - Hébergement 1 - Montoir-de-Bretagne', 2, 60.28),
(79, 40, 'Chambre 1 - Hébergement 2 - Montoir-de-Bretagne', 3, 89.76),
(80, 40, 'Chambre 2 - Hébergement 2 - Montoir-de-Bretagne', 4, 96.04),
(81, 41, 'Chambre 1 - Hébergement 1 - Decize', 2, 101.85),
(82, 41, 'Chambre 2 - Hébergement 1 - Decize', 3, 69.70),
(83, 42, 'Chambre 1 - Hébergement 2 - Decize', 2, 50.01),
(84, 42, 'Chambre 2 - Hébergement 2 - Decize', 3, 46.70);

-- --------------------------------------------------------

--
-- Table structure for table `room_availability`
--

CREATE TABLE `room_availability` (
  `room_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `price_override` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route_stops`
--

CREATE TABLE `route_stops` (
  `route_id` int(11) NOT NULL,
  `stop_id` int(11) NOT NULL,
  `order_in_route` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seasonal_prices`
--

CREATE TABLE `seasonal_prices` (
  `id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `multiplier` decimal(5,2) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 'Paniers-repas (&agrave; l&#039;unit&eacute;)', 'Panier garni pour le voyage', 10.99, 1),
(2, 'Transport de bagages (Prix/kg)', 'Transport des bagages par la compagnie', 5.00, 1),
(3, 'Location de tente (&agrave; l&#039;unit&eacute;)', 'Permet le campements aux points d&#039;arr&ecirc;ts d&eacute;di&eacute;s (2 places par tente)', 7.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

CREATE TABLE `stops` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `stops`
--

INSERT INTO `stops` (`id`, `name`, `description`, `latitude`, `longitude`) VALUES
(1, 'Le Puy-en-Velay', 'etape', 45.0448, 3.885),
(2, 'Roanne', 'etape', 46.0333, 4.0667),
(3, 'Feurs', 'etape', 45.7434, 4.2249),
(4, 'Saint-Étienne', 'etape', 45.4397, 4.3872),
(5, 'Nevers', 'etape', 46.9896, 3.159),
(6, 'La Charité-sur-Loire', 'etape', 47.177, 3.0207),
(7, 'Cosne-Cours-sur-Loire', 'etape', 47.4097, 2.9205),
(8, 'Gien', 'etape', 47.6853, 2.6333),
(9, 'Briare', 'etape', 47.6396, 2.7391),
(10, 'Orléans', 'etape', 47.9029, 1.9093),
(11, 'Blois', 'etape', 47.5861, 1.3359),
(12, 'Amboise', 'etape', 47.4136, 0.9828),
(13, 'Tours', 'etape', 47.3941, 0.6848),
(14, 'Chinon', 'etape', 47.1667, 0.2333),
(15, 'Saumur', 'etape', 47.2597, -0.0783),
(16, 'Angers', 'etape', 47.4784, -0.5632),
(17, 'Ancenis', 'etape', 47.3647, -1.1742),
(18, 'Nantes', 'etape', 47.2184, -1.5536),
(19, 'Saint-Nazaire', 'etape', 47.2806, -2.2081),
(20, 'Montoir-de-Bretagne', 'etape', 47.3272, -2.1558),
(21, 'Decize', 'etape', 46.828348453428, 3.4576034545898);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_online` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `created_at`, `is_admin`, `is_online`) VALUES
(1, 'test@test.fr', '$2y$10$ogmt6rAS3ioksDJsTjB3xeMGSqalMwVpjz4bQul9KMXa3Ka8Fe6N2', 'Jensen', 'Tahi', '0600000000', '2025-07-29 23:09:27', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stop_id` (`stop_id`);

--
-- Indexes for table `accommodation_availability`
--
ALTER TABLE `accommodation_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation_id` (`accommodation_id`);

--
-- Indexes for table `accommodation_closures`
--
ALTER TABLE `accommodation_closures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation_id` (`accommodation_id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `promotion_code_used` (`promotion_code_used`);

--
-- Indexes for table `booking_accommodations`
--
ALTER TABLE `booking_accommodations`
  ADD PRIMARY KEY (`booking_id`,`accommodation_id`,`date`),
  ADD KEY `accommodation_id` (`accommodation_id`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`booking_id`,`room_id`,`date`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`booking_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `booking_stops`
--
ALTER TABLE `booking_stops`
  ADD PRIMARY KEY (`booking_id`,`stop_id`),
  ADD KEY `stop_id` (`stop_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`);

--
-- Indexes for table `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `packs`
--
ALTER TABLE `packs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pack_templates`
--
ALTER TABLE `pack_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pack_template_stops`
--
ALTER TABLE `pack_template_stops`
  ADD PRIMARY KEY (`pack_template_id`,`stop_id`),
  ADD KEY `stop_id` (`stop_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation_id` (`accommodation_id`);

--
-- Indexes for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD PRIMARY KEY (`room_id`,`date`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `route_stops`
--
ALTER TABLE `route_stops`
  ADD PRIMARY KEY (`route_id`,`stop_id`),
  ADD KEY `stop_id` (`stop_id`);

--
-- Indexes for table `seasonal_prices`
--
ALTER TABLE `seasonal_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `accommodation_availability`
--
ALTER TABLE `accommodation_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accommodation_closures`
--
ALTER TABLE `accommodation_closures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_threads`
--
ALTER TABLE `chat_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packs`
--
ALTER TABLE `packs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pack_templates`
--
ALTER TABLE `pack_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seasonal_prices`
--
ALTER TABLE `seasonal_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stops`
--
ALTER TABLE `stops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD CONSTRAINT `accommodations_ibfk_1` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accommodation_availability`
--
ALTER TABLE `accommodation_availability`
  ADD CONSTRAINT `accommodation_availability_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accommodation_closures`
--
ALTER TABLE `accommodation_closures`
  ADD CONSTRAINT `accommodation_closures_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`promotion_code_used`) REFERENCES `promotions` (`code`);

--
-- Constraints for table `booking_accommodations`
--
ALTER TABLE `booking_accommodations`
  ADD CONSTRAINT `booking_accommodations_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_accommodations_ibfk_2` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`);

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `booking_stops`
--
ALTER TABLE `booking_stops`
  ADD CONSTRAINT `booking_stops_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_stops_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `chat_threads` (`id`);

--
-- Constraints for table `chat_threads`
--
ALTER TABLE `chat_threads`
  ADD CONSTRAINT `chat_threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD CONSTRAINT `customer_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pack_template_stops`
--
ALTER TABLE `pack_template_stops`
  ADD CONSTRAINT `pack_template_stops_ibfk_1` FOREIGN KEY (`pack_template_id`) REFERENCES `pack_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pack_template_stops_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`id`);

--
-- Constraints for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD CONSTRAINT `room_availability_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `route_stops`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `route_stops`
--
ALTER TABLE `route_stops`
  ADD CONSTRAINT `route_stops_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `route_stops_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
