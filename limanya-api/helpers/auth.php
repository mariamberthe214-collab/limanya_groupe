<?php
function requireAdmin()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => 'Forbidden']);
        exit;
    }
}
