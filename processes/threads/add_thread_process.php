<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if (!isset($_SESSION['user_id'])) {
    redirectAlert('not-connected', 'Veuillez vous connecter ou créer un compte afin d\'accéder à ce service', 'index');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION['user_id'];
    createThread($pdo, $userId);
    // redirect(le lien vers chat.php);
}

redirect("index");
exit();

?>