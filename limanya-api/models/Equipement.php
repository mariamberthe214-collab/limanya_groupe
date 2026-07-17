<?php
require_once __DIR__ . '/BaseModel.php';

class Equipement extends BaseModel
{
    protected string $table = 'equipements';

    public function getAll(int $limit = 100, int $offset = 0, ?string $categorie = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($categorie !== null) {
            $sql .= " WHERE categorie = :categorie";
            $params[':categorie'] = $categorie;
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
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
            (nom, description, image, prix, disponible, categorie, created_at)
            VALUES
            (:nom, :description, :image, :prix, :disponible, :categorie, NOW())
        ");

        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':prix' => $data['prix'] ?? null,
            ':disponible' => $data['disponible'] ?? 1,
            ':categorie' => $data['categorie'] ?? 'Matériel de forage',
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
                disponible = :disponible,
                categorie = :categorie
            WHERE id = :id
        ");

        return $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':description' => $data['description'] ?? null,
            ':image' => $data['image'] ?? null,
            ':prix' => $data['prix'] ?? null,
            ':disponible' => $data['disponible'] ?? 1,
            ':categorie' => $data['categorie'] ?? 'Matériel de forage',
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
