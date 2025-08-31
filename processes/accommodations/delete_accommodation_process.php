<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

if (!existsSession("user_id")) {
    setSession("error", "Vous devez être connecté pour effectuer cette action.");
    redirect("/admin/accommodations.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    setSession("error", "Méthode non autorisée.");
    redirect("/admin/accommodations.php");
    exit();
}

$accommodationId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$accommodationId) {
    setSession("error", "ID d'hébergement invalide.");
    redirect("/admin/accommodations.php");
    exit();
}

$stmt = $pdo->prepare("
    DELETE ra FROM room_availability ra
    INNER JOIN rooms r ON ra.room_id = r.id
    WHERE r.accommodation_id = :acc_id
");
$stmt->bindValue(":acc_id", $accommodationId, PDO::PARAM_INT);
$stmt->execute();

$stmt = $pdo->prepare("DELETE FROM rooms WHERE accommodation_id = :acc_id");
$stmt->bindValue(":acc_id", $accommodationId, PDO::PARAM_INT);
$stmt->execute();

$stmt = $pdo->prepare("DELETE FROM accommodation_availability WHERE accommodation_id = :acc_id");
$stmt->bindValue(":acc_id", $accommodationId, PDO::PARAM_INT);
$stmt->execute();

$stmt = $pdo->prepare("DELETE FROM accommodations WHERE id = :id");
$stmt->bindValue(":id", $accommodationId, PDO::PARAM_INT);
$stmt->execute();

setSession("success", "Hébergement supprimé avec succès !");

redirect("admin/dashboard");
exit();
