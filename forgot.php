<?php
session_start();
include_once 'includes/config/config.php';
include_once 'includes/functions.php';

include_once 'includes/templates/header.html';
include_once 'includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/login.css">

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-card shadow-lg p-4">
        <h3 class="fw-bold mb-4 text-center">Mot de passe oublié</h3>

        <form method="POST" action="/processes/user/account/forgot_password_process.php">
            <div class="mb-3">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control" placeholder="Votre email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                Envoyer le lien de réinitialisation
            </button>
        </form>
    </div>
</div>

<?php
if (existsSession("error")) {
    displayToast("errorToast", "danger", "Erreur", "Maintenant", getSession('error'));
    unsetSession('error');
}
if (existsSession("success")) {
    displayToast("successToast", "success", "Succès", "Maintenant", getSession('success'));
    unsetSession('success');
}

include_once 'includes/templates/footer.php';
?>
