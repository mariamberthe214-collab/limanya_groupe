<?php
require_once __DIR__ . '/../models/Equipement.php';

class EquipementController
{
    private Equipement $model;

    public function __construct()
    {
        $this->model = new Equipement();
    }

    public function index()
    {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $items = $this->model->getAll($limit, $offset);
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
        $id = $this->model->create($input);
        $this->sendJson(['id' => $id], 201);
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendJson(['message' => 'Invalid input'], 400);
            return;
        }
        $ok = $this->model->update((int)$id, $input);
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
