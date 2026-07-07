<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=limanya;charset=utf8mb4', 'limanya', 'changeme');
    echo 'PDO OK';
} catch (Throwable $e) {
    echo $e->getMessage();
}
