<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, prenom, email, telephone, password, photo, role, created_at) VALUES (:nom, :prenom, :email, :telephone, :password, :photo, :role, NOW())");
        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':prenom' => $data['prenom'] ?? null,
            ':email' => $data['email'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':password' => $data['password'] ?? null,
            ':photo' => $data['photo'] ?? null,
            ':role' => $this->normalizeRole($data['role'] ?? null),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = ['nom = :nom', 'prenom = :prenom', 'email = :email', 'telephone = :telephone', 'photo = :photo', 'role = :role'];
        $params = [
            ':nom' => $data['nom'] ?? null,
            ':prenom' => $data['prenom'] ?? null,
            ':email' => $data['email'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':photo' => $data['photo'] ?? null,
            ':role' => $this->normalizeRole($data['role'] ?? null),
            ':id' => $id,
        ];

        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = $data['password'];
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    private function normalizeRole(?string $role): string
    {
        $value = strtolower(trim((string)$role));
        if ($value === 'editeur') {
            return 'editeur';
        }
        return 'admin';
    }
}