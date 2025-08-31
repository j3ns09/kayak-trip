<?php
session_start();
include_once '../../../includes/config/config.php';
include_once '../../../includes/functions.php';

if (!existsSession("user_id")) {
    redirectAlert('error', 'Vous devez être connecté pour effectuer cette action.', 'login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['id']);

    if ($userId !== getSession('user_id') && !isAdmin($pdo, $userId)) {
        redirectAlert('error', 'Action non autorisée.', 'profile');
        exit();
    }

    $ok = deleteUser($pdo, $userId);

    if ($ok) {
        deleteSession();
        redirectAlert('success', 'Votre compte a été clôturé avec succès.', 'index');
        exit();
    }

    redirectAlert('error', 'Une erreur est survenue lors de la suppression du compte.', 'profile');
    exit();

} else {
    setSession("error", "Requête invalide.");
    redirect("/profile.php");
    exit();
}

exit();
?>
