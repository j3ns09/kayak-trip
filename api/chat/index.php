<?php

session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $threadId = (int)$input['threadId'];


    if (!existsSession('user_id') || !isAdmin($pdo, $_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Non autorisé']);
        exit;
    }

    $ok = setThreadClosed($pdo, $threadId);
    
    if ($ok) {
        echo json_encode(['ok' => true]);
        exit();
    }
    
    echo json_encode(["ok" => false, "error" => "Erreur lors de la création du thread."]);
    exit();
}

