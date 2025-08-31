<?php

session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';

if (!existsSession('user_id') && !isAdmin($pdo, getSession('user_id'))) {
    redirectAlert('error', 'Accès non autorisé', 'admin/dashboard');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $newRights = filter_input(INPUT_POST, 'new_droits', FILTER_VALIDATE_INT) ?? 0;

    if (empty($id)) {
        redirectAlert('error', 'Veuillez remplir tous les champs obligatoires.', 'admin/dashboard');
    } else {
        $ok = setUserAdmin($pdo, $id, $newRights);

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