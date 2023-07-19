-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 19 juil. 2023 à 07:11
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jellyfish_database`
--

-- --------------------------------------------------------

--
-- Structure de la table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `currency` varchar(50) DEFAULT NULL,
  `limit` decimal(10,2) DEFAULT NULL,
  `type` enum('above','below') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `alerts`
--

INSERT INTO `alerts` (`id`, `user_id`, `currency`, `limit`, `type`, `created_at`, `updated_at`) VALUES
(14, 18, 'USD', '5000.00', 'below', '2023-07-19 07:07:15', '2023-07-19 07:07:15'),
(16, 19, 'USD', '5000.00', 'above', '2023-07-19 07:09:45', '2023-07-19 07:09:45'),
(15, 19, 'EUR', '3500.00', 'above', '2023-07-19 07:09:32', '2023-07-19 07:09:32'),
(17, 19, 'JAN', '1500.00', 'below', '2023-07-19 07:09:55', '2023-07-19 07:09:55');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pic` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `pic`) VALUES
(18, 'sam', 'sample@gmail.com', '$2y$11$2193fd0c8e3d279fc191fuGeESRGJ9F7zF.oRnAXBNR13nAA0Pvhe', NULL),
(19, 'jd', 'john@gmail.com', '$2y$11$4ab4dda8a04f84c335d3auLKjqpDGMccA2vD9TtVkRpFlToci8UWW', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
