<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

$prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$tel = filter_input(INPUT_POST, "tel", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$passwordConfirm = filter_input(INPUT_POST, "password_confirm", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data = [
    'prenom' => $prenom,
    'nom' => $nom,
    'email' => $email,
    'tel' => $tel
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

$result = createUser($pdo, $nom, $prenom, $tel, $email, $password, $passwordConfirm);

$ok = $result['ok'];
$message = $result['message'];

if ($ok) {
    unset($_SESSION['form_data']);
    redirectAlert('success', 'Compte crée avec succès', 'login');
}

redirectAlert('error', $message, 'register');
exit();

?>