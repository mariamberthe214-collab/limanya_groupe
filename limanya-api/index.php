<?php
// Simple front controller for the API

// --- CORS ---
// Domaines autorisés à appeler cette API avec des identifiants (cookies de session).
// ⚠️ Avant la mise en production : ajoutez votre vrai domaine ici
// (ex: 'https://www.limanyagroupe.com') et retirez les origines localhost si besoin.
$allowedOrigins = [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    'http://localhost',
    // 'https://www.limanyagroupe.com',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
}
// Remarque : si l'origine n'est pas dans la liste, aucun en-tête CORS n'est renvoyé
// (le navigateur bloquera la requête côté client), au lieu d'autoriser "*" par défaut.
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Vary: Origin');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// --- Session sécurisée ---
session_set_cookie_params([
    'httponly' => true,   // Le cookie de session n'est pas accessible en JavaScript
    'samesite' => 'Lax',  // Protection basique contre le CSRF
    'secure' => (($_SERVER['HTTPS'] ?? '') === 'on'), // Cookie HTTPS uniquement si le site est servi en HTTPS
]);
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$path = '/' . trim(substr($uri, strlen($base)), '/');
$segments = array_values(array_filter(explode('/', $path)));

// Supprimer index.php des segments s'il est présent
if (!empty($segments) && $segments[0] === 'index.php') {
    array_shift($segments);
}


// Router minimal
$method = $_SERVER['REQUEST_METHOD'];

require_once __DIR__ . '/controllers/ActualiteController.php';
require_once __DIR__ . '/controllers/RealisationController.php';
require_once __DIR__ . '/controllers/DevisController.php';
require_once __DIR__ . '/controllers/ContactController.php';
require_once __DIR__ . '/controllers/EquipementController.php';
require_once __DIR__ . '/controllers/UsersController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UploadController.php';
require_once __DIR__ . '/helpers/auth.php';

if (count($segments) === 0 || $segments[0] === '') {
    echo json_encode(['message' => 'API root']);
    exit;
}

$resource = $segments[0];
$id = $segments[1] ?? null;

switch ($resource) {
    case 'auth':
        $auth = new AuthController();
        $action = $segments[1] ?? null;
        if ($action === 'login' && $method === 'POST') $auth->login();
        elseif ($action === 'logout' && $method === 'POST') $auth->logout();
        else {
            http_response_code(404);
            echo json_encode(['message' => 'Not found']);
        }
        break;
    case 'actualites':
        $ctrl = new ActualiteController();
        if ($method === 'GET' && !$id) $ctrl->index();
        elseif ($method === 'GET' && $id) $ctrl->show($id);
        elseif ($method === 'POST') { requireAdmin(); $ctrl->store(); }
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;
    case 'realisations':
        $ctrl = new RealisationController();
        if ($method === 'GET' && !$id) $ctrl->index();
        elseif ($method === 'GET' && $id) $ctrl->show($id);
        elseif ($method === 'POST') { requireAdmin(); $ctrl->store(); }
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;
    case 'devis':
        $ctrl = new DevisController();
        if ($method === 'GET' && !$id) { requireAdmin(); $ctrl->index(); }
        elseif ($method === 'GET' && $id) { requireAdmin(); $ctrl->show($id); }
        elseif ($method === 'POST') $ctrl->store();
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;
    case 'contacts':
        $ctrl = new ContactController();
        if ($method === 'GET' && !$id) { requireAdmin(); $ctrl->index(); }
        elseif ($method === 'GET' && $id) { requireAdmin(); $ctrl->show($id); }
        elseif ($method === 'POST') $ctrl->store();
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;

    case 'users':
        $ctrl = new UsersController();
        if ($method === 'GET' && !$id) { requireAdmin(); $ctrl->index(); }
        elseif ($method === 'GET' && $id) { requireAdmin(); $ctrl->show($id); }
        elseif ($method === 'POST') { requireAdmin(); $ctrl->store(); }
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;

    case 'equipements':
        $ctrl = new EquipementController();
        if ($method === 'GET' && !$id) $ctrl->index();
        elseif ($method === 'GET' && $id) $ctrl->show($id);
        elseif ($method === 'POST') { requireAdmin(); $ctrl->store(); }
        elseif (($method === 'PUT' || $method === 'PATCH') && $id) { requireAdmin(); $ctrl->update($id); }
        elseif ($method === 'DELETE' && $id) { requireAdmin(); $ctrl->destroy($id); }
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;
    case 'upload':
        requireAdmin();
        $ctrl = new UploadController();
        if ($method === 'POST') $ctrl->store();
        else {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
        }
        break;

    case 'dashboard':

        require_once __DIR__ . '/controllers/DashboardController.php';

        $ctrl = new DashboardController();

        if ($method === 'GET') {
            $ctrl->stats();
        }

        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Not found']);
}
