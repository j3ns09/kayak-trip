<?php

// set, get, create, delete

function setUserOnline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 1 WHERE id = $userId");
}

function setUserOffline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 0 WHERE id = $userId");
}

function getUserId(PDO $pdo, string $email) {
    $r = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getDisplayableUserInfo(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT first_name, last_name, phone, email FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function createUser(
    PDO $pdo,
    string $name,
    string $firstName,
    string $phone,
    string $email,
    string $password,
    string $confirmPassword
    )
{
    if (!$firstName || !$name || !$email || !$phone || !$password || !$confirmPassword) {
        return ['ok' => false, 'message' => "Tous les champs sont obligatoires."];
    } else if ($password !== $confirmPassword) {
        return ['ok' => false, 'message' => "Les mots de passe ne correspondent pas."];
    }

    $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        return ['ok' => false, 'message' => "Email déjà utilisé."];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = 
    "INSERT INTO users 
    (email, password, first_name, last_name, phone) 
    VALUES 
    (:email, :password, :first_name, :last_name, :phone)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

    if ($stmt->execute()) return ['ok' => true, 'message' => ""];

    return ['ok' => false, 'message' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."];

}

?>