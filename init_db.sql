-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 15 août 2019 à 05:28
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `slimappmvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(11) NOT NULL,
  `upload_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `height`, `width`, `size`, `type`, `upload_date`, `user_id`) VALUES
(1, 525, 800, 532878, 'image/jpeg', '2019-08-15', 1);

-- --------------------------------------------------------

--
-- Structure de la table `markers`
--

DROP TABLE IF EXISTS `markers`;
CREATE TABLE IF NOT EXISTS `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  `altitude` float DEFAULT NULL,
  `upload_date` datetime NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lng`, `lat`, `altitude`, `upload_date`, `type`) VALUES
(22, 'TestImage', 'placeholder', -3.37521, 47.7069, 82.81, '2019-08-01 07:51:27', 'jpeg'),
(30, 'Scotland', 'placeholder', -6.18284, 57.4577, 177.1, '2019-08-01 21:39:23', 'jpeg'),
(15, 'Quiberon', 'placeholder', -3.14242, 47.4902, 17.935, '2019-07-19 04:15:45', 'jpeg'),
(32, 'TestImage', 'placeholder', -3.37521, 47.7069, 82.81, '2019-08-15 07:26:34', 'jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `name`, `password`, `ip_address`, `date`) VALUES
(1, 'Placeholder', 'aa', NULL, '2019-06-22 02:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `name`, `content`) VALUES
(1, 'Article 1', 'Lorem Ipsum Content 1'),
(2, 'Article 2', 'Au revoir'),
(3, 'Bonjour', 'Lorem Ipsum'),
(48, 'Content', 'test'),
(49, 'Test', 'test'),
(47, 'Validation', 'content'),
(42, 'Alexandre', 'content'),
(43, 'Alexandre', 'content'),
(45, 'Alexandre', 'test'),
(46, 'Alexandre', 'test'),
(50, 'name', 'test'),
(51, 'test', 'test'),
(52, 'Test', 'content'),
(53, 'Test donnÃ©e', 'Contenus donnÃ©es'),
(54, 'Test donnÃ©e', 'Contenus donnÃ©es');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
