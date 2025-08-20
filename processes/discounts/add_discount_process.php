<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = filter_input(INPUT_POST, "code", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_start = filter_input(INPUT_POST, "discount-start", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $date_end = filter_input(INPUT_POST, "discount-end", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "description", FILTER_DEFAULT);
    $reduction = filter_input(INPUT_POST, "reduction", FILTER_VALIDATE_INT);
    
    $unique_use = isset($_POST["discount-use"]) ? 1 : 0;

    
    $data = [
        'code' => $code,
        'description' => $description,
        'reduction' => $reduction,
        'unique_use' => $unique_use,
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (!isset($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }
    
    unset($_SESSION['form_data']);
    
    createDiscount($pdo, $code, $date_start, $date_end, $description, $reduction, $unique_use);
    exit();
}

redirect("admin/dashboard");
exit();

?>