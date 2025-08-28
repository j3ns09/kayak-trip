<?php

session_start();

$root = $_SERVER["DOCUMENT_ROOT"];

include_once $root . "/includes/config/config.php";
include_once $root . "/includes/functions.php";

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];
$requester = null;

if (existsSession('user_id')) {
    $requester = $_SESSION["user_id"];
    $key = getApiKey($pdo, $_SESSION["user_id"]);
} else {
    echo json_encode(["ok" => false, "error" => "Erreur: Utilisateur non connecté"]);
    exit();
}

if ($method === "GET") {
    $input = json_decode(file_get_contents("php://input"), true);
    $userId = filter_input(INPUT_GET, 'userId', FILTER_VALIDATE_INT);
    $threadId = filter_input(INPUT_GET, 'threadId', FILTER_VALIDATE_INT);

    if (empty($threadId)) {
        if (!$key) {
            echo json_encode([
                "ok" => false, "error" => "Erreur: Utilisateur non administrateur",
            ]);
            exit();
        }
        $messages = getAllMessages($pdo);
    } else {
        if (is_null($requester)) {
            echo json_encode([
                "ok" => false, "error" => "Erreur: Fil de discussion non spécifié",
            ]);
            exit();
        }

        // TODO : Rajouter une colonne ou une table pour le support d'aide (accès aux fils de discussion)
        // Ou utiliser admin ?
        $thread = getThread($pdo, $threadId);
        if ($requester !== $thread["user_id"]) {
            echo json_encode(["ok" => false, "error" => "Erreur: Accès au fil non autorisé"]);
            exit();
        }

        $messages = getAllMessagesFromThread($pdo, $threadId);
    }

    if (!$messages) {
        echo json_encode([
            "ok" => false, "error" => "Pas de messages ou mauvaise réponse",
            "response" => $messages,
        ]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $requester,
        "messages" => $messages,
    ]);
} elseif ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $threadId = trim($input["threadId"] ?? "");
    $senderId = trim($input["userId"] ?? "");
    $message = trim($input["message"] ?? "");

    if (empty($senderId) || empty($threadId) || empty($message)) {
        echo json_encode(["ok" => false, "error" => "Données invalides"]);
        exit();
    }

    if (isAdmin($pdo, $senderId)) {
        createMessage($pdo, $threadId, 'admin', $senderId, $message);
        echo json_encode(["ok" => true]);
        exit();
    }

    createMessage($pdo, $threadId, 'client', $senderId, $message);
    echo json_encode(["ok" => true]);
}

exit();

?>
