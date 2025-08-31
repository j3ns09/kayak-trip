<?php

include_once 'includes/config/config.php';
include_once 'includes/functions.php';

$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($token) {
    $userId = hasToken($pdo, $token);

    if ($userId) {
        verifyUser($pdo, $userId);
        echo "Email vérifié avec succès. Vous pouvez maintenant vous connecter.";
    } else {
        echo "Lien de vérification invalide ou déjà utilisé.";
    }
} else {
    echo "Aucun token fourni.";
}

?>