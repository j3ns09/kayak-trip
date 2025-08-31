<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $subs = getAllSubscribers($pdo);

    if (!$subs) {
        echo json_encode(["ok" => false, "error" => "Pas d'abonnés ou mauvaise réponse", "response" => $subs, "subs" => ""]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $_SESSION['user_id'],
        "subs" => $subs
    ]);
    exit();
}

exit();

?>