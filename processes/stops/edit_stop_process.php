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
        setStopNewValues($pdo, $id, $name, $latitude, $longitude, $description);
    } else {
        echo "Erreur : certaines données sont invalides ou manquantes.";
    }
}

redirect("admin/dashboard");
exit();

?>