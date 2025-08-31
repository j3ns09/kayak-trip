<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

if (!existsSession('user_id')) {
    redirect('login');
    exit();
}

if (!isVerified($pdo, getSession('user_id'))) {
    redirectAlert('error', 'Veuillez vérifier votre compte.', 'index');
}

$packId = filter_input(INPUT_POST, 'pack_id', FILTER_VALIDATE_INT);

if (!$packId) {
    redirect('packs');
    exit();
}

$pack = getPack($pdo, $packId);

if (!$pack) {
    redirect('packs');
    exit();
}

$_SESSION['cart_items'] = [
    'pack_id' => $pack['id'],
    'pack_name' => $pack['name'],
    'duration' => $pack['duration'],
    'price' => $pack['price'],
    'stops' => $pack['stops'],
    'options' => $pack['services'],
    'person_count' => $pack['person_count'],
    'desired_time' => [
        'duration' => $pack['duration'],
        'dates' => [date('Y-m-d'), date('Y-m-d', strtotime("+{$pack['duration']} days"))]
    ]
];

header("Location: /cart.php");
exit();
