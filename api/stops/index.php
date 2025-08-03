<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (isset($_SESSION['user_id'])) {
    $key = getApiKey($pdo, $_SESSION['user_id']);

    if (!$key) {
        echo json_encode(["state" => "Erreur: Pas de clé pour l'API"]);
        exit();
    }
} else {
    echo json_encode(["state" => "Erreur: Utilisateur non connecté"]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $input = json_decode(file_get_contents('php://input'), true);
    $type = trim($input['type'] ?? '');
    
    if (empty($type)) {
        $stops = getAllStops($pdo);
    }

    if (!$stops) {
        echo json_encode(["state" => "Pas de point d'arrêt ou mauvaise réponse", "response" => $stops]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "stops" => $stops
    ]);
}

exit();

?>