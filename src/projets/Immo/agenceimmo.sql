-- --------------------------------------------------------
-- Hôte :                        debian.loc
-- Version du serveur:           10.1.26-MariaDB-0+deb9u1 - Debian 9.1
-- SE du serveur:                debian-linux-gnu
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de la base pour agenceimmo
DROP DATABASE IF EXISTS `agenceimmo`;
CREATE DATABASE IF NOT EXISTS `agenceimmo` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `agenceimmo`;


-- Export de la structure de table agenceimmo. adverts
DROP TABLE IF EXISTS `adverts`;
CREATE TABLE IF NOT EXISTS `adverts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `zip_code` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `bedroom` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `description` text,
  `advert_type` enum('location','achat') DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Export de données de la table agenceimmo.adverts : 1 rows
DELETE FROM `adverts`;
/*!40000 ALTER TABLE `adverts` DISABLE KEYS */;
INSERT INTO `adverts` (`id`, `title`, `ville`, `zip_code`, `price`, `room`, `bedroom`, `area`, `description`, `advert_type`, `sale_date`, `user_id`) VALUES
	(9, 'deez', 'fzefz', '56', 6, 5, 6, 7, 'ege', 'location', '2017-04-05', 5);
/*!40000 ALTER TABLE `adverts` ENABLE KEYS */;


-- Export de la structure de table agenceimmo. messages
DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `object` text,
  `sended` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Export de données de la table agenceimmo.messages : 1 rows
DELETE FROM `messages`;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`id`, `receiver_id`, `sender_id`, `object`, `sended`) VALUES
	(4, 5, 7, 'Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou Coucou ', '2018-01-08 15:28:28');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;


-- Export de la structure de table agenceimmo. picture
DROP TABLE IF EXISTS `picture`;
CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `id_ad` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- Export de données de la table agenceimmo.picture : ~1 rows (environ)
DELETE FROM `picture`;
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` (`id`, `name`, `id_ad`) VALUES
	(28, 'image5a56207e1a3d69.jpg', 9),
	(29, 'image5a56207e1bc9c9.jpg', 9),
	(30, 'image5a56207e1d8c49.jpg', 9),
	(31, 'image5a5620c9d931b9.jpg', 9);
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;


-- Export de la structure de table agenceimmo. users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('admin','client','vendeur') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Export de données de la table agenceimmo.users : 3 rows
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `pseudo`, `password`, `email`, `status`) VALUES
	(5, 'Admin', '$2y$10$k4J4K9kpL3BhwxwyCLQ6XOZJ1QnB1rbK6gBBlVlEwlR02uu/.zZKK', 'admin@admin.fr', 'admin'),
	(6, 'Jean', '$2y$10$bCfseY.SdUp7Qsk6cVOGeubzgqrZcT7Odz.XgAHaVgYD2fW5MmWm.', 'jean@gmail.com', 'client'),
	(7, 'Bernard', '$2y$10$4UKyKgPO7HOEuak0W6I1E.MPEBTMcFbckWZoA0lAQfbT4Bpt2hRo6', 'bernard@gmail.com', 'vendeur');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
