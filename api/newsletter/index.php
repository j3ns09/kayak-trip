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
    
    $id = $data['id'] ?? null;

    if (is_null($id)) {
        echo json_encode(["ok" => false, "error" => "Id nul"]);
        exit();
    }
    
    createSubscriber($pdo, $id);
    echo json_encode(["ok" => true]);
    exit();
} else {
    echo json_encode(['error' => 'Mauvaise méthode HTTP']);
    exit();
}

exit();

?>