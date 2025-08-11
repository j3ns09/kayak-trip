<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = filter_input(INPUT_POST, "nom_etape", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "latitude", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, "longitude", FILTER_VALIDATE_FLOAT);

    $data = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }

    unset($_SESSION['form_data']);

    createService($pdo, $name, $description, $price);
}

redirect("admin/dashboard");
exit();

?>