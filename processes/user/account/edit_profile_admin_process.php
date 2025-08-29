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
        redirectAlert('error', 'Veuillez remplir tous les champs obligatoires.', 'admin/dashboard');
    } elseif (is_null($email)) {
        redirectAlert('error', 'Adresse email invalide.', 'admin/dashboard');
    } elseif (emailExists($pdo, $email)) {
        redirectAlert('error', 'L\'email sélectionné est déjà utilisé.', 'admin/dashboard');
    } else {
        $ok = setUserNewValuesAdmin($pdo, $id, $firstName, $lastName, $email, $phone);

        if ($ok) {
            redirectAlert('success', 'Vos modification ont bien été enregistrés !', 'admin/dashboard');
            exit();
        }

        redirectAlert('error', 'Erreur dans l\'enregistrement des modifications', 'admin/dashboard');
        exit();
    }
}

exit();

?>