<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private User $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->model = new User();
    }

   public function login()
{
    $input = json_decode(file_get_contents('php://input'), true);

    if (
        !isset($input['email']) ||
        !isset($input['password'])
    ) {
        $this->sendJson([
            'message' => 'Email et mot de passe requis'
        ], 400);
        return;
    }

    $user = $this->model->findByEmail($input['email']);

    if (!$user) {
        $this->sendJson([
            'message' => 'Utilisateur introuvable'
        ], 401);
        return;
    }

    if (!password_verify($input['password'], $user['password'])) {
        $this->sendJson([
            'message' => 'Mot de passe incorrect'
        ], 401);
        return;
    }

    $_SESSION['user'] = [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'email' => $user['email'],
        'role' => $user['role']
    ];

    $this->sendJson([
        'message' => 'Connexion réussie',
        'user' => $_SESSION['user']
    ]);
}
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        $this->sendJson(['message' => 'Logged out']);
    }

    private function sendJson($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
