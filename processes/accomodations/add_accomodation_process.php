<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = filter_input(INPUT_POST, "nom_hebergement", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_start = filter_input(INPUT_POST, "type_hebergement", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_end = filter_input(INPUT_POST, "arret", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "prix_base", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $reduction = filter_input(INPUT_POST, "description", FILTER_VALIDATE_INT);
    $unique_use = filter_input(INPUT_POST, "discount-use", FILTER_VALIDATE_INT);

    $data = [
        'code' => $code,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'description' => $description,
        'reduction' => $reduction,
        'unique_use' => $unique_use,
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }

    unsetSession('form_data');

    createDiscount($pdo, $code, $date_start, $date_end, $description, $reduction, $unique_use);
}

redirect("admin/dashboard");
exit();

?>