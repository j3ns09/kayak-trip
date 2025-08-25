<?php

function isOnline(PDO $pdo, int $userId) : bool {
    $r = $pdo->query("SELECT is_online FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) == 1;
}

function isAdmin(PDO $pdo, int $userId) : bool {
    $r = $pdo->query("SELECT is_admin FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) == 1;
}

function userExistsPasswordCorrect(PDO $pdo, string $email, string $password) : bool {
    $r = $pdo->prepare("SELECT password FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    $r->execute();

    $psw = $r->fetch(PDO::FETCH_COLUMN);
    return ($psw && password_verify($password, $psw));
}

function getSession(string $key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

function existsSession(string $key) : bool {
    return isset($_SESSION[$key]);
}

function setSession(string $key, $value) : void {
    $_SESSION[$key] = $value;
}

function unsetSession(string $key) : void {
    unset($_SESSION[$key]);
}

?>