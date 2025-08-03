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

<link rel="stylesheet" href="/src/css/home.css">
<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand ms-3 fs-2 title" href="#"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
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
    </div>
</nav>

<div class="container" style="padding-top: 7rem; color: white;">
    <h1 class="mb-4">Composez votre itinéraire en kayak</h1>

    <div class="row">
        <div class="col-md-4 steps-list bg-dark p-3 rounded">
            <h4>Sélectionnez vos étapes</h4>
            <form id="route-form">
                <?php foreach ($stops as $stop): ?>
                    <label>
                        <input type="checkbox" name="stops" value="<?= htmlspecialchars($stop['id']) ?>"
                               data-lat="<?= htmlspecialchars($stop['latitude']) ?>"
                               data-lng="<?= htmlspecialchars($stop['longitude']) ?>">
                        <?= htmlspecialchars($stop['name']) ?>
                    </label>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-success btn-select w-100 mt-3">Valider mon itinéraire</button>
            </form>
        </div>

        <div class="col-md-8">
            <div id="map"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<?php include_once "includes/templates/footer.php"; ?>
