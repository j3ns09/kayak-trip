<?php

function redirect(string $locationFromRoot) {
    header("Location: /" . $locationFromRoot . ".php");
    exit();
}

function redirectAlert(string $alertName, string $errorMessage, string $location) {
    $_SESSION[$alertName] = $errorMessage;
    header("Location: /" . $location . ".php");
    exit();
}

?>