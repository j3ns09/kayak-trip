<?php
session_start();
include_once 'includes/config/config.php';
include_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login');
}

if (isset($_GET['booking_id'])) {
    $bookingId = (int) $_GET['booking_id'];

    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookingId, $_SESSION['user_id']]);
    $order = $stmt->fetch();

    if ($order && !$order['is_paid']) {
        $update = $pdo->prepare("UPDATE bookings SET is_paid = 1 WHERE id = ?");
        $update->execute([$bookingId]);

        $_SESSION['flash_message'] = "Votre commande #$bookingId a bien été payée ✅";
    }
}

redirect('orders');
