<?php
require_once __DIR__ . '/../config/connection.php';
header('Content-Type: application/json; charset=utf-8');
try {
    $pdo = getPDO();
    echo json_encode(['ok' => true, 'message' => 'Connection OK']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
