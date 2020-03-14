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

-- Export de la structure de la base pour forum
DROP DATABASE IF EXISTS `forum`;
CREATE DATABASE IF NOT EXISTS `forum` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `forum`;


-- Export de la structure de table forum. commentaire
DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `contenu` text,
  `ajoute` datetime DEFAULT NULL,
  `t_id` smallint(5) unsigned DEFAULT NULL,
  `u_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_id` (`t_id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Export de données de la table forum.commentaire : 0 rows
DELETE FROM `commentaire`;
/*!40000 ALTER TABLE `commentaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentaire` ENABLE KEYS */;


-- Export de la structure de table forum. jeu
DROP TABLE IF EXISTS `jeu`;
CREATE TABLE IF NOT EXISTS `jeu` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `plateforme` varchar(10) DEFAULT NULL,
  `developpeur` varchar(50) DEFAULT NULL,
  `date_de_sortie` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Export de données de la table forum.jeu : 2 rows
DELETE FROM `jeu`;
/*!40000 ALTER TABLE `jeu` DISABLE KEYS */;
INSERT INTO `jeu` (`id`, `nom`, `image`, `description`, `plateforme`, `developpeur`, `date_de_sortie`) VALUES
	(1, 'League of Legends', 'lol.jpg', 'League of Legends (abrégé LoL, anciennement nommé League of Legends: Clash of Fates) est un jeu vidéo de type arène de bataille en ligne (MOBA) gratuit développé et édité par Riot Games sur Windows1 et Mac OS X. Fin janvier 2013, un nouveau client bêta pour Mac a été distribué par Riot Games2. Le jeu a été évoqué pour la première fois le 8 octobre 2008 et est entré en phase bêta le 10 avril 20093.', 'PC', 'Riot Games', '2009-10-27'),
	(2, 'PlayerUnknown\'s Battlegrounds', 'pubg.jpg', 'PlayerUnknown\'s Battleground (PUBG) est un jeu vidéo multijoueur en ligne de type battle royale développé et édité par Bluehole. Il est disponible en accès anticipé sur Microsoft Windows à partir du 23 mars 2017. La version finale du jeu est prévue pour fin 2017 sur Windows et Xbox One et sortira 12 décembre 2017', 'PC / XBOX ', 'Bluehole', '2017-03-23');
/*!40000 ALTER TABLE `jeu` ENABLE KEYS */;


-- Export de la structure de table forum. sujet
DROP TABLE IF EXISTS `sujet`;
CREATE TABLE IF NOT EXISTS `sujet` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) DEFAULT NULL,
  `description` text,
  `cat_id` smallint(5) unsigned DEFAULT NULL,
  `u_id` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Export de données de la table forum.sujet : 0 rows
DELETE FROM `sujet`;
/*!40000 ALTER TABLE `sujet` DISABLE KEYS */;
/*!40000 ALTER TABLE `sujet` ENABLE KEYS */;


-- Export de la structure de table forum. utilisateur
DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('Administrateur','Utilisateur','Modérateur') DEFAULT 'Utilisateur',
  `description` text,
  `avatar` varchar(255) DEFAULT 'default.png',
  `inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `banned` varchar(5) DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Export de données de la table forum.utilisateur : 1 rows
DELETE FROM `utilisateur`;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` (`id`, `login`, `password`, `email`, `status`, `description`, `avatar`, `inscription`, `banned`) VALUES
	(4, 'admin', '$2y$10$cW0V5q11OvYPC8nR66m5pO2s9VxPukJnnyVN6ABb8SqzrRy3Uy3rS', 'admin@admin.adm', 'Administrateur', NULL, 'default.png', '2017-12-05 14:51:03', '0');
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
