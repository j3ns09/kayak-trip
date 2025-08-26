<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $code = filter_input(INPUT_GET, 'code', FILTER_DEFAULT);

    if (!is_null($code)) {
        $discount = getPromotion($pdo, $code);
        if (!$discount) {
            echo json_encode(["ok" => false, "error" => "Pas de promotion de ce nom ou mauvaise réponse", "response" => $discount, "discount" => ""]);
            exit();
        }

        echo json_encode([
            "ok" => true,
            "waiter" => $_SESSION['user_id'],
            "discount" => $discount
        ]);
        exit();
    }

    
    $discounts = getAllPromotions($pdo);

    if (!$discounts) {
        echo json_encode(["ok" => false, "error" => "Pas de promotions ou mauvaise réponse", "response" => $discounts, "discounts" => ""]);
        exit();
    }

    echo json_encode([
        "waiter" => $_SESSION['user_id'],
        "discounts" => $discounts
    ]);
}

exit();

?>