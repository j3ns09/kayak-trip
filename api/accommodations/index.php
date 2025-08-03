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
        $accommodations = getAllAccommodations($pdo);
    }

    if (!$accommodations) {
        echo json_encode(["state" => "Pas d'hébergements ou mauvaise réponse", "response" => $accommodations]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "accommodations" => $accommodations
    ]);
}

exit();

?>