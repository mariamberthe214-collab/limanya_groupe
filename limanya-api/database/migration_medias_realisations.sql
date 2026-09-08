-- Galerie de médias pour les réalisations
-- À exécuter une seule fois sur la base limanya.

CREATE TABLE IF NOT EXISTS `realisation_medias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `realisation_id` INT UNSIGNED NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `type` ENUM('image', 'video') NOT NULL DEFAULT 'image',
  `ordre` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_realisation_medias_realisation` (`realisation_id`),
  CONSTRAINT `fk_realisation_medias_realisation`
    FOREIGN KEY (`realisation_id`) REFERENCES `realisations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
