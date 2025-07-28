<?php

function isOnline(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT is_online FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) === 1;
}

function isAdmin(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT is_admin FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) === 1;
}

?>