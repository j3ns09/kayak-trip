<?php 

include_once 'includes/store.php';
include_once 'includes/functions.php';
include_once 'includes/shape/header.php';

$formData = $_SESSION['form_data'];
?>

<link rel="stylesheet" href="includes/styles/login.css">

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold ms-3" href="/"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
    </div>
</nav>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card shadow-lg">
        <?php
            if (isset($_SESSION['error'])) {
                displayAlert('error', 1);
            }
        ?>
        <h2 class="mb-4 text-center fw-semibold">Inscription</h2>
        <form action="processes/access/register_process.php" method="POST">
            
            <div class="row mb-3">
                <div class="col">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" value="<?= $formData['nom'] ?? '' ?>" required>
                </div>
                <div class="col">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" value="<?= $formData['prenom'] ?? '' ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="tel" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control" id="tel" name="tel" placeholder="Entrez votre numéro" value="<?= $formData['tel'] ?? '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre e-mail" value="<?= $formData['email'] ?? '' ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <div class="mb-4">
                <label for="password_confirm" class="form-label">Confirmer mot de passe</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Mot de passe à nouveau" required>
            </div>

            <button type="submit" class="btn btn-warning w-100">S'inscrire</button>
            <div class="mt-3 text-center">
                <a href="login.php" class="text-light text-decoration-underline">Vous possédez déjà un compte ?</a>
            </div>
        </form>
    </div>
</div>

<?php

unset($_SESSION['form_data']);
include_once "includes/shape/footer.php";

?>
