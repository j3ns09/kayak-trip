<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $packId = filter_input(INPUT_GET, 'pack_id', FILTER_VALIDATE_INT);
    
    if (!$packId) {
        $packs = getAllPacks($pdo);
        if (!$packs) {
            echo json_encode(["ok" => false, "error" => "Pas de packs ou mauvaise réponse", "response" => $packs]);
            exit();
        }
        echo json_encode([
            "ok" => true,
            "waiter" => $_SESSION['user_id'],
            "packs" => $packs
        ]);
    } else {
        $pack = getPack($pdo, $packId);
        if (!$pack) {
            echo json_encode(["ok" => false, "error" => "Pack introuvable"]);
            exit();
        }
        echo json_encode([
            "ok" => true,
            "waiter" => $_SESSION['user_id'],
            "pack" => $pack
        ]);
    }
}

exit();

?>