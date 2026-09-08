# LIMANYA Groupe

Site vitrine et espace d'administration de LIMANYA Groupe.

## Technologies

- Vue 3 et Vite
- Vue Router
- Bootstrap et Bootstrap Icons
- Axios
- API PHP avec MySQL/MariaDB

## Installation frontend

```bash
npm install
npm run dev
```

Le site est disponible par défaut sur `http://localhost:5173`.

## Configuration de l'API

Copiez `.env.example` vers `.env` si vous devez utiliser une URL spécifique :

```env
VITE_API_BASE_URL=/limanya-api/index.php
```

En développement local, le proxy Vite utilise l'API PHP servie par Apache. En production, remplacez cette valeur par l'URL publique réelle de l'API.

## API PHP

Prérequis : PHP 8+ et MySQL/MariaDB.

1. Importez `limanya-api/database/limanya_db.sql`.
2. Configurez `limanya-api/config/database.php`.
3. Démarrez Apache/PHP ou le serveur PHP local.

La documentation de l'API se trouve dans [limanya-api/README.md](limanya-api/README.md).

## Administration

Accès local : `http://localhost:5173/admin/login`

Les routes admin sont protégées par session PHP et nécessitent un utilisateur avec le rôle `admin`.

## Vérifications

```bash
npm run build
php limanya-api/tests/db_test.php
```

Le build frontend doit se terminer sans erreur avant une mise en ligne.
