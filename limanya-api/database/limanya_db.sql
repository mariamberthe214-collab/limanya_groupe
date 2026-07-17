-- limanya_db.sql
-- SQL schema pour l'API Limanya (corrigé pour correspondre aux modèles PHP et formulaires Vue)

CREATE DATABASE IF NOT EXISTS `limanya` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `limanya`;

-- ==========================
-- users (admin/editeur)
-- ==========================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `prenom` VARCHAR(150) DEFAULT NULL,
  `email` VARCHAR(200) NOT NULL UNIQUE,
  `telephone` VARCHAR(50) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Compte admin par défaut : email = admin@example.com / mot de passe = admin123
-- (changez ce mot de passe après la première connexion)
INSERT INTO `users` (`nom`, `prenom`, `email`, `telephone`, `password`, `role`)
VALUES ('Admin', 'Limanya', 'admin@example.com', NULL, '$2b$12$B3ICJj2Tv3Xpl6juQQvsQ.s0dMXJS6hPKCPYScgqPDJQIalT1cUqK', 'admin');

-- ==========================
-- actualites
-- ==========================
CREATE TABLE IF NOT EXISTS `actualites` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) NOT NULL,
  `contenu` TEXT,
  `image` VARCHAR(255) DEFAULT NULL,
  `auteur` VARCHAR(150) DEFAULT 'Administrateur',
  `statut` VARCHAR(50) DEFAULT 'Publié',
  `date_publication` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================
-- realisations
-- ==========================
CREATE TABLE IF NOT EXISTS `realisations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `categorie` VARCHAR(150) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `video` VARCHAR(255) DEFAULT NULL,
  `lieu` VARCHAR(255) DEFAULT NULL,
  `date_realisation` DATE DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================
-- devis
-- ==========================
CREATE TABLE IF NOT EXISTS `devis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `telephone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(200) DEFAULT NULL,
  `service` VARCHAR(150) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `statut` VARCHAR(50) DEFAULT 'En attente',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================
-- contacts
-- ==========================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `telephone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(200) DEFAULT NULL,
  `sujet` VARCHAR(255) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `lu` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================
-- equipements
-- ==========================
CREATE TABLE IF NOT EXISTS `equipements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `prix` DECIMAL(12,2) DEFAULT NULL,
  `disponible` TINYINT(1) NOT NULL DEFAULT 1,
  `categorie` VARCHAR(100) NOT NULL DEFAULT 'Matériels de forage',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
