# API Test - Limanya

## But
Fournir les commandes `curl` et exemples de réponses pour tester localement l'API.

Base URL (dev): `http://localhost:8000`

---

## 1) Vérifier la connexion MySQL (script PHP)

```bash
php -f limanya-api/tests/db_test.php
```

Réponse attendue (JSON):

```json
{"ok":true,"message":"Connection OK"}
```

Si erreur, le JSON contient `error` avec le message PDO.

---

## 2) Endpoints et commandes curl

Remplacez `localhost:8000` si besoin.

### Actualités
- GET /actualites
```bash
curl http://localhost:8000/actualites
```
- POST /actualites
```bash
curl -X POST http://localhost:8000/actualites -H "Content-Type: application/json" -d '{"titre":"Titre","contenu":"Contenu","image":"img.jpg","date_publication":"2026-06-23 12:00:00"}'
```

### Réalisations
- GET /realisations
```bash
curl http://localhost:8000/realisations
```
- POST /realisations
```bash
curl -X POST http://localhost:8000/realisations -H "Content-Type: application/json" -d '{"titre":"Réal","description":"Desc","image":"img.jpg","lieu":"Ville","date_realisation":"2026-06-01"}'
```

### Devis
- GET /devis
```bash
curl http://localhost:8000/devis
```
- POST /devis
```bash
curl -X POST http://localhost:8000/devis -H "Content-Type: application/json" -d '{"nom":"Client","telephone":"0564613584","email":"c@c.com","service":"Forage","message":"Projet"}'
```

### Contacts
- GET /contacts
```bash
curl http://localhost:8000/contacts
```
- POST /contacts
```bash
curl -X POST http://localhost:8000/contacts -H "Content-Type: application/json" -d '{"nom":"Client","telephone":"0564613584","email":"c@c.com","message":"Bonjour"}'
```

### Équipements
- GET /equipements
```bash
curl http://localhost:8000/equipements
```
- POST /equipements
```bash
curl -X POST http://localhost:8000/equipements -H "Content-Type: application/json" -d '{"nom":"Foreuse","description":"Machine","image":"img.jpg","disponibilite":"disponible"}'
```

### Authentification
- POST /auth/login
```bash
curl -X POST http://localhost:8000/auth/login -H "Content-Type: application/json" -d '{"email":"admin@example.com","password":"admin123"}'
```
- POST /auth/logout
```bash
curl -X POST http://localhost:8000/auth/logout
```

---

## 3) Exemples de réponses attendues
- Liste (GET) : JSON array d'objets (200 OK)
- POST réussi : `{ "id": 123 }` (201 Created)
- Erreur d'input : `{ "message": "Invalid input" }` (400)
- Ressource non trouvée : `{ "message": "Not found" }` (404)

---

## 4) Postman
- Créer une collection, ajouter les requêtes ci-dessus, configurer un `environment` avec `baseUrl`.

---

## 5) Compatibilité XAMPP / phpMyAdmin
- Copier le dossier `limanya-api` dans `C:\xampp\htdocs\`.
- Importer `database/limanya_db.sql` via phpMyAdmin : `Import` -> choisir le fichier.
- Modifier `config/database.php` avec l'utilisateur `root` et mot de passe (vide par défaut sous XAMPP).
- Accéder à l'API via `http://localhost/limanya-api/` ou démarrer serveur intégré.

---

## 6) Authentification administrateur (plan)
- Endpoints proposés:
  - POST /auth/login  => body: email, password -> renvoie JWT
  - POST /auth/logout => invalide token côté serveur (optionnel)
  - Middleware vérifie JWT et rôle `admin` pour routes protégées
- Utiliser `firebase/php-jwt` pour JWT.
- Stocker `users` (table fournie). Passwords hachés via `password_hash()`.

### Créer un utilisateur administrateur

Générer un hash pour le mot de passe depuis la ligne de commande PHP :

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT) . PHP_EOL;"
```

Puis insérer l'utilisateur dans la base (remplacez le hash obtenu) :

```sql
INSERT INTO users (nom, email, password, role) VALUES ('Admin', 'admin@example.com', '<HASH>', 'admin');
```

---

## 7) Rapport de test
Créés : scripts de test dans `limanya-api/tests/` :

- `run_tests.ps1` : script PowerShell pour Windows (lance le serveur PHP intégré, exécute les requêtes via `Invoke-RestMethod` puis arrête le serveur).
- `run_tests.sh` : script Bash pour Linux/macOS (démarre le serveur PHP intégré, exécute les `curl`, puis arrête le serveur).

Exécutez le script adapté à votre système pour lancer tous les tests automatiquement.


