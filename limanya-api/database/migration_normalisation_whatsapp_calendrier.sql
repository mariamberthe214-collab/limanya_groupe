-- Harmonise le schéma des participations d'une base de jeu déjà installée.
-- À exécuter une seule fois après migration_jeu_calendrier.sql.

USE `limanya`;

ALTER TABLE `jeu_participants`
  ADD COLUMN `whatsapp_normalise` VARCHAR(20) NULL AFTER `whatsapp`;

UPDATE `jeu_participants`
SET `whatsapp_normalise` = CASE
  WHEN LEFT(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 2) = '00' THEN
    CASE
      WHEN LEFT(SUBSTRING(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 3), 3) = '225'
        THEN SUBSTRING(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 6)
      ELSE SUBSTRING(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 3)
    END
  WHEN LEFT(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 3) = '225'
    THEN SUBSTRING(REGEXP_REPLACE(`whatsapp`, '[^0-9]', ''), 4)
  ELSE REGEXP_REPLACE(`whatsapp`, '[^0-9]', '')
END;

ALTER TABLE `jeu_participants`
  MODIFY COLUMN `whatsapp_normalise` VARCHAR(20) NOT NULL,
  DROP INDEX `unique_participation`,
  ADD UNIQUE KEY `uq_jeu_participant_jour_whatsapp` (`question_id`, `whatsapp_normalise`);
