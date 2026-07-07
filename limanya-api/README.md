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
- CORS est ouvert à toutes origines pour le développement. Restreindre en production.
- Ajouter validation, authentification (tokens), et gestion d'upload sécurisée avant mise en production.
