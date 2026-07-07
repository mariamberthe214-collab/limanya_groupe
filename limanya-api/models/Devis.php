<?php
require_once __DIR__ . '/BaseModel.php';

class Devis extends BaseModel
{
    protected string $table = 'devis';

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, telephone, email, service, message, statut, created_at) VALUES (:nom, :telephone, :email, :service, :message, :statut, NOW())");
        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':email' => $data['email'] ?? null,
            ':service' => $data['service'] ?? null,
            ':message' => $data['message'] ?? null,
            ':statut' => $this->normalizeStatus($data['statut'] ?? null),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table} SET nom = :nom, email = :email, telephone = :telephone, service = :service, message = :message, statut = :statut WHERE id = :id"
        );

        return $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':email' => $data['email'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':service' => $data['service'] ?? null,
            ':message' => $data['message'] ?? null,
            ':statut' => $this->normalizeStatus($data['statut'] ?? null),
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function normalizeStatus(?string $status): string
    {
        $value = strtolower(trim((string)$status));

        if ($value === 'traité' || $value === 'traite' || $value === 'done' || $value === 'termine') {
            return 'Traité';
        }

        if ($value === 'en cours' || $value === 'in_progress' || $value === 'progress' || $value === 'encours') {
            return 'En cours';
        }

        return 'En attente';
    }
}
