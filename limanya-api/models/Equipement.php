<?php
require_once __DIR__ . '/BaseModel.php';

class Equipement extends BaseModel
{
    protected string $table = 'equipements';

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
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table}
            (nom, description, image, prix, disponible, created_at)
            VALUES
            (:nom, :description, :image, :prix, :disponible, NOW())
        ");

        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':prix' => $data['prix'] ?? null,
            ':disponible' => $data['disponible'] ?? 1,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

   public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE {$this->table}
            SET
                nom = :nom,
                description = :description,
                image = :image,
                prix = :prix,
                disponible = :disponible
            WHERE id = :id
        ");

        return $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':prix' => $data['prix'] ?? null,
            ':disponible' => $data['disponible'] ?? 1,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
