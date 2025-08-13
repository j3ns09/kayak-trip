<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $input = json_decode(file_get_contents('php://input'), true);
    $type = trim($input['type'] ?? '');
    
    if (empty($type)) {
        $packs = getAllPacks($pdo);
    }

    if (!$packs) {
        echo json_encode(["ok" => false, "state" => "Pas de packs ou mauvaise réponse", "response" => $packs]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $_SESSION['user_id'],
        "packs" => $packs
    ]);
}

exit();

?>