<?php
session_start();
include_once 'includes/config/config.php';
include_once 'includes/functions.php';

if (existsSession('user_id')) {
    $userId = $_SESSION["user_id"];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    redirect('login');
}

$isConnected = !is_null($userId);
$userId = $_SESSION['user_id'];

include_once 'includes/templates/header.html';
include_once 'includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/profile.css">

<div class="container min-vh-100" style="margin-top:6rem; margin-bottom:60px;">
    <h3 class="fw-bold mb-4">Mon profil</h3>

    <form action="/processes/user/account/edit_profile_process.php" method="POST" class="card shadow-sm p-4">
        <div class="row mb-3">
            <input type="hidden" name="id" value="<?= $userId ?>">
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="first_name" class="form-control" value="<?= $userInfo['first_name'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="last_name" class="form-control" value="<?= $userInfo['last_name'] ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control" value="<?= $userInfo['email'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Numéro de téléphone</label>
                <input type="text" name="phone" class="form-control" value="<?= $userInfo['phone'] ?>" required>
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirm" class="form-control" placeholder="Confirmer le mot de passe">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Enregistrer
        </button>
    </form>
</div>

<script type="module" src="/src/js/profile/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>


<?php 

if (existsSession("error")) {
    displayToast(
        "errorToast",
        "danger",
        "Erreur",
        "Maintenant",
        getSession('error'),
    );
    unsetSession('error');
}

if (existsSession("success")) {
    displayToast(
        "successToast",
        "success",
        "Succès",
        "Maintenant",
        getSession('success'),
    );
    unsetSession('success');
}

include_once 'includes/templates/offcanvas.php';
include_once 'includes/templates/footer.php';
?>
