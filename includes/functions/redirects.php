<?php

function redirect(string $locationFromRoot) {
    header("Location: /" . $locationFromRoot . ".php");
    exit();
}

function redirectAlert(string $alertName, string $errorMessage, string $locationFromRoot) {
    $_SESSION[$alertName] = $errorMessage;
    header("Location: /" . $locationFromRoot . ".php");
    exit();
}

?>