<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

// TODO : Changer les chemins à la page correspondante pour l'admin / personne qui s'occupe des threads
// Pour l'instant mit à index.php

if (!isset($_SESSION['user_id'])) {
    redirectAlert('not-connected', 'Veuillez vous connecter ou créer un compte afin d\'accéder à ce service', 'index');
    exit();
}

// TODO : Vérification si admin / personne qui s'occupe des threads



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $threadId = filter_input(INPUT_POST, 'threadId', FILTER_VALIDATE_INT);

    if (is_null($threadId)) {
        redirectAlert('error', 'Cet id de thread n\'existe pas.', 'index')
        exit();
    }
    
    setThreadClosed($pdo, $threadId);
    redirect('success', 'Thread fermé.', 'index');
    exit();
}

redirect("index");
exit();

?>