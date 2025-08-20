<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $services = getAllServices($pdo);

    if (!$services) {
        echo json_encode(["ok" => false, "error" => "Pas de services ou mauvaise réponse", "response" => $services]);
        exit();
    }

    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            "ok" => true,
            "waiter" => $_SESSION['user_id'],
            "services" => $services
        ]);
        exit();
    }
    
    echo json_encode([
        "ok" => true,
        "services" => $services
        ]);
}

exit();

?>