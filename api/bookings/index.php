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
    if (empty($type)) {
        $bookings = getAllBookings($pdo);
    }

    if (!$bookings) {
        echo json_encode(["ok" => false, "error" => "Pas de commandes ou mauvaise réponse", "response" => $bookings]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $_SESSION['user_id'],
        "bookings" => $bookings
    ]);
}

exit();

?>