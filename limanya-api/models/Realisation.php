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
        if (!$row) return null;

        $media = $this->pdo->prepare(
            'SELECT id, url, type, ordre FROM realisation_medias WHERE realisation_id = :id ORDER BY ordre, id'
        );
        $media->execute([':id' => $id]);
        $row['medias'] = $media->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (titre, description, categorie, image, video, lieu, date_realisation, created_at) VALUES (:titre, :description, :categorie, :image, :video, :lieu, :date_realisation, NOW())");
        $stmt->execute([
            ':titre' => $data['titre'] ?? null,
            ':description' => $data['description'] ?? null,
            ':categorie' => $data['categorie'] ?? null,
            ':image' => $data['image'] ?? null,
            ':video' => $data['video'] ?? null,
            ':lieu' => $data['lieu'] ?? null,
            ':date_realisation' => $data['date_realisation'] ?? null,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        $this->saveMedias($id, $data['medias'] ?? []);
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET titre = :titre, description = :description, categorie = :categorie, image = :image, video = :video, lieu = :lieu, date_realisation = :date_realisation WHERE id = :id");
        $ok = $stmt->execute([
            ':titre' => $data['titre'] ?? null,
            ':description' => $data['description'] ?? null,
            ':categorie' => $data['categorie'] ?? null,
            ':image' => $data['image'] ?? null,
            ':video' => $data['video'] ?? null,
            ':lieu' => $data['lieu'] ?? null,
            ':date_realisation' => $data['date_realisation'] ?? null,
            ':id' => $id,
        ]);
        if ($ok && array_key_exists('medias', $data)) {
            $this->saveMedias($id, $data['medias'], true);
        }
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function saveMedias(int $realisationId, array $medias, bool $replace = false): void
    {
        if ($replace) {
            $delete = $this->pdo->prepare('DELETE FROM realisation_medias WHERE realisation_id = :id');
            $delete->execute([':id' => $realisationId]);
        }

        $insert = $this->pdo->prepare(
            'INSERT INTO realisation_medias (realisation_id, url, type, ordre) VALUES (:realisation_id, :url, :type, :ordre)'
        );
        foreach ($medias as $ordre => $media) {
            if (empty($media['url'])) continue;
            $type = ($media['type'] ?? 'image') === 'video' ? 'video' : 'image';
            $insert->execute([
                ':realisation_id' => $realisationId,
                ':url' => $media['url'],
                ':type' => $type,
                ':ordre' => $ordre,
            ]);
        }
    }
}
