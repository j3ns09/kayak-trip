<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

if (!existsSession('user_id')) {
    header("Location: /login.php");
    exit();
}

$packId = filter_input(INPUT_POST, 'pack_id', FILTER_VALIDATE_INT);

if (!$packId) {
    header("Location: /packs.php");
    exit();
}

$pack = getPack($pdo, $packId);

if (!$pack) {
    header("Location: /packs.php");
    exit();
}

$_SESSION['cart_items'] = [
    'pack_id' => $pack['id'],
    'pack_name' => $pack['name'],
    'duration' => $pack['duration'],
    'price' => $pack['price'],
    'stops' => $pack['stops'],
    'options' => $pack['services'],
    'person_count' => 1,
    'desired_time' => [
        'duration' => $pack['duration'],
        'dates' => [date('Y-m-d'), date('Y-m-d', strtotime("+{$pack['duration']} days"))]
    ]
];

header("Location: /cart.php");
exit();
