<?php
require_once __DIR__ . '/BaseModel.php';

class Actualite extends BaseModel
{
    protected string $table = 'actualites';

    /**
     * Décode le champ JSON "medias" en tableau PHP pour chaque ligne.
     */
    private function decorer(array $row): array
    {
        $row['medias'] = $row['medias'] ? json_decode($row['medias'], true) : [];
        return $row;
    }

    public function getAll(int $limit = 100, int $offset = 0, ?string $statut = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($statut !== null) {
            $sql .= " WHERE statut = :statut";
            $params[':statut'] = $statut;
        }

        $sql .= " ORDER BY date_publication DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'decorer'], $rows);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM {$this->table}
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id
        ]);

        $actualite = $stmt->fetch(PDO::FETCH_ASSOC);

        return $actualite ? $this->decorer($actualite) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO {$this->table}
            (
                titre,
                contenu,
                image,
                video,
                medias,
                auteur,
                statut,
                date_publication
            )
            VALUES
            (
                :titre,
                :contenu,
                :image,
                :video,
                :medias,
                :auteur,
                :statut,
                :date_publication
            )
        ");

        $stmt->execute([
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':image' => $data['image'] ?? null,
            ':video' => $data['video'] ?? null,
            ':medias' => isset($data['medias']) ? json_encode($data['medias']) : null,
            ':auteur' => $data['auteur'] ?? 'Administrateur',
            ':statut' => $data['statut'] ?? 'Publié',
            ':date_publication' => $data['date_publication']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE {$this->table}
            SET
                titre = :titre,
                contenu = :contenu,
                image = :image,
                video = :video,
                medias = :medias,
                auteur = :auteur,
                statut = :statut,
                date_publication = :date_publication
            WHERE id = :id
        ");

        return $stmt->execute([
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':image' => $data['image'] ?? null,
            ':video' => $data['video'] ?? null,
            ':medias' => isset($data['medias']) ? json_encode($data['medias']) : null,
            ':auteur' => $data['auteur'] ?? 'Administrateur',
            ':statut' => $data['statut'] ?? 'Publié',
            ':date_publication' => $data['date_publication'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM {$this->table}
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}