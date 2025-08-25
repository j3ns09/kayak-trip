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
} else {
    echo json_encode(["ok" => false, "error" => "Erreur: Utilisateur non connecté"]);
    exit();
}

if ($method === "GET") {
} elseif ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    echo json_encode(["ok" => true]);
    setSession('order', $input);
    exit();
}

exit();

?>
