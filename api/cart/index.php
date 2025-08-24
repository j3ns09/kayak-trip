<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header('Content-Type: application/json');


$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $rawData = file_get_contents("php://input");
    
    $data = json_decode($rawData, true);
    
    if (!$data) {
        echo json_encode(["ok" => false, "error" => "Données invalides"]);
        exit;
    }
    
    $stops = $data['stops'] ?? null;
    $desired_time = $data['desiredTime'] ?? null;
    $person_count = $data['personCount'] ?? null;
    $options = $data['options'] ?? null;
    
    $result = [
        "ok" => true,
        "stops" => $stops,
        "desired_time" => $desired_time,
        "person_count" => $person_count,
        "options" => $options
    ];

    if (is_null($desired_time) || is_null($person_count) || is_null($options)) {
        $result['ok'] = false;
        echo json_encode($result);
        exit();
    }
    

    
    $_SESSION['cart_items'] = $result;
    echo json_encode($result);

    exit();
} else if ($method === 'GET') {
    if (isset($_SESSION['cart_items'])) {
        echo json_encode(["ok" => true] + $_SESSION['cart_items']);
        exit();
    }

    echo json_encode(["ok" => false, "error" => "Panier vide"]);

    exit();

} else {
    echo json_encode(['error' => 'mauvaise méthode']);
    exit();
}

exit();

?>