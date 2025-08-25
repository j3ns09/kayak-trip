<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = filter_input(INPUT_POST, "nom_etape", FILTER_SANITIZE_EMAIL);
    $lat = filter_input(INPUT_POST, "latitude", FILTER_VALIDATE_FLOAT);
    $lng = filter_input(INPUT_POST, "longitude", FILTER_VALIDATE_FLOAT);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $data = [
        'name' => $name,
        'lat' => $lat,
        'lng' => $lng,
        'description' => $description
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }

    unsetSession('form_data');

    createStop($pdo, $name, $lat, $lng, $description);
}

redirect("admin/dashboard");
exit();

?>