-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 09 sep. 2019 à 09:54
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

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
  `thumbnail_base64` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_img` (`groupimg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `filename`, `height`, `width`, `size`, `type`, `upload_date`, `user_id`, `groupimg_id`, `privacy`, `thumbnail_base64`) VALUES
(23, '291b42e7a2b5b405.JPG', 3000, 4000, 4977227, 'image/jpeg', '2019-08-30 07:54:23', 6, 1, 0, 'placeholder'),
(25, 'fd53e0ff42e734bb.JPG', 3672, 4896, 5235515, 'image/jpeg', '2019-08-30 08:17:19', 6, 1, 1, 'placeholder'),
(26, '635e6a83076ae87a.jpg', 1080, 1920, 233172, 'image/jpeg', '2019-09-04 05:21:51', 6, 3, 1, 'placeholder'),
(27, 'ba5a170e7ba993d9.jpg', 2242, 3992, 5252806, 'image/jpeg', '2019-09-04 06:52:48', 6, 1, 0, 'placeholder'),
(32, 'fea47e658a416473.jpg', 2242, 3992, 6071612, 'image/jpeg', '2019-09-06 07:29:31', 6, 1, 0, 'placeholder');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lng`, `lat`, `altitude`, `image_id`) VALUES
(12, 'Larmor-Plages', 'placeholder', -3.37521, 47.7069, 82.81, 23),
(14, 'Scotland', 'placeholder', -6.18284, 57.4577, 177.1, 25),
(15, 'Tihany', 'Tihany', 17.8895, 46.9144, 100, 26),
(16, 'Maeva', 'placeholder', -3.507, 47.7474, 99.203, 27),
(21, 'Douarnenez', 'placeholder', -4.28549, 48.108, 72.636, 32);

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_file` varchar(255) NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_members_to_members_groups` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `name`, `password`, `avatar_file`, `group_id`, `ip_address`, `date`) VALUES
(6, 'SOku', '$2y$10$lOIr/VRtSKqwFFM5NoNJb.mMH9aoO2s1h97tzsDpFZmaCiSiuK59q', 'avatar_soku_08-31-53.jpg', 1, NULL, '2019-06-23 02:47:26'),
(18, 'John', '$2y$10$UDVjA2pLHdZIfO64peq.W.QS3GlEVFeGkvUSKL7cg./Hb/ttB1qrW', 'avatar_default.png', 2, '::1', '2019-09-05 06:04:44'),
(19, 'Membre1', '$2y$10$qeOKW7srtV0iGFBfz7bBGuMckXoj5TddK290Zo/RL3NRXsTK4yHqW', 'avatar_default.png', 3, '::1', '2019-09-05 06:30:48'),
(20, 'Membre2', '$2y$10$RV21BhyVsqSLOiLoBCeWrOoStOhU7NVr5t57lZD0VvISc3c7HKlme', 'avatar_default.png', 3, '::1', '2019-09-05 06:31:57'),
(21, 'Membre3', '$2y$10$aPT267hpOs8skX3tGQyoduPpq6VFiBz.9EWVm2FxGjxbCzdWD2Mga', 'avatar_default.png', 3, '::1', '2019-09-05 06:32:13'),
(22, 'Private', '$2y$10$Ly4plL/wboZmuWYck.sSVu8itdXwGARhgBFbb2elWrR8tuYjsKvFW', 'avatar_default.png', 3, '::1', '2019-09-08 10:08:38');

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
  `name` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `name`, `content`) VALUES
(1, 'Article 1', 'Lorem Ipsum Content 1'),
(2, 'Article 2', 'Au revoir'),
(3, 'Bonjour', 'Lorem Ipsum'),
(48, 'Content', 'test'),
(49, 'Test', 'test'),
(56, 'TestMethode', 'Voila');

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
