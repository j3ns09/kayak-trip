<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $orders = getAllOrders($pdo);

    if (!$orders) {
        echo json_encode(["ok" => false, "error" => "Pas de commandes ou mauvaise réponse", "response" => $orders]);
        exit();
    }

    if (existsSession('user_id')) {
        echo json_encode([
            "waiter" => $_SESSION['user_id'],
            "orders" => $orders
        ]);
        exit();
    }
    
    echo json_encode([
            "orders" => $orders
        ]);
}

exit();

?>