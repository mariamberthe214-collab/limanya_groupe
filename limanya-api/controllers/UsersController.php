<?php
require_once __DIR__ . '/../models/User.php';

class UsersController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        $items = $this->model->getAll();
        $this->sendJson($items);
    }

    public function show($id)
    {
        $item = $this->model->getById((int)$id);
        if (!$item) {
            $this->sendJson(['message' => 'Not found'], 404);
            return;
        }
        $this->sendJson($item);
    }

    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendJson(['message' => 'Invalid input'], 400);
            return;
        }

        $passwordHash = password_hash($input['password'] ?? '', PASSWORD_DEFAULT);
        $id = $this->model->create([
            'nom' => $input['nom'] ?? null,
            'prenom' => $input['prenom'] ?? null,
            'email' => $input['email'] ?? null,
            'telephone' => $input['telephone'] ?? null,
            'photo' => $input['photo'] ?? null,
            'password' => $passwordHash,
            'role' => $input['role'] ?? 'admin',
        ]);

        $this->sendJson(['id' => $id], 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendJson(['message' => 'Invalid input'], 400);
            return;
        }

        $data = [
            'nom' => $input['nom'] ?? null,
            'prenom' => $input['prenom'] ?? null,
            'email' => $input['email'] ?? null,
            'telephone' => $input['telephone'] ?? null,
            'photo' => $input['photo'] ?? null,
            'role' => $input['role'] ?? 'admin',
        ];

        if (!empty($input['password'])) {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $ok = $this->model->update((int)$id, $data);
        if ($ok) {
            $this->sendJson(['message' => 'Updated']);
        } else {
            $this->sendJson(['message' => 'Update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $ok = $this->model->delete((int)$id);
        if ($ok) {
            $this->sendJson(['message' => 'Deleted']);
        } else {
            $this->sendJson(['message' => 'Delete failed'], 500);
        }
    }

    private function sendJson($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
