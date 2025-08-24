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

<link rel="stylesheet" href="/src/css/build.css">
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
    <h1 class="mt-2 mb-4">Composez votre itinéraire en kayak</h1>

    <div class="row mb-4">
        <div class="col-md-4 steps-list bg-dark bg-opacity-50 p-3 rounded">
            <h4>Sélectionnez vos étapes</h4>
            <form id="route-form" class="p-3 rounded bg-dark bg-opacity-75">
                <div class="mb-3">
                    <label class="form-label fw-bold">Sélectionnez les arrêts :</label>
                    <div class="row row-cols-1 row-cols-md-2 g-2">
                        <?php foreach ($stops as $stop): ?>
                            <div class="form-check col">
                                <input class="form-check-input" type="checkbox" name="stops[]" 
                                    value="<?= htmlspecialchars($stop['id']) ?>"
                                    id="stop-<?= htmlspecialchars($stop['id']) ?>"
                                    data-lat="<?= htmlspecialchars($stop['latitude']) ?>"
                                    data-lng="<?= htmlspecialchars($stop['longitude']) ?>"
                                    data-name="<?= $stop['name'] ?>">
                                <label class="form-check-label" for="stop-<?= htmlspecialchars($stop['id']) ?>">
                                    <?= htmlspecialchars($stop['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-8">
            <div id="map">

            </div>
        </div>
    </div>

    <div class="container bg-dark bg-opacity-50 rounded p-2">
        <div id="route" class="container bg-dark bg-opacity-75 rounded p-4 text-light">
            <h1 >Votre itinéraire</h1>

            <div id="route-summary" class="mb-3">
                <div class="container">
                    <div id="stop-container" class="container row row-cols-6">
                        <!-- stepper invisible
                        <div class="d-flex flex-column align-items-center">
                            <div class="step-label mb-2">Départ</div>
                            <div class="step completed"><i class="bi bi-flag"></i></div>
                            <div class="step-label mb-2"></div>
                        </div> -->
                        <!-- <div class="d-flex flex-column align-items-center">
                            <div class="step active">2</div>
                            <div class="step-label">Étape 2</div>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="step"><i class="bi bi-geo-alt"></i></div>
                            <div class="step-label">Arrivée</div>
                        </div> -->
                    </div>
                </div>
            </div>
    
            <div id="route-details" class="mb-3">
                <p><strong>Distance totale :</strong> <span id="total-distance">0 km</span></p>
                <p><strong>Temps estimé :</strong> <span id="estimated-time">0h00</span></p>
                <input id="estimated-time-input" type="hidden" value="0">
            </div>
    
            <div id="params" class="mb-3">
                <form id="build-form">
                    <div class="row pb-3">
                        <!-- <div class="col mb-3">
                            <label for="travel-time" class="form-label fw-bold">Temps de voyage souhaité (jours):</label>
                            <input id="travel-time" type="number" class="form-control pt-2" placeholder="Temps de voyage souhaité en jours... Ex: 5">
                        </div> -->
                        <div class="col-3 mb-3">
                            <label for="person-count" class="form-label fw-bold">Nombre de participants</label>
                            <input id="person-count" type="number" class="form-control pt-2" placeholder="Nombre de participants" required>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <h2 class="pb-2">Dates du voyage</h2>
                        <div class="col-3">
                            <input type="date" id="travel-start" class="form-control" required>
                            <div id="travel-start-label" class="form-text text-white">Date de début</div>
                        </div>
        
                        <div class="col-3">
                            <input type="date" id="travel-end" class="form-control" required>
                            <div id="travel-end-label" class="form-text text-white">Date de fin</div>
                        </div>
                    </div>
        
                    <div id="services-region" class="row pb-2">
                        <h2>Services optionnels</h2>
                    </div>
                    
                    <div class="row pb-3">
                        <div class="d-flex gap-2 mt-2">
                            <button id="finalize-route" type="submit" class="btn btn-success">Valider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/templates/offcanvas.php'; ?>
<?php include_once 'includes/templates/chat.php'; ?>

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

<script type="module" src="/src/js/build/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<?php include_once "includes/templates/footer.php"; ?>
