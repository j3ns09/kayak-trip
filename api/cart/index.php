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
    
    $desired_time = $data['desiredTime'] ?? null;
    $person_count = $data['personCount'] ?? null;
    $bagage = $data['bagage'] ?? null;
    $food = $data['food'] ?? null;
    $location = $data['location'] ?? null;
    
    $result = [
        "ok" => true,
        "desired_time" => $desired_time,
        "person_count" => $person_count,
        "bagage" => $bagage,
        "food" => $food,
        "location" => $location
    ];

    if (is_null($desired_time) || is_null($bagage) || is_null($food) || is_null($location) || is_null($person_count)) {
        $result['ok'] = false;
        echo json_encode($result);
        exit();
    }
    

    
    $_SESSION['cart_items'] = $result;
    echo json_encode($result);

    exit();
} else {
    echo json_encode(['error' => 'mauvaise méthode']);
    exit();
}

exit();

?>