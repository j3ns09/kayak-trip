<?php

session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($firstName) || empty($lastName) || empty($email)) {
        redirectAlert('error', 'Veuillez remplir tous les champs obligatoires.', 'profile');
    } elseif (is_null($email)) {
        redirectAlert('error', 'Adresse email invalide.', 'profile');
    } else {
        $ok = setUserNewValues($pdo, $id, $firstName, $lastName, $email, $phone, $password, $passwordConfirm);

        if ($ok) {
            redirectAlert('success', 'Vos modification ont bien été enregistrés !', 'profile');
            exit();
        }

        redirectAlert('error', 'Erreur dans l\'enregistrement des modifications', 'profile');
        redirect('profile');
        exit();
    }
}

exit();

?>