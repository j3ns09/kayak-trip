<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST,"id", FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, "name", FILTER_DEFAULT);
    $duration = filter_input(INPUT_POST, "duration", FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_DEFAULT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    
    $data = [
        'name' => $name,
        'duration' => $duration,
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

    unsetSession('form_data');

    $ok = setPackNewValues($pdo, $id, $name, $duration, $description, $price);

    if ($ok) {
        redirectAlert('success', 'Le pack a bien été modifié !', 'admin/dashboard');
        exit();
    }
    
    redirectAlert('error', 'Erreur dans l\'enregistrement du point', 'admin/dashboard');
    exit();
}

redirect("admin/dashboard");
exit();

?>