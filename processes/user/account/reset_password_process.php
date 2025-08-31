<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    setSession("error", "Méthode non autorisée.");
    redirect("login");
    exit();
}

$token = $_POST['token'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirm = $_POST['password_confirm'] ?? null;

if (!$token || !$password || !$passwordConfirm) {
    setSession("error", "Données manquantes.");
    header("location: /reset.php?token=" . urlencode($token));
    exit();
}

if ($password !== $passwordConfirm) {
    setSession("error", "Les mots de passe ne correspondent pas.");
    header("location: /reset.php?token=" . urlencode($token));
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = :token LIMIT 1");
$stmt->execute([":token" => $token]);
$reset = $stmt->fetch();

if (!$reset) {
    setSession("error", "Lien invalide.");
    redirect("forgot");
    exit();
}

$userId = $reset['user_id'];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
$stmt->execute([":password" => $hashedPassword, ":id" => $userId]);

$stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
$stmt->execute([":token" => $token]);

setSession("success", "Votre mot de passe a été réinitialisé avec succès !");
redirect("login");
exit();

?>