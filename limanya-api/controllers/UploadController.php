<?php

class UploadController
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private $maxSizeBytes = 5 * 1024 * 1024; // 5 Mo

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['message' => "Aucun fichier valide reçu."]);
            return;
        }

        $file = $_FILES['file'];

        if ($file['size'] > $this->maxSizeBytes) {
            http_response_code(400);
            echo json_encode(['message' => "Le fichier dépasse la taille maximale autorisée (5 Mo)."]);
            return;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $this->allowedTypes, true)) {
            http_response_code(400);
            echo json_encode(['message' => "Type de fichier non autorisé. Formats acceptés : JPG, PNG, WEBP, GIF."]);
            return;
        }

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $ext = $extensions[$mime];

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            http_response_code(500);
            echo json_encode(['message' => "Erreur lors de l'enregistrement du fichier."]);
            return;
        }

        echo json_encode([
            'message' => 'Fichier téléversé avec succès.',
            'path' => '/uploads/' . $filename,
        ]);
    }
}
