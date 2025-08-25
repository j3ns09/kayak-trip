<?php

include_once "includes/store.php";

include_once "includes/config/config.php";
include_once "includes/functions.php";

include_once "includes/templates/header.php";

if (existsSession('user_id')) {
    $userId = $_SESSION["user_id"];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);
?>

<link rel="stylesheet" href="/src/css/home.css">

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand ms-3 fs-2 title" href="/"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
        <div class="ms-auto mb-3 me-2">
            <?php if ($isConnected): ?>
                <p class="text-white px-3 py-2 rounded fs-5 my-0 shadow-sm">Bonjour, <span class="fw-bold"><?= strtoupper(
                    $userInfo["last_name"],
                ) .
                    " " .
                    $userInfo["first_name"] ?></span></p>

            <?php else: ?>
                <a href="login.php" class="btn btn-warning text-white fs-5">
                    <span class="fw-bolder">
                        Se connecter
                    </span>
                </a>
            <?php endif; ?>
        </div>
        <button class="navbar-toggler mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div id="main" class="container row align-items-center">
    <div class="container text-white ps-5">
        <div class="container ps-3 row gx-1 justify-content-around align-items-center">
            <div class="col-7 px-5">
                <h1 class="display-1 fw-semibold">Explorez la Loire en Kayak</h1>
                <p class="fst-italic fs-5">Composez votre itinéraire ou optez pour l'un de nos packs tout inclus</p>
            </div>

            <div class="col-5 row gx-5 ps-5">
                <div class="col">
                    <a href="build.php" class="btn text-white col fw-semibold rounded-5 home-buttons">Composer mon itinéraire</a>
                </div>
                <div class="col">
                    <a href="packs.php" class="btn text-white col fw-semibold rounded-5 home-buttons">Voir les packs disponibles</a>
                </div>
            </div>
            <!-- <hr class="border border-light border-2 opacity-100" style="width: 300px;"> -->
        </div>
    </div>
    <div class="container text-white ps-5">
        <div class="container ps-3 row gx-1 justify-content-around align-items-center">
            <div class="col-7 px-5">
                <h1 class="display-2 fw-semibold">Besoin d'aide ?</h1>
                <p>Discutez avec nos commerciaux</p>
            </div>
            <div class="col-5">
                <button id="chat-button" class="btn text-white fw-semibold rounded-5 home-buttons">Parler avec nos équipes</button>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/templates/offcanvas.php"; ?>

<?php 

if (existsSession("event")) {
    if ($_SESSION["event"] === "logout") {
        displayToast(
            "logoutToast",
            "danger",
            "Déconnexion",
            "Maintenant",
            "Déconnexion réussie.",
        );
    } elseif ($_SESSION["event"] === "login") {
        displayToast(
            "loginToast",
            "success",
            "Connexion",
            "Maintenant",
            "Vous êtes maintenant connecté.",
        );
    }

    unset($_SESSION["event"]);
}

if (existsSession("error")) {
    displayToast(
        "errorToast",
        "danger",
        "Erreur",
        "Maintenant",
        $_SESSION["error"],
    );
    unsetSession('error');
}

if ($isConnected) {
    include_once 'includes/templates/chat.php';
}
?>



<script type="module" src="/src/js/index/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php include_once "includes/templates/footer.php"; ?>
