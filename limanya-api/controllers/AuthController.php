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

    // Limitation anti-bruteforce : 5 tentatives max, blocage 5 minutes
    $tentatives = $_SESSION['login_attempts'] ?? ['count' => 0, 'first' => time()];
    if (time() - $tentatives['first'] > 300) {
        $tentatives = ['count' => 0, 'first' => time()];
    }
    if ($tentatives['count'] >= 5) {
        $this->sendJson([
            'message' => 'Trop de tentatives. Réessayez dans quelques minutes.'
        ], 429);
        return;
    }

    $user = $this->model->findByEmail($input['email']);
    $motDePasseValide = $user && password_verify($input['password'], $user['password']);

    if (!$motDePasseValide) {
        $tentatives['count']++;
        $_SESSION['login_attempts'] = $tentatives;

        $this->sendJson([
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
        return;
    }

    unset($_SESSION['login_attempts']);

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
