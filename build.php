<?php
include_once 'includes/store.php';
include_once 'includes/config/config.php';
include_once 'includes/functions.php';
include_once 'includes/templates/header.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);

$stops = getAllStops($pdo);
?>

<link rel="stylesheet" href="/src/css/compose.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand ms-3 fs-2 title" href="/"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
        <div class="ms-auto mb-3 me-2">
            <?php if ($isConnected): ?>
                <p class="text-white px-3 py-2 rounded fs-5 my-0 shadow-sm">
                    Bonjour, <span class="fw-bold"><?= strtoupper($userInfo['last_name']) . " " . $userInfo['first_name'] ?></span>
                </p>
            <?php else: ?>
                <a href="login.php" class="btn btn-warning text-white fs-5 fw-bold">
                    Se connecter
                </a>
            <?php endif; ?>
        </div>
        <button class="navbar-toggler mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div id="main" class="container pb-4 mx-5">
    <h1 class="mb-4">Composez votre itinéraire en kayak</h1>

    <div class="row mb-4">
        <div class="col-md-4 steps-list bg-dark bg-opacity-50 p-3 rounded">
            <h4>Sélectionnez vos étapes</h4>
            <form id="route-form" class="p-3 border border-dark-subtle border-opacity-10 rounded bg-dark bg-opacity-75">
                <div class="mb-3">
                    <label class="form-label fw-bold">Sélectionnez les arrêts :</label>
                    <div class="row row-cols-1 row-cols-md-2 g-2">
                        <?php foreach ($stops as $stop): ?>
                            <div class="form-check col">
                                <input class="form-check-input" type="checkbox" name="stops[]" 
                                    value="<?= htmlspecialchars($stop['id']) ?>"
                                    id="stop-<?= htmlspecialchars($stop['id']) ?>"
                                    data-lat="<?= htmlspecialchars($stop['latitude']) ?>"
                                    data-lng="<?= htmlspecialchars($stop['longitude']) ?>">
                                <label class="form-check-label" for="stop-<?= htmlspecialchars($stop['id']) ?>">
                                    <?= htmlspecialchars($stop['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">Valider mon itinéraire</button>
            </form>
        </div>

        <div class="col-md-8">
            <div id="map">

            </div>
        </div>
    </div>

    <div id="route" class="bg-dark bg-opacity-50 rounded p-4 mt-4 text-light">
        <h4 class="mb-3">Votre itinéraire</h4>

        <div id="route-summary" class="mb-3">
            <div class="container">
                <div class="stepper">
                    <div class="d-flex flex-column align-items-center">
                        <div class="step completed">1</div>
                        <div class="step-label">Étape 1</div>
                    </div>
                        <div class="d-flex flex-column align-items-center">
                        <div class="step active">2</div>
                    <div class="step-label">Étape 2</div>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <div class="step">3</div>
                        <div class="step-label">Étape 3</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="route-details" class="mb-3">
            <p><strong>Distance totale :</strong> <span id="total-distance">0 km</span></p>
            <p><strong>Temps estimé :</strong> <span id="estimated-time">0h00</span></p>
        </div>

        <div class="d-flex gap-2">
            <button id="edit-route" class="btn btn-warning">Modifier l’itinéraire</button>
            <button id="finalize-route" class="btn btn-primary">Finaliser</button>
        </div>
    </div>
</div>

<?php include_once 'includes/templates/offcanvas.php'; ?>

<?php

if (isset($_SESSION['event'])) {
    if ($_SESSION['event'] === 'logout') {
        displayToast("logoutToast", "danger", "Déconnexion", "Maintenant", "Déconnexion réussie.");
    } else if ($_SESSION['event'] === 'login') {
        displayToast("loginToast", "success", "Connexion", "Maintenant", "Vous êtes maintenant connecté.");
    }
    
    unset($_SESSION['event']);
}

?>

<script type="module" src="/src/js/compose/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<?php include_once "includes/templates/footer.php"; ?>
