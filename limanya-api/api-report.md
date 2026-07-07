# Rapport API Limanya

## Objectif
Vérifier l'implémentation du backend PHP 8 REST API, confirmer la compatibilité XAMPP/phpMyAdmin et documenter les tests disponibles.

## État actuel de l'API
- Dossier `limanya-api/` créé.
- Configuration PDO via `config/database.php` et `config/connection.php`.
- Modèles CRUD implémentés pour :
  - `actualites`
  - `realisations`
  - `devis`
  - `contacts`
  - `equipements`
- Contrôleurs correspondants créés dans `controllers/`.
- Routeur central `index.php` avec CORS et dispatch des routes REST.
- Authentification admin ajoutée via sessions :
  - `POST /auth/login`
  - `POST /auth/logout`
- Fichiers SQL & documentation créés :
  - `database/limanya_db.sql`
  - `README.md`
  - `api-test.md`
- Scripts de test automatiques créés :
  - `limanya-api/tests/run_tests.ps1`
  - `limanya-api/tests/run_tests.sh`
- Fichiers de test de connexion :
  - `limanya-api/tests/db_test.php`

## Vérification de l'environnement actuel
- `php` n'a pas été trouvé dans le `PATH` de l'environnement actuel.
- `mysql` n'a pas été trouvé dans le `PATH` de l'environnement actuel.
- Aucune installation standard XAMPP/WAMP n'a été détectée sur `C:\xampp`, `C:\Program Files\xampp`, `C:\Program Files (x86)\xampp`, `C:\wamp64`, ou `C:\wamp`.

> Résultat : je n'ai pas pu exécuter les tests locaux dans cet environnement faute d'interpréteur PHP/MySQL accessible.

## Points de cohérence vérifiés
- Le modèle `Actualite` est aligné avec le schéma SQL : `titre`, `contenu`, `image`, `date_publication`.
- Les tests et la documentation utilisent désormais les bons champs pour `POST /actualites`.
- Les routes REST pour les ressources demandées sont présentes dans `index.php`.
- Les réponses JSON et les codes HTTP sont pris en charge dans tous les contrôleurs.

## Tests disponibles à exécuter localement
### 1) Import de la base de données
```bash
mysql -u root -p < limanya-api/database/limanya_db.sql
```

### 2) Vérifier la connexion PDO
```bash
php -f limanya-api/tests/db_test.php
```

### 3) Lancer le serveur PHP interne
```bash
php -S localhost:8000 -t limanya-api
```

### 4) Exécuter les tests automatiques
- Windows PowerShell :
```powershell
cd limanya-api\tests
./run_tests.ps1
```
- Linux/macOS :
```bash
cd limanya-api/tests
./run_tests.sh
```

## Compatibilité XAMPP / phpMyAdmin
- Copier `limanya-api` dans `C:\xampp\htdocs\`.
- Importer `database/limanya_db.sql` via phpMyAdmin.
- Modifier `config/database.php` pour l'utilisateur et le mot de passe MySQL de XAMPP.
- Accéder à l'API via `http://localhost/limanya-api/`.

## Authentification administrateur
- Login : `POST /auth/login`
- Logout : `POST /auth/logout`
- Utilisation de sessions PHP.
- Pour créer un compte admin :
  1. Générer un hash de mot de passe avec `password_hash()`.
  2. Insérer l'utilisateur dans la table `users`.

## Recommandations
1. Installer PHP 8 et MySQL/WAMP/XAMPP localement pour exécuter les tests.
2. Importer `limanya_db.sql` puis lancer `tests/db_test.php`.
3. Exécuter `tests/run_tests.ps1` ou `tests/run_tests.sh` pour valider les routes.
4. En production, restreindre le CORS et ajouter des validations plus strictes.

## Conclusion
Le code backend est implémenté et prêt à être testé localement. La seule limite est que l'environnement actuel ne dispose pas d'un exécutable PHP/MySQL utilisable pour lancer les tests automatiquement.
