-- Ajoute 2 produits réels au catalogue "Vente de matériels".
-- À exécuter une seule fois dans phpMyAdmin, après la migration_categorie_equipements.sql.
-- Le prix est laissé à NULL : à compléter vous-même depuis l'admin si vous voulez l'afficher.

INSERT INTO `equipements` (`nom`, `description`, `image`, `prix`, `disponible`, `categorie`) VALUES

('Pompe solaire immergée', 'Pompe immergée avec contrôleur solaire, livrée avec câblage complet.', '/uploads/materiel_pompe_solaire.jpg', NULL, 1, 'Pompes solaires'),

('Tuyaux PVC hydrauliques', 'Tuyaux PVC hydrauliques pour forages et réseaux de distribution d\'eau.', '/uploads/materiel_livraison_pvc_1.jpg', NULL, 1, 'PVC hydrauliques');
