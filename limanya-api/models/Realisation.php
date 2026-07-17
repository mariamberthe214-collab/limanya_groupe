<?php
require_once __DIR__ . '/BaseModel.php';

class Realisation extends BaseModel
{
    protected string $table = 'realisations';

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
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (titre, description, categorie, image, lieu, date_realisation, created_at) VALUES (:titre, :description, :categorie, :image, :lieu, :date_realisation, NOW())");
        $stmt->execute([
            ':titre' => $data['titre'] ?? null,
            ':description' => $data['description'] ?? null,
            ':categorie' => $data['categorie'] ?? null,
            ':image' => $data['image'] ?? null,
            ':lieu' => $data['lieu'] ?? null,
            ':date_realisation' => $data['date_realisation'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET titre = :titre, description = :description, categorie = :categorie, image = :image, lieu = :lieu, date_realisation = :date_realisation WHERE id = :id");
        return $stmt->execute([
            ':titre' => $data['titre'] ?? null,
            ':description' => $data['description'] ?? null,
            ':categorie' => $data['categorie'] ?? null,
            ':image' => $data['image'] ?? null,
            ':lieu' => $data['lieu'] ?? null,
            ':date_realisation' => $data['date_realisation'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
