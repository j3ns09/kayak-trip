<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
    $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($id && $name && $latitude !== false && $longitude !== false && $description) {
        $ok = setStopNewValues($pdo, $id, $name, $latitude, $longitude, $description);
        if ($ok) {
            redirectAlert('success', 'Le point d\'arrêt a bien été modifié !', 'admin/dashboard');
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