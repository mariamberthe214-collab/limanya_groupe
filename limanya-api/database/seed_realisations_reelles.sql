-- Insère les projets réels du dossier technique LIMANYA GROUPE dans la table `realisations`.
-- Les photos correspondantes doivent déjà être présentes dans limanya-api/uploads/
-- (elles sont incluses dans l'archive fournie).
-- À exécuter dans phpMyAdmin (onglet SQL) sur la base "limanya", une seule fois.

INSERT INTO `realisations` (`titre`, `description`, `categorie`, `image`, `lieu`, `date_realisation`) VALUES

-- BTP & Génie Civil
('Construction d\'une mercerie — École de Police', 'Projet de construction sur une surface de 500 m² comprenant un réfectoire, une cuisine, 3 magasins et une grande terrasse. Travaux de gros œuvre du bâtiment.', 'BTP & Génie Civil', '/uploads/btp_odienne_1.jpg', 'Yamoussoukro', NULL),

('Construction de 2 villas — École de Police', 'Projet de construction de 2 villas sur une surface de 250 m². Travaux de gros œuvre du bâtiment.', 'BTP & Génie Civil', '/uploads/btp_odienne_2.jpg', 'Yamoussoukro', NULL),

('Construction d\'un poste de contrôle — École de Police', 'Projet de construction d\'un poste de contrôle sur une surface de 400 m². Travaux de gros œuvre du bâtiment.', 'BTP & Génie Civil', '/uploads/btp_odienne_3.jpg', 'Yamoussoukro', NULL),

('Construction du Bataillon de Sécurisation Nord-Ouest', 'Projet de construction sur 700 m² comprenant 2 ateliers de lavage, 1 atelier de vidange, 1 atelier mécanique, 1 salle d\'eau et 1 magasin. Durée : 8 semaines.', 'BTP & Génie Civil', '/uploads/btp_odienne_4.jpg', 'Odienné', NULL),

-- Assainissement (fosses septiques biodigesteurs)
('Fosse septique biodigesteur — Sikensi', 'Réalisation d\'une fosse biodigesteur de 3000 L (3 tanks de 1000 L). Fouille, pose des tanks, remblai et dalle de couverture. Durée : 1 semaine.', 'Assainissement', '/uploads/assainissement_sikensi_1.jpg', 'Sikensi', NULL),

('Fosse septique biodigesteur — Bassam Cité Yakassé', 'Réalisation d\'une fosse biodigesteur de 3000 L (3 tanks de 1000 L). Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 8 jours.', 'Assainissement', '/uploads/assainissement_sikensi_2.jpg', 'Grand-Bassam', NULL),

('Fosse septique biodigesteur — Cocody Angré', 'Réalisation d\'une fosse biodigesteur de 2000 L (2 tanks de 1000 L). Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 4 jours.', 'Assainissement', '/uploads/assainissement_angre_1.jpg', 'Cocody Angré, Abidjan', NULL),

('Fosse septique biodigesteur — Yamoussoukro', 'Réalisation d\'une fosse biodigesteur de 3000 L (3 tanks de 1000 L). Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 1 semaine.', 'Assainissement', '/uploads/assainissement_yamoussoukro.jpg', 'Yamoussoukro', NULL),

('Fosse septique biodigesteur — Dabakala', 'Réalisation d\'une fosse biodigesteur de 1500 L. Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 5 jours.', 'Assainissement', '/uploads/assainissement_dabakala.jpg', 'Dabakala', NULL),

('Fosse septique biodigesteur — Bonoua', 'Réalisation d\'une fosse biodigesteur de 6 tanks de 1000 L. Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 2 semaines.', 'Assainissement', '/uploads/assainissement_bonoua_1.jpg', 'Bonoua', NULL),

('Fosse septique biodigesteur — Bingerville', 'Réalisation d\'une fosse biodigesteur de 3 tanks de 1000 L. Fouille, pose des tanks, remblai, dalle de couverture et liaison. Durée : 5 jours.', 'Assainissement', '/uploads/assainissement_bingerville.jpg', 'Bingerville, Abidjan', NULL),

-- Forages Hydrauliques
('Forage hydraulique — Songon', 'Réalisation d\'un forage hydraulique allant jusqu\'à 51 m de profondeur. Étude géophysique, géomorphologique et foration.', 'Forages Hydrauliques', '/uploads/forage_songon.jpg', 'Songon', NULL),

('Forage hydraulique — Blolequin', 'Réalisation d\'un forage hydraulique allant jusqu\'à 60 m de profondeur. Étude géophysique, géomorphologique et foration.', 'Forages Hydrauliques', '/uploads/forage_blolequin_1.jpg', 'Blolequin', NULL);
