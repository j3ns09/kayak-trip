<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (!existsSession("user_id") || !isAdmin($pdo, $_SESSION['user_id'])) {
    setSession("error", "Accès non autorisé.");
    echo json_encode(["ok" => false]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = filter_input(INPUT_POST, 'nom_hebergement', FILTER_DEFAULT);
    $stars           = filter_input(INPUT_POST, 'stars', FILTER_VALIDATE_INT);
    $stopId         = filter_input(INPUT_POST, 'arret', FILTER_VALIDATE_INT);
    $description    = filter_input(INPUT_POST, 'description', FILTER_DEFAULT);
    $chambres       = $_POST['chambres'] ?? [];

    if (empty($nom) || empty($stars) || empty($stopId) || empty($description)) {
        setSession("error", "Tous les champs sont obligatoires.");
        echo json_encode(["ok" => false, "message" => "Tous les champs sont obligatoires."]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO accommodations (name, stars, stop_id, description)
        VALUES (:name, :stars, :stop_id, :description)
    ");
    $stmt->execute([
        ':name' => $nom,
        ':stars' => $stars,
        ':stop_id' => $stopId,
        ':description' => $description
    ]);

    $accommodationId = $pdo->lastInsertId();

    if (!empty($chambres)) {
        $stmtRoom = $pdo->prepare("
            INSERT INTO rooms (accommodation_id, room_name, capacity, base_price)
            VALUES (:accommodation_id, :room_name, :capacity, :base_price)
        ");
        foreach ($chambres as $chambre) {
            $stmtRoom->execute([
                ':accommodation_id' => $accommodationId,
                ':room_name' => $chambre['room_name'],
                ':capacity' => $chambre['capacity'],
                ':base_price' => $chambre['base_price']
            ]);
        }
    }


    setSession("success", "Hébergement ajouté avec succès !");
    echo json_encode(["ok" => true, "message" => "Hébergement ajouté avec succès !"]);
    exit;

} else {
    setSession("error", "Requête invalide.");
    echo json_encode(["ok" => false, "message" => "Requête invalide."]);
    exit;
}

exit();
?>