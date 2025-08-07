<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_SESSION['user_id'])) {
    $key = getApiKey($pdo, $_SESSION['user_id']);
} else {
    echo json_encode(["state" => "Erreur: Utilisateur non connecté"]);
    exit();
}


if ($method === "GET") {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = trim($input['userId'] ?? '');
    
    if (!empty($userId)) {
        if ($_SESSION['user_id'] === $userId) {
            $thread = getThreadsFromUser($pdo, $userId);
        }
    } else {
        if ($key) {
            $thread = getAllThreads($pdo);
        } else {
            echo json_encode(["state" => "Erreur: Utilisateur non administrateur"]);
            exit();
        }
    }

    if (!$thread) {
        echo json_encode(["state" => "Pas de fil de message ou mauvaise réponse", "response" => $thread]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "threads" => $thread
    ]);
}

exit();

?>