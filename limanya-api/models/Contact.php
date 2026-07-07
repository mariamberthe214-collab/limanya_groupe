<?php
require_once __DIR__ . '/BaseModel.php';

class Contact extends BaseModel
{
    protected string $table = 'contacts';

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
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, telephone, email, message, sujet, created_at) VALUES (:nom, :telephone, :email, :message, :sujet, NOW())");
        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':email' => $data['email'] ?? null,
            ':message' => $data['message'] ?? null,
            ':sujet' => $data['sujet'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

   public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE contacts
            SET
                nom = :nom,
                email = :email,
                telephone = :telephone,
                sujet = :sujet,
                message = :message,
                lu = :lu
            WHERE id = :id
        ");

        return $stmt->execute([
            ':nom' => $data['nom'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':sujet' => $data['sujet'],
            ':message' => $data['message'],
            ':lu' => $data['lu'] ?? 0,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
