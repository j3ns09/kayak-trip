<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . "/includes/config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'promo_nom', FILTER_DEFAULT);
    $discount = filter_input(INPUT_POST, 'promo_discount', FILTER_VALIDATE_INT);
    $accId = filter_input(INPUT_POST, 'acc_id', FILTER_VALIDATE_INT);
    $start_date = filter_input(INPUT_POST, 'promo_start_date', FILTER_DEFAULT);
    $end_date = filter_input(INPUT_POST, 'promo_end_date', FILTER_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO promotion_periods (name, accommodation_id, discount_value, start_date, end_date) 
        VALUES (:name, :acc_id, :discount, :start_date, :end_date)
    ");
    $stmt->execute([
        ":name" => $name,
        ":discount" => $discount,
        ":acc_id" => $accId,
        ":start_date" => $start_date,
        ":end_date" => $end_date
    ]);

    $_SESSION['success'] = "Promotion ajoutée avec succès.";
    header("Location: /admin/dashboard.php#promotions");
    exit();
}

exit();
?>