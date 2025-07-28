<?php include_once 'includes/header.php'; ?>

<link rel="stylesheet" href="includes/styles/login.css">

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold ms-3" href="/"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
    </div>
</nav>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card shadow-lg">
        <h2 class="mb-4 text-center fw-semibold">Connexion</h2>
        <form action="processes/register/register_process.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre e-mail" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Se connecter</button>
        </form>
        <div class="mt-3 text-center">
            <a href="#" class="text-light text-decoration-underline">Mot de passe oublié ?</a>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
