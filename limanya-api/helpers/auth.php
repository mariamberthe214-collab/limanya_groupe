<?php
function requireRole(array $roles = [])
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $role = $_SESSION['user']['role'] ?? '';
    $roleAutorise = $role === 'admin'
        || in_array($role, $roles, true)
        || ($role === 'editeur' && in_array('gestionnaire_contenu', $roles, true));

    if (!isset($_SESSION['user']) || !$roleAutorise) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }
}

function requireAdmin()
{
    requireRole();
}
