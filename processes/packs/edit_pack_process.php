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
    $stops = filter_input(INPUT_POST, 'stop_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $accommodations = filter_input(INPUT_POST, 'accommodation_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $services = filter_input(INPUT_POST, 'service_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    $personCount = filter_input(INPUT_POST, 'person_count', FILTER_VALIDATE_INT);

    $data = [
        'name' => $name,
        'duration' => $duration,
        'description' => $description,
        'price' => $price,
        'stops' => $stops,
        'accommodations' => $accommodations,
        'services' => $services,
        'person_count' => $personCount
    ];

    $_SESSION['form_data'] = $data;

    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'admin/dashboard');
            exit();
        }
    }

    unsetSession('form_data');

    $ok = setPackNewValues($pdo, $id, $name, $duration, $description, $price, $stops, $accommodations, $services, $personCount);

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