-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 10 fév. 2020 à 02:38
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `p5_libre`
--

-- --------------------------------------------------------

--
-- Structure de la table `avatars`
--

DROP TABLE IF EXISTS `avatars`;
CREATE TABLE IF NOT EXISTS `avatars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `avatar_file` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` tinyint(4) NOT NULL,
  `content` varchar(255) NOT NULL,
  `com_date` datetime NOT NULL,
  `reported` int(11) NOT NULL DEFAULT '0',
  `moderated` int(11) NOT NULL DEFAULT '0',
  `post_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE IF NOT EXISTS `friendship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friend_a` tinyint(4) NOT NULL,
  `friend_b` tinyint(4) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'friend',
  `ignored` tinyint(1) NOT NULL DEFAULT '0',
  `friend_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_friendship_to_members` (`friend_b`),
  KEY `fk_friendship_to_members2` (`friend_a`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `height` int(11) NOT NULL DEFAULT '0',
  `width` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  `type` varchar(11) NOT NULL DEFAULT 'placeholder',
  `upload_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `groupimg_id` int(11) NOT NULL DEFAULT '0',
  `privacy` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_img` (`groupimg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `images_groups`
--

DROP TABLE IF EXISTS `images_groups`;
CREATE TABLE IF NOT EXISTS `images_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `post_content` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `mod_type` varchar(255) NOT NULL DEFAULT 'none',
  `mod_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `logs_com`
--

DROP TABLE IF EXISTS `logs_com`;
CREATE TABLE IF NOT EXISTS `logs_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `com_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `mod_type` varchar(255) NOT NULL,
  `mod_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_image_to_markers` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT 'email@default.com',
  `custom_avatar` tinyint(4) NOT NULL DEFAULT '0',
  `group_id` tinyint(4) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_to_members_groups` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `members_groups`
--

DROP TABLE IF EXISTS `members_groups`;
CREATE TABLE IF NOT EXISTS `members_groups` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `placeholder`
--

DROP TABLE IF EXISTS `placeholder`;
CREATE TABLE IF NOT EXISTS `placeholder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'placeholder',
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `marker_id` int(11) NOT NULL,
  `liked` int(11) NOT NULL DEFAULT '0',
  `reported` int(11) NOT NULL DEFAULT '0',
  `privacy` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `post_reports`
--

DROP TABLE IF EXISTS `post_reports`;
CREATE TABLE IF NOT EXISTS `post_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `privacy_categories`
--

DROP TABLE IF EXISTS `privacy_categories`;
CREATE TABLE IF NOT EXISTS `privacy_categories` (
  `id` int(11) NOT NULL,
  `private_label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `fk_friendship_to_members` FOREIGN KEY (`friend_b`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_friendship_to_members2` FOREIGN KEY (`friend_a`) REFERENCES `members` (`id`);

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_to_images_groups` FOREIGN KEY (`groupimg_id`) REFERENCES `images_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `markers`
--
ALTER TABLE `markers`
  ADD CONSTRAINT `fk_image_to_markers` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `fk_members_to_members_groups` FOREIGN KEY (`group_id`) REFERENCES `members_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_to_images` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
