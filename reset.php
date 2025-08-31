<?php
session_start();
include_once 'includes/config/config.php';
include_once 'includes/functions.php';

$token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

if (!$token) {
    redirect("login");
}

include_once 'includes/templates/header.html';
?>

<link rel="stylesheet" href="/src/css/login.css">


<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="login-card shadow-lg p-4">
        <h3 class="fw-bold mb-4 text-center">Réinitialiser le mot de passe</h3>

        <form method="POST" action="/processes/user/account/reset_password_process.php">
            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Réinitialiser</button>
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