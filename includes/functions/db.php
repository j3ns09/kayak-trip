// set, get

<?php

function getDisplayableUserInfo(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT first_name, last_name, phone, email FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function setUserOnline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 1 WHERE id = $userId");
}

function setUserOffline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 0 WHERE id = $userId");
}



?>