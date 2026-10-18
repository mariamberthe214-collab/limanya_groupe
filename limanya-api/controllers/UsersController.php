<?php
require_once __DIR__ . '/../models/User.php';

class UsersController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        $items = $this->model->getAll();
        $this->sendJson($items);
    }

    public function show($id)
    {
        $item = $this->model->getById((int)$id);
        if (!$item) {
            $this->sendJson(['message' => 'Not found'], 404);
            return;
        }
        $this->sendJson($item);
    }

    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $this->sendJson(['message' => 'Données invalides.'], 400);
            return;
        }

        $validation = $this->validateUser($input, true);
        if ($validation !== null) {
            $this->sendJson(['message' => $validation], 422);
            return;
        }

        $email = strtolower(trim($input['email']));
        if ($this->model->findByEmail($email)) {
            $this->sendJson(['message' => 'Cette adresse e-mail est déjà utilisée.'], 409);
            return;
        }

        $id = $this->model->create([
            'nom' => trim($input['nom']),
            'prenom' => trim($input['prenom'] ?? ''),
            'email' => $email,
            'telephone' => trim($input['telephone'] ?? ''),
            'photo' => $input['photo'] ?? null,
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'role' => $input['role'],
        ]);

        $this->sendJson(['id' => $id], 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendJson(['message' => 'Données invalides.'], 400);
            return;
        }

        $validation = $this->validateUser($input, false);
        if ($validation !== null) {
            $this->sendJson(['message' => $validation], 422);
            return;
        }

        $email = strtolower(trim($input['email']));
        $existingUser = $this->model->findByEmail($email);
        if ($existingUser && (int)$existingUser['id'] !== (int)$id) {
            $this->sendJson(['message' => 'Cette adresse e-mail est déjà utilisée.'], 409);
            return;
        }

        $data = [
            'nom' => trim($input['nom']),
            'prenom' => trim($input['prenom'] ?? ''),
            'email' => $email,
            'telephone' => trim($input['telephone'] ?? ''),
            'photo' => $input['photo'] ?? null,
            'role' => $input['role'],
        ];

        if (isset($input['password']) && $input['password'] !== '') {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $ok = $this->model->update((int)$id, $data);
        if ($ok) {
            $this->sendJson(['message' => 'Updated']);
        } else {
            $this->sendJson(['message' => 'Update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $ok = $this->model->delete((int)$id);
        if ($ok) {
            $this->sendJson(['message' => 'Deleted']);
        } else {
            $this->sendJson(['message' => 'Delete failed'], 500);
        }
    }

    private function validateUser(array $input, bool $passwordRequired): ?string
    {
        if (!isset($input['nom']) || trim((string)$input['nom']) === '') {
            return 'Le nom est obligatoire.';
        }

        if (!isset($input['email']) || !filter_var(trim((string)$input['email']), FILTER_VALIDATE_EMAIL)) {
            return 'Veuillez saisir une adresse e-mail valide.';
        }

        $password = $input['password'] ?? '';
        if (($passwordRequired || $password !== '') && strlen((string)$password) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        $roles = ['admin', 'gestionnaire_contenu', 'gestionnaire_commercial'];
        if (!isset($input['role']) || !in_array($input['role'], $roles, true)) {
            return 'Veuillez choisir un rôle valide.';
        }

        if (isset($input['photo']) && !is_string($input['photo'])) {
            return 'La photo de profil est invalide.';
        }

        return null;
    }

    private function sendJson($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
