-- Calendrier des Rêves Limanya (migration additive)
-- À exécuter une seule fois sur la base Limanya avant d'utiliser les routes du jeu.

USE `limanya`;

CREATE TABLE IF NOT EXISTS `jeu_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `jour` TINYINT UNSIGNED NOT NULL,
  `date_jeu` DATE NOT NULL,
  `cadeau` VARCHAR(255) NOT NULL,
  `question` TEXT NOT NULL,
  `choix_a` VARCHAR(500) NOT NULL,
  `choix_b` VARCHAR(500) NOT NULL,
  `choix_c` VARCHAR(500) NOT NULL,
  `bonne_reponse` ENUM('A', 'B', 'C') NOT NULL,
  `est_grand_lot` TINYINT(1) NOT NULL DEFAULT 0,
  `statut` ENUM('brouillon', 'publie') NOT NULL DEFAULT 'brouillon',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jeu_questions_jour` (`jour`),
  UNIQUE KEY `uq_jeu_questions_date` (`date_jeu`),
  CONSTRAINT `chk_jeu_questions_jour` CHECK (`jour` BETWEEN 1 AND 31)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jeu_participants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `prenoms` VARCHAR(150) NOT NULL,
  `whatsapp` VARCHAR(30) NOT NULL,
  `whatsapp_normalise` VARCHAR(20) NOT NULL,
  `commune` VARCHAR(100) NOT NULL,
  `reponse` ENUM('A', 'B', 'C') NOT NULL,
  `est_correcte` TINYINT(1) NOT NULL,
  `date_participation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jeu_participant_jour_whatsapp` (`question_id`, `whatsapp_normalise`),
  KEY `idx_jeu_participants_corrects` (`question_id`, `est_correcte`),
  CONSTRAINT `fk_jeu_participants_question`
    FOREIGN KEY (`question_id`) REFERENCES `jeu_questions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jeu_gagnants` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` INT UNSIGNED NOT NULL,
  `participant_id` BIGINT UNSIGNED NOT NULL,
  `date_tirage` DATETIME NOT NULL,
  `statut` ENUM('en_attente', 'confirme', 'publie', 'annule') NOT NULL DEFAULT 'en_attente',
  `observation` VARCHAR(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jeu_gagnant_jour` (`question_id`),
  CONSTRAINT `fk_jeu_gagnants_question`
    FOREIGN KEY (`question_id`) REFERENCES `jeu_questions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_jeu_gagnants_participant`
    FOREIGN KEY (`participant_id`) REFERENCES `jeu_participants` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
