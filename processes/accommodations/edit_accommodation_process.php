<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
    $description = filter_input(INPUT_POST, 'description', FILTER_DEFAULT);
    $stars = filter_input(INPUT_POST, 'stars', FILTER_VALIDATE_INT);

    if ($id && $name && $description && $stars) {
        $ok = setAccommodationNewValues($pdo, $id, $name, $description, $stars);
        if ($ok) {
            redirectAlert('success', 'L\'hébergement a bien été modifié !', 'admin/dashboard');
            exit();
        }
        
        redirectAlert('error', 'Erreur dans l\'enregistrement des modifications', 'admin/dashboard');
        exit();
    } else {
        redirectAlert('error', 'Certaines données sont invalides ou manquantes.', 'admin/dashboard');
        exit();
    }
}

redirect("admin/dashboard");
exit();

?>