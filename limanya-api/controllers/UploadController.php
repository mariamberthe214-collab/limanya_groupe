<?php

class UploadController
{
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private $allowedVideoTypes = ['video/mp4', 'video/quicktime', 'video/webm'];
    private $maxSizeBytes = 5 * 1024 * 1024; // 5 Mo pour les images
    private $maxVideoSizeBytes = 60 * 1024 * 1024; // 60 Mo pour les vidéos

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['message' => "Aucun fichier valide reçu."]);
            return;
        }

        $file = $_FILES['file'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $isVideo = in_array($mime, $this->allowedVideoTypes, true);
        $isImage = in_array($mime, $this->allowedTypes, true);

        if (!$isVideo && !$isImage) {
            http_response_code(400);
            echo json_encode(['message' => "Type de fichier non autorisé. Formats acceptés : JPG, PNG, WEBP, GIF, MP4, MOV, WEBM."]);
            return;
        }

        $maxSize = $isVideo ? $this->maxVideoSizeBytes : $this->maxSizeBytes;
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            $limite = $isVideo ? '60 Mo' : '5 Mo';
            echo json_encode(['message' => "Le fichier dépasse la taille maximale autorisée ($limite)."]);
            return;
        }

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/webm' => 'webm',
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
            'type' => $isVideo ? 'video' : 'image',
        ]);
    }
}
