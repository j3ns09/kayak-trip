<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = filter_input(INPUT_POST, "code", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($code) {
        $ok = deleteDiscount($pdo, $code);
        if ($ok) {
            redirectAlert('success', 'Le code promo a bien été supprimé !', 'admin/dashboard');
            exit();
        }
        
        redirectAlert('error', 'Erreur dans l\'enregistrement des modifications', 'admin/dashboard');
        exit();
    }
}

redirect("admin/dashboard");
exit();

?>