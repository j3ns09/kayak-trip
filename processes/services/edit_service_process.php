<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
    $description = filter_input(INPUT_POST, 'description', FILTER_DEFAULT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

    if ($id && $name && $price && $description) {
        setServiceNewValues($pdo, $id, $name, $description, $price);
    } else {
        echo "Erreur : certaines données sont invalides ou manquantes.";
    }
}

redirect("admin/dashboard");
exit();

?>