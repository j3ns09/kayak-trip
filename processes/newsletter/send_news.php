<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $object = filter_input(INPUT_POST, "object", FILTER_DEFAULT);
    $content = filter_input(INPUT_POST, "content", FILTER_DEFAULT);

    $subs = getAllSubscribers($pdo);

    foreach ($subs as $sub) {
        $ok = sendMailKayak($sub['email'], $object, $content);

        if (!$ok) {
            redirectAlert('error', 'Erreur dans l\'envoi du/des mails', 'admin/dashboard');
            exit();
        }
    }
}

redirect("admin/dashboard");
exit();

?>