<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (existsSession('user_id')) {
    $key = getApiKey($pdo, $_SESSION['user_id']);

    if (!$key) {
        echo json_encode(["ok" => false, "error" => "Erreur: Pas de clé pour l'API"]);
        exit();
    }
} else {
    echo json_encode(["ok" => false, "error" => "Erreur: Utilisateur non connecté"]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $stopId = filter_input(INPUT_GET, 'stop_id', FILTER_VALIDATE_INT);
    
    if (is_null($stopId)) {
        $accommodations = getAllAccommodations($pdo);
    } else {
        $accommodations = getAccommodationsByStop($pdo, $stopId);
    }

    if (!$accommodations) {
        echo json_encode(["ok" => false, "error" => "Pas d'hébergements ou mauvaise réponse", "response" => $accommodations]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $_SESSION['user_id'],
        "accommodations" => $accommodations
    ]);
}

exit();

?>