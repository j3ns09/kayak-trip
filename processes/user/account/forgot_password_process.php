<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    setSession("error", "Méthode non autorisée.");
    redirect("forgot");
    exit();
}

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

if (!$email) {
    setSession("error", "Adresse email invalide.");
    redirect("forgot");
    exit();
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmt->execute([":email" => $email]);
$userId = $stmt->fetch(PDO::FETCH_COLUMN);

if (!$userId) {
    setSession("error", "Aucun compte trouvé avec cet email.");
    redirect("forgot");
    exit();
}

$token = bin2hex(random_bytes(32));

$stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token) VALUES (:user_id, :token)");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindParam(':token', $token, PDO::PARAM_STR);
$stmt->execute();

$resetLink = "{$_SERVER['HTTP_HOST']}/reset.php?token=" . $token;

$subject = "Réinitialisation de votre mot de passe";
$message = "Bonjour,\n\nCliquez sur ce lien pour réinitialiser votre mot de passe : \n$resetLink\n\n Merci !";
sendMailKayak($email, $subject, $message);

setSession("success", "Un lien de réinitialisation vous a été envoyé par email.");
redirect("forgot");
exit();
