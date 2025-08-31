<?php
session_start();
include_once 'includes/config/config.php';
include_once 'includes/functions.php';

if (existsSession('user_id')) {
    $userId = $_SESSION["user_id"];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);

include_once 'includes/templates/header.html';
include_once 'includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/profile.css">

<div class="container min-vh-100" style="margin-top:6rem; margin-bottom:60px;">
    <h3 class="fw-bold mb-4">Mon profil</h3>

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="mb-3">Informations personnelles</h5>
        <form action="/processes/user/account/edit_profile_process.php" method="POST">
            <input type="hidden" name="id" value="<?= $userId ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($userInfo['first_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($userInfo['last_name']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userInfo['email']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Numéro de téléphone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($userInfo['phone']) ?>" required>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Sécurité</h5>
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

    <div class="card border-danger shadow-sm p-4">
        <h5 class="text-danger mb-3">Zone dangereuse</h5>
        <p class="text-muted">Une fois votre compte clôturé, toutes vos données seront supprimées définitivement.</p>
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="bi bi-x-circle"></i> Clôturer mon compte
        </button>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="/processes/user/account/delete_account_process.php">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Clôturer mon compte</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> votre compte ? Cette action est <span class="text-danger fw-bold">irréversible</span>.</p>
          <input type="hidden" name="id" value="<?= $userId ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-danger">Oui, supprimer mon compte</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="module" src="/src/js/profile/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

<?php 
if (existsSession("error")) {
    displayToast("errorToast","danger","Erreur","Maintenant",getSession('error'));
    unsetSession('error');
}
if (existsSession("success")) {
    displayToast("successToast","success","Succès","Maintenant",getSession('success'));
    unsetSession('success');
}
include_once 'includes/templates/offcanvas.php';
include_once 'includes/templates/footer.php';
?>
