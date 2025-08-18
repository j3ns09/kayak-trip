<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["ok" => false, "error" => "Utilisateur non connecté"]);
    exit();
}

$requesterId = $_SESSION['user_id'];
$isAdmin = getApiKey($pdo, $requesterId) !== false;

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $userId = filter_input(INPUT_GET, 'userId', FILTER_VALIDATE_INT);

    if ($userId) {
        if ($userId !== $requesterId) {
            echo json_encode(["ok" => false, "error" => "Accès interdit à ce thread."]);
            exit();
        }

        $thread = getOpenThreadFromUser($pdo, $userId);

        if (!$thread) {
            echo json_encode(["ok" => false, "error" => "Aucun thread ouvert trouvé."]);
            exit();
        }

        echo json_encode([
            "ok" => true,
            "threadId" => $thread['id'],
            "thread" => $thread
        ]);
        exit();
    }

    if (!$isAdmin) {
        echo json_encode(["ok" => false, "error" => "Accès réservé aux administrateurs."]);
        exit();
    }

    $threads = getAllThreads($pdo);

    if (!$threads) {
        echo json_encode(["ok" => false, "error" => "Aucun thread trouvé."]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "threads" => $threads
    ]);
    exit();

} else if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = (int)($input['userId'] ?? 0);
    
    if ($userId !== $requesterId) {
        echo json_encode(["ok" => false, "error" => "Accès interdit pour créer un thread pour un autre utilisateur."]);
        exit();
    }
    
    $existingThread = getOpenThreadFromUser($pdo, $userId);
    
    if ($existingThread) {
        echo json_encode([
            "ok" => true,
            "message" => "Thread déjà existant.",
            "threadId" => $existingThread['id'],
            "thread" => $existingThread
        ]);
        exit();
    }
    
    $newThreadId = createThread($pdo, $userId);
    
    if ($newThreadId) {
        echo json_encode([
            "ok" => true,
            "message" => "Nouveau thread créé.",
            "threadId" => $newThreadId
        ]);
        exit();
    }
    
    echo json_encode(["ok" => false, "error" => "Erreur lors de la création du thread."]);
    exit();
}


?>