<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = filter_input(INPUT_POST, "name", FILTER_DEFAULT);
    $duration = filter_input(INPUT_POST, "duration", FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_DEFAULT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stops = filter_input(INPUT_POST, 'stop_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $accommodations = filter_input(INPUT_POST, 'accommodation_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $data = [
        'name' => $name,
        'duration' => $duration,
        'description' => $description,
        'price' => $price,
        'stops' => $stops,
        'accommodations' => $accommodations
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }

    unsetSession('form_data');

    $ok = createPack($pdo, $name, $duration, $description, $price, $stops, $accommodations);

    if ($ok) {
        redirectAlert('success', 'Le pack a bien été crée !', 'admin/dashboard');
        exit();
    }
    
    redirectAlert('error', 'Erreur dans l\'enregistrement du service', 'admin/dashboard');
    exit();
}
exit();

?>