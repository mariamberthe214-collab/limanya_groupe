<?php
// Helper connection
$cfg = require __DIR__ . '/database.php';
$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['dbname'], $cfg['charset']);

function getPDO(): PDO
{
    global $dsn, $cfg;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
}
