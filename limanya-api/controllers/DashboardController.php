<?php

require_once __DIR__ . '/../config/connection.php';

class DashboardController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function stats()
    {
        $actualites = $this->pdo->query("SELECT COUNT(*) FROM actualites")->fetchColumn();

        $realisations = $this->pdo->query("SELECT COUNT(*) FROM realisations")->fetchColumn();

        $devis = $this->pdo->query("SELECT COUNT(*) FROM devis")->fetchColumn();

        $contacts = $this->pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

        echo json_encode([
            "actualites" => (int)$actualites,
            "realisations" => (int)$realisations,
            "devis" => (int)$devis,
            "contacts" => (int)$contacts
        ]);
    }
}