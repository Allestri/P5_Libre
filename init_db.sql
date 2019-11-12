-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 12 nov. 2019 à 04:45
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `avatars`
--

INSERT INTO `avatars` (`id`, `user_id`, `avatar_file`, `active`) VALUES
(7, 6, 'avatar_soku_20-03-13.jpg', 1),
(2, 6, 'avatar_soku_10-15-07.jpg', 0),
(3, 6, 'avatar_soku_10-41-11.jpg', 0);

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
  `img_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `author_id`, `content`, `com_date`, `reported`, `moderated`, `img_id`) VALUES
(1, 6, 'Superbe photo !', '2019-09-23 04:44:37', 0, 0, 23),
(2, 6, 'DeuxiÃ¨me commentaire', '2019-09-23 04:59:23', 0, 0, 23),
(23, 6, 'Belle photo', '2019-10-08 13:56:02', 0, 0, 23),
(22, 6, '4e commentaire', '2019-10-01 23:35:53', 0, 0, 23),
(21, 6, '3e commentaire', '2019-10-01 22:13:35', 0, 0, 23),
(24, 6, 'Comment !', '2019-10-08 14:10:25', 0, 0, 32),
(25, 6, 'Superbe !', '2019-10-08 14:12:14', 0, 0, 32),
(26, 6, 'Bonjour', '2019-10-10 03:41:02', 0, 0, 32),
(27, 6, 'yo', '2019-10-10 07:44:36', 0, 0, 32),
(28, 6, 'df', '2019-10-10 07:54:48', 0, 0, 32),
(29, 6, 'Bonjour', '2019-10-10 08:40:02', 0, 0, 32),
(30, 6, 'jioj', '2019-10-10 08:40:12', 0, 0, 32),
(31, 6, 'Hello', '2019-10-10 08:41:17', 0, 0, 27),
(32, 6, 'Test', '2019-10-10 09:12:42', 0, 0, 32),
(33, 6, 'test', '2019-10-10 09:12:54', 0, 0, 27),
(34, 6, 'Test', '2019-10-11 03:27:49', 0, 0, 32),
(35, 6, 'Hey', '2019-10-11 03:28:03', 0, 0, 27),
(36, 6, 'Commentaire !', '2019-10-22 21:34:27', 0, 0, 3),
(37, 6, 'Commentaire !', '2019-10-29 05:14:55', 0, 0, 1),
(38, 6, 'Commentaire !', '2019-10-29 05:20:01', 0, 0, 1),
(39, 6, 'Test commentaire', '2019-10-29 18:51:34', 0, 0, 3),
(40, 6, 'Salut', '2019-11-05 21:20:40', 0, 0, 10),
(41, 6, 'Bonjour', '2019-11-05 21:20:44', 0, 0, 10);

-- --------------------------------------------------------

--
-- Structure de la table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE IF NOT EXISTS `friendship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friend_a` tinyint(4) NOT NULL,
  `friend_b` tinyint(4) NOT NULL,
  `friend_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_friendship_to_members` (`friend_b`),
  KEY `fk_friendship_to_members2` (`friend_a`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `friendship`
--

INSERT INTO `friendship` (`id`, `friend_a`, `friend_b`, `friend_date`) VALUES
(6, 6, 18, '2019-09-06'),
(8, 6, 19, '2019-09-06');

-- --------------------------------------------------------

--
-- Structure de la table `friend_requests`
--

DROP TABLE IF EXISTS `friend_requests`;
CREATE TABLE IF NOT EXISTS `friend_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` tinyint(4) NOT NULL,
  `receiver_id` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`sender_id`),
  KEY `fk_members_to_friend_req2` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `sender_id`, `receiver_id`, `created_at`) VALUES
(5, 6, 21, '2019-09-05 10:14:19');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(11) NOT NULL,
  `upload_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `groupimg_id` int(11) NOT NULL,
  `privacy` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_img` (`groupimg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `filename`, `height`, `width`, `size`, `type`, `upload_date`, `user_id`, `groupimg_id`, `privacy`) VALUES
