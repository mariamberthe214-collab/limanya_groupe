Limanya API

Prérequis:
- PHP 8+
- MySQL/MariaDB

Installation rapide (développement):

1) Importer la base de données

```bash
mysql -u root -p < database/limanya_db.sql
```

2) Modifier la configuration

Éditez `config/database.php` et renseignez vos identifiants MySQL.

Pour activer le Calendrier des Rêves sur une base existante, exécutez aussi une seule fois `database/migration_jeu_calendrier.sql` après avoir sélectionné la base `limanya`. Cette migration ajoute les tables du jeu sans modifier les tables existantes.

Pour associer une photo ou une vidéo à chaque cadeau et l'afficher avec le gagnant publié, exécutez une seule fois `database/migration_media_cadeau_calendrier.sql` après la migration du jeu.

Si une ancienne table `jeu_participants` ne contient pas encore `whatsapp_normalise`, exécutez une seule fois `database/migration_normalisation_whatsapp_calendrier.sql`. Les nouvelles installations ont déjà ce champ dans `migration_jeu_calendrier.sql`.

Pour une recette avant décembre, le backend accepte une date simulée uniquement lorsque `APP_ENV=testing` est défini côté PHP. Définissez alors `CALENDRIER_TEST_NOW` (par exemple `2026-12-01T12:05:00+00:00`) sur l'environnement de test. Ne définissez pas ces variables sur le site de production ; sans `APP_ENV=testing`, le backend utilise l'heure réelle d'Abidjan.

Si la base existe déjà, exécutez aussi `database/migration_medias_realisations.sql` pour activer les galeries de photos et vidéos des réalisations.

3) Lancer le serveur PHP interne (pour tests)

```bash
php -S localhost:8000 -t limanya-api
```

4) Endpoints principaux

- GET /actualites
- GET /actualites/{id}
- POST /actualites
- PUT /actualites/{id}
- DELETE /actualites/{id}

- GET /realisations
- GET /realisations/{id}
- POST /realisations
- PUT /realisations/{id}
- DELETE /realisations/{id}

- GET /devis
- GET /devis/{id}
- POST /devis
- PUT /devis/{id}
- DELETE /devis/{id}

- GET /contacts
- GET /contacts/{id}
- POST /contacts
- PUT /contacts/{id}
- DELETE /contacts/{id}

- GET /equipements
- GET /equipements/{id}
- POST /equipements
- PUT /equipements/{id}
- DELETE /equipements/{id}

- POST /auth/login
- POST /auth/logout

5) Tester avec curl

```bash
# Lister actualites
curl http://localhost:8000/actualites

# Créer un devis
curl -X POST http://localhost:8000/devis -H 'Content-Type: application/json' -d '{"nom":"Client","telephone":"0564613584","email":"c@c.com","service":"Forage","message":"Projet"}'
```

Notes de sécurité:
- CORS est limité aux origines déclarées dans `index.php`.
- Les routes d'administration utilisent une session PHP et le rôle `admin`.
- Les routes publiques de création de contacts et de devis restent accessibles sans connexion.
- En production, remplacez les origines localhost par le domaine réel et utilisez HTTPS.
