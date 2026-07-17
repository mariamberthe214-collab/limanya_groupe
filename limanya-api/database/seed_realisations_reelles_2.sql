-- Ajoute un second lot de projets réels (photos + vidéos) à la galerie de réalisations.
-- À exécuter une seule fois dans phpMyAdmin, après avoir exécuté la migration
-- migration_video_realisations.sql (sinon la colonne "video" n'existe pas encore).

INSERT INTO `realisations` (`titre`, `description`, `categorie`, `image`, `video`, `lieu`, `date_realisation`) VALUES

('Fosse septique biodigesteur', 'Installation d\'une fosse septique biodigesteur avec pose des tanks et dalle de couverture en béton.', 'Assainissement', '/uploads/assainissement_biodigesteur_1.jpg', '/uploads/video_biodigesteur.mp4', NULL, NULL),

('Fosse biodigesteur — 4 tanks', 'Réalisation d\'une fosse biodigesteur à 4 compartiments, coffrage béton et raccordements.', 'Assainissement', '/uploads/assainissement_biodigesteur_2.jpg', NULL, NULL, NULL),

('Regard d\'assainissement', 'Aménagement d\'un regard d\'accès pour le réseau d\'assainissement.', 'Assainissement', '/uploads/assainissement_regard.jpg', NULL, NULL, NULL),

('Forage hydraulique', 'Réalisation d\'un forage hydraulique avec pose du tube de captage.', 'Forages Hydrauliques', '/uploads/forage_tube_eau.jpg', '/uploads/video_forage.mp4', NULL, NULL),

('Construction d\'un château d\'eau', 'Construction et installation d\'un château d\'eau pour l\'approvisionnement en eau du site.', 'Forages Hydrauliques', '/uploads/chateau_eau_construction.jpg', NULL, NULL, NULL),

('Étude géophysique de terrain', 'Campagne de mesures géophysiques sur le terrain pour l\'implantation d\'un forage.', 'Études Géophysiques', '/uploads/etude_geophysique_terrain.jpg', '/uploads/video_geophysique_1.mp4', NULL, NULL),

('Étude géophysique — relevés techniques', 'Relevés techniques et mesures de terrain réalisés par notre équipe géophysique.', 'Études Géophysiques', '/uploads/etude_geophysique_technicien.jpg', '/uploads/video_geophysique_2.mp4', NULL, NULL);
