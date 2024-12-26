-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 26 déc. 2024 à 19:55
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eventure`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_requests`
--

CREATE TABLE `admin_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin_requests`
--

INSERT INTO `admin_requests` (`id`, `user_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 11, 'i want to .....', 'approved', '2024-12-26 14:12:32', '2024-12-26 14:27:15'),
(2, 11, 'please', 'approved', '2024-12-26 14:21:22', '2024-12-26 14:27:15'),
(3, 12, 'im nour i want to be an admin', 'approved', '2024-12-26 15:38:41', '2024-12-26 15:42:54');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','canceled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `photo`, `title`, `category`, `city`, `date_time`, `description`, `supervisor_id`, `status`) VALUES
(1, 'public/images/68927381.jpg', 'hiking in ain drahem', 'hiking', 'ain drahem', '2024-12-28 13:28:00', 'wieeee', 9, 'pending'),
(4, 'public/images/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI0LTAxL3Jhd3BpeGVsX29mZmljZV81M19hX3Zpc2lvbl9vZl9hX3N1c3RhaW5hYmxlX2VhcnRoX2VhcnRoX2RheV9jb184ODg0ZTYyNy0wMmMxLTQzZmItOWI1Mi0zZjExZjZhOGI5YmZfMS5qcGc.webp', 'tbarka', 'running', 'tbarka', '2025-01-03 17:54:00', 'nnnnnn\r\n', 11, 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `registration_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `event_participants`
--

INSERT INTO `event_participants` (`id`, `event_id`, `user_id`, `status`, `registration_date`) VALUES
(3, 1, 11, 'pending', '2024-12-26 16:16:34');

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','rejected') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `rater_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `reported_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `reported_id`, `reason`, `status`, `created_at`) VALUES
(1, 11, 12, '........................', 'pending', '2024-12-26 18:08:55');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `role` enum('user','admin','superadmin') NOT NULL DEFAULT 'user',
  `rating` float DEFAULT 0,
  `profile_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `photo`, `email`, `password`, `age`, `role`, `rating`, `profile_details`) VALUES
(5, 'wiem', 'uploads/profile_photos/profile_6756c63c69b94.jpg', 'wiemboujelben22@gmail.com', '$2y$10$gh57fL7A6Lo/uAgyT2Lg.u9QmWFw2j2PRdmgnW07FmBglQv8iAnF.', 55, '', 0, 'jjj'),
(7, 'iheb', NULL, 'iheb@yahoo.fr', '$2y$10$embJlypkdVMWaQOLKjiDX.zo5Lf.VemkuBimTDxbB.wp2oGf12oz2', NULL, '', 0, NULL),
(8, 'wiem', NULL, 'wii@gmail.com', '$2y$10$5PhPoeME1S8ODrVhsWtIteue4iFcXFj30vG5pXpQwCS1nzKkuC8Ga', NULL, '', 0, NULL),
(9, 'wiwi', NULL, 'wiwi@yahoo.fr', '$2y$10$PAOikqwTCw7vFMrN.mPmJOl/VkVZRmCoPeLJIlkaZcXeFUIgChGZG', 22, '', 0, ''),
(10, 'SuperAdmin', NULL, 'superadmin@eventure.com', '$2y$10$0ZlrAZ.Goy0LZC9QVRmx6eYWjAGPwa9yI7sdBsK0/d4prQMS8WSoK', NULL, 'superadmin', 0, NULL),
(11, 'user', NULL, 'user@gmail.com', '$2y$10$X.PRn5Z0X/sScLW4U7sJpOhF0NaWJbRdfgJOMf0b1TGAcAIo.yOi6', NULL, 'admin', 0, NULL),
(12, 'nour', NULL, 'nour@gmail.com', '$2y$10$sgZMHIq/Fi03Fpg0mcbMvuQXKILvOFHYo5nVILIrtouFl6ZLO4Zkm', NULL, 'admin', 0, NULL),
(13, 'sousou', NULL, 'sou@gmail.com', '$2y$10$KiWiTtIsn9c3G6TmMnyvEeDZ6YNHgPXMHQT3ybIu9BYuZrxPdufni', NULL, 'user', 0, NULL);

--
-- Déclencheurs `users`
--
DELIMITER $$
CREATE TRIGGER `before_user_insert` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE superadmin_count INT;
    SELECT COUNT(*) INTO superadmin_count FROM users WHERE role = 'superadmin';
    IF NEW.role = 'superadmin' AND superadmin_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Only one superadmin is allowed';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_user_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    DECLARE superadmin_count INT;
    IF NEW.role = 'superadmin' AND OLD.role != 'superadmin' THEN
        SELECT COUNT(*) INTO superadmin_count FROM users WHERE role = 'superadmin';
        IF superadmin_count > 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Only one superadmin is allowed';
        END IF;
    END IF;
END
$$
DELIMITER ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin_requests`
--
ALTER TABLE `admin_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_index` (`status`),
  ADD KEY `user_status_index` (`user_id`,`status`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Index pour la table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `rater_id` (`rater_id`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_id` (`reported_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin_requests`
--
ALTER TABLE `admin_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_requests`
--
ALTER TABLE `admin_requests`
  ADD CONSTRAINT `fk_admin_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
