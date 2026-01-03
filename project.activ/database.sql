-- Database: cultural_platform

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--
-- Default admin: admin / admin123
-- Password hash generated with password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`, `email`, `password`) VALUES
('admin', 'admin@culture.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`nom`) VALUES
('Musique'),
('Théâtre'),
('Danse'),
('Arts plastiques'),
('Lecture'),
('Cinéma'),
('Cuisine');

--
-- Table structure for table `activites`
--

CREATE TABLE `activites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `prix` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT 'default_activity.jpg',
  `formateur` varchar(100) DEFAULT NULL,
  `niveau` enum('Débutant','Intermédiaire','Avancé','Tous niveaux') DEFAULT 'Tous niveaux',
  `places_disponibles` int(11) DEFAULT 20,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `fk_activite_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activites`
--

INSERT INTO `activites` (`titre`, `description`, `categorie_id`, `prix`, `image`, `formateur`, `niveau`, `places_disponibles`, `date_debut`) VALUES
('Atelier Peinture à l\'Huile', 'Découvrez les techniques de base de la peinture à l\'huile dans une ambiance conviviale.', 4, 150.00, 'atelier_peinture.jpg', 'Sarah Bernhardt', 'Débutant', 15, '2024-06-15 14:00:00'),
('Cours de Guitare Classique', 'Maîtrisez les accords et les mélodies de la guitare classique.', 1, 200.00, 'cours_guitare.jpg', 'Django Reinhardt', 'Intermédiaire', 10, '2024-06-20 18:00:00'),
('Initiation Théâtre', 'Apprenez à vous exprimer et à gérer le trac sur scène.', 2, 100.00, 'theatre_initiation.jpg', 'Molière', 'Débutant', 20, '2024-07-01 10:00:00');


--
-- Table structure for table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activite_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_inscription` timestamp DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('Confirmé','En attente','Annulé') DEFAULT 'En attente',
  PRIMARY KEY (`id`),
  KEY `activite_id` (`activite_id`),
  CONSTRAINT `fk_inscription_activite` FOREIGN KEY (`activite_id`) REFERENCES `activites` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `galerie`
--

CREATE TABLE `galerie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `categorie` enum('Ateliers','Concerts','Théâtre','Expositions') NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `offres`
--

CREATE TABLE `offres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `type` enum('Pack','Carte Membre','Autre') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `offres` (`titre`, `description`, `prix`, `type`) VALUES
('Pack Découverte', 'Inscription à 3 ateliers au choix avec une réduction de 20%.', 400.00, 'Pack'),
('Carte Membre Annuelle', 'Accès illimité à la bibliothèque et réductions sur tous les événements.', 250.00, 'Carte Membre');

--
-- Table structure for table `evenements`
--

CREATE TABLE `evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) NOT NULL,
  `description` text,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `lieu` varchar(100) DEFAULT 'Centre Culturel',
  `categorie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
