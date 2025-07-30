<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data = [
    'email' => $email,
];

$_SESSION['form_data'] = $data;

if (empty($password)) {
    redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'register');
    exit();
}
foreach ($data as $key => $value) {
    if (empty($value)) {
        redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'register');
        exit();
    }
}

if (userExistsPasswordCorrect($pdo, $email, $password)) {
    unset($_SESSION['form_data']);
    $_SESSION['user_id'] = getUserId($pdo, $email);
    redirect('home');
    exit();
} else {
    redirectAlert('error', 'Adresse e-mail ou mot de passe incorrect', 'login');
    exit();
}

exit();

?>