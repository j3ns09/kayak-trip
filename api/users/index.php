<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (isset($_SESSION['user_id'])) {
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
    $users = getAllUsers($pdo);

    if (!$users) {
        echo json_encode(["ok" => false, "error" => "Pas de users ou mauvaise réponse", "response" => $users]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "users" => $users
    ]);
}

exit();

?>