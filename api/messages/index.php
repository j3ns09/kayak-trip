<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$requester = null;

if (isset($_SESSION['user_id'])) {
    $requester = $_SESSION['user_id'];
    $key = getApiKey($pdo, $_SESSION['user_id']);
} else {
    echo json_encode(["state" => "Erreur: Utilisateur non connecté"]);
    exit();
}


if ($method === "GET") {
    $input = json_decode(file_get_contents('php://input'), true);
    $threadId = trim($input['threadId'] ?? '');
    
    if (empty($threadId)) {
        if (!$key) {
            echo json_encode(["state" => "Erreur: Utilisateur non administrateur"]);
            exit();
        }
        $messages = getAllMessages($pdo);
    } else {
        if (is_null($requester)) {
            echo json_encode(["state" => "Erreur: Fil de discussion non spécifié"]);
            exit();
        }

        // TODO : Rajouter une colonne ou une table pour le support d'aide (accès aux fils de discussion)
        // Ou utiliser admin ?
        $thread = getThread($pdo, $threadId);
        if (!$requester === $thread['user_id']) {
            echo json_encode(["state" => "Erreur: Accès au fil non autorisé"]);
            exit();
        }

        $messages = getAllMessagesFromThread($pdo, $threadId);        
    }


    if (!$messages) {
        echo json_encode(["state" => "Pas de messages ou mauvaise réponse", "response" => $messages]);
        exit();
    }

    echo json_encode([
        "waiter" => $requester,
        "messages" => $messages
    ]);
} else if ($method === "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $threadId = trim($input['threadId'] ?? '');
    $senderId = trim($input['userId'] ?? '')
    $message = trim($input['message'] ?? '');
      
}

exit();

?>