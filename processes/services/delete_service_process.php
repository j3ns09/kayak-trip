<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($id) {
        $ok = deleteService($pdo, $id);
        if ($ok) {
            redirectAlert('success', 'Le service a bien été supprimé !', 'admin/dashboard');
            exit();
        }
        
        redirectAlert('error', 'Erreur dans l\'enregistrement des modifications', 'admin/dashboard');
        exit();
    }
}

redirect("admin/dashboard");
exit();

?>