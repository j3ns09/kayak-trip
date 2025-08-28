<?php

function isOnline(PDO $pdo, int $userId) : bool {
    $r = $pdo->query("SELECT is_online FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) == 1;
}

function isAdmin(PDO $pdo, int $userId) : bool {
    $r = $pdo->query("SELECT is_admin FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN) == 1;
}

function isPasswordCorrect(PDO $pdo, int $userId, string $password) : bool {
    $r = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $r->bindParam(':id', $userId, PDO::PARAM_INT);
    $r->execute();

    $psw = $r->fetch(PDO::FETCH_COLUMN);
    return (password_verify($password, $psw));
}

function userExistsPasswordCorrect(PDO $pdo, string $email, string $password) : bool {
    $r = $pdo->prepare("SELECT password FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    $r->execute();

    $psw = $r->fetch(PDO::FETCH_COLUMN);
    return ($psw && password_verify($password, $psw));
}

function bookingExists(PDO $pdo, int $userId, string $startDate, string $endDate) : bool {
    $startDate = DateTime::createFromFormat('d/m/Y', $startDate);
    $endDate = DateTime::createFromFormat('d/m/Y', $endDate);

    if (!$startDate || !$endDate) {
        return false;
    }

    $startDate = $startDate->format('Y-m-d');
    $endDate = $endDate->format('Y-m-d');

    $sql = "SELECT id from bookings WHERE user_id = :user_id AND start_date = :start_date AND end_date = :end_date";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);

    $stmt->execute();

    return $stmt->fetch() !== false;
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