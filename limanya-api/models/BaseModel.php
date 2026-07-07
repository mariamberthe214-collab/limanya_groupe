<?php
require_once __DIR__ . '/../config/connection.php';

class BaseModel
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }
}
