<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {    
    $discounts = getAllPromotions($pdo);

    if (!$discounts) {
        echo json_encode(["state" => "Pas de promotions ou mauvaise réponse", "response" => $discounts, "discounts" => ""]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "discounts" => $discounts
    ]);
}

exit();

?>