(7, '4d6dbdc13fb6a080.jpg', 2242, 3992, 5447704, 'image/jpeg', '2019-10-29 20:36:18', 6, 1, 0),
(13, '1ca95b4620d5f191.JPG', 3000, 4000, 4977227, 'image/jpeg', '2019-11-12 01:24:59', 6, 1, 0),
(14, '2ee4783a396867a1.jpg', 2242, 3992, 5252806, 'image/jpeg', '2019-11-12 01:25:09', 6, 1, 0),
(25, '7534e9b8fae7dfb5.JPG', 3000, 4000, 4606630, 'image/jpeg', '2019-11-12 05:36:49', 6, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `images_groups`
--

DROP TABLE IF EXISTS `images_groups`;
CREATE TABLE IF NOT EXISTS `images_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `images_groups`
--

INSERT INTO `images_groups` (`id`, `label`) VALUES
(1, 'Drone'),
(2, 'DLSR'),
(3, 'Smartphone'),
(4, 'Other');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lng`, `lat`, `altitude`, `image_id`) VALUES
(6, 'Tihany', 'placeholder', 17.8909, 46.9152, 158.645, 7),
(12, 'Larmor', 'placeholder', -3.37521, 47.7069, 82.81, 13),
(13, 'Guidel', 'placeholder', -3.507, 47.7474, 99.203, 14),
(24, 'Quiberon', 'placeholder', -3.14242, 47.4902, 17.935, 25);

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
  `avatar_file` varchar(255) NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_to_members_groups` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `name`, `password`, `email`, `custom_avatar`, `avatar_file`, `group_id`, `ip_address`, `date`) VALUES
(6, 'SOku', '$2y$10$lOIr/VRtSKqwFFM5NoNJb.mMH9aoO2s1h97tzsDpFZmaCiSiuK59q', '', 1, 'avatar_default.png', 1, NULL, '2019-06-23 02:47:26'),
(18, 'John', '$2y$10$UDVjA2pLHdZIfO64peq.W.QS3GlEVFeGkvUSKL7cg./Hb/ttB1qrW', 'email@default.com', 1, 'avatar_default.png', 2, '::1', '2019-09-05 06:04:44'),
(19, 'Membre1', '$2y$10$qeOKW7srtV0iGFBfz7bBGuMckXoj5TddK290Zo/RL3NRXsTK4yHqW', 'email@default.com', 1, 'avatar_default.png', 3, '::1', '2019-09-05 06:30:48'),
(20, 'Membre2', '$2y$10$RV21BhyVsqSLOiLoBCeWrOoStOhU7NVr5t57lZD0VvISc3c7HKlme', 'email@default.com', 1, 'avatar_default.png', 3, '::1', '2019-09-05 06:31:57'),
(21, 'Membre3', '$2y$10$aPT267hpOs8skX3tGQyoduPpq6VFiBz.9EWVm2FxGjxbCzdWD2Mga', 'email@default.com', 1, 'avatar_default.png', 3, '::1', '2019-09-05 06:32:13'),
(22, 'Private', '$2y$10$Ly4plL/wboZmuWYck.sSVu8itdXwGARhgBFbb2elWrR8tuYjsKvFW', 'email@default.com', 1, 'avatar_default.png', 3, '::1', '2019-09-08 10:08:38'),
(23, 'Test56', '$2y$10$srbzRZu4WXMQgPfHb5WztekDG0tClMjpbe2XHOb7KF6BA57KxaFvi', 'email@default.com', 1, 'avatar_test56_17-08-37.gif', 3, '::1', '2019-10-01 19:02:18');

-- --------------------------------------------------------

--
-- Structure de la table `members_groups`
--

DROP TABLE IF EXISTS `members_groups`;
CREATE TABLE IF NOT EXISTS `members_groups` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `members_groups`
--

INSERT INTO `members_groups` (`id`, `label`) VALUES
(1, 'Administrateur'),
(2, 'Moderateur'),
(3, 'Membre');

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
  KEY `fk_post_to_images` (`image_id`),
  KEY `fk_post_to_markers` (`marker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `name`, `content`, `user_id`, `image_id`, `marker_id`, `liked`, `reported`, `privacy`) VALUES
(67, 'Quiberon', 'Une photo faite Ã  la va-vite au drone !', 6, 25, 2, 0, 1, 0);

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
-- Déchargement des données de la table `privacy_categories`
--

INSERT INTO `privacy_categories` (`id`, `private_label`) VALUES
(0, 'Public'),
(1, 'Friends'),
(2, 'User_only');

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
-- Contraintes pour la table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `fk_members_to_friend_req` FOREIGN KEY (`sender_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_members_to_friend_req2` FOREIGN KEY (`receiver_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
