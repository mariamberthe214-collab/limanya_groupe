-- Migration : ajoute la colonne "video" à la table realisations existante
-- À exécuter dans phpMyAdmin (onglet SQL) sur la base "limanya", une seule fois.

ALTER TABLE `realisations`
ADD COLUMN `video` VARCHAR(255) DEFAULT NULL AFTER `image`;
