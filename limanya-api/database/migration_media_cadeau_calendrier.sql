-- Média du cadeau affiché avec le gagnant publié.
-- À exécuter une seule fois après migration_jeu_calendrier.sql.

USE `limanya`;

ALTER TABLE `jeu_questions`
  ADD COLUMN `media_cadeau` VARCHAR(2048) NULL AFTER `cadeau`,
  ADD COLUMN `type_media_cadeau` ENUM('image', 'video') NULL AFTER `media_cadeau`;
