-- Migration : ajoute la colonne "categorie" à la table equipements existante
-- À exécuter une seule fois dans phpMyAdmin (onglet SQL) sur la base "limanya"

ALTER TABLE `equipements`
ADD COLUMN `categorie` VARCHAR(100) NOT NULL DEFAULT 'Matériels de forage' AFTER `disponible`;
