<?php

session_start();

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

$cart = null;
$isCartEmpty = true;

if (isset($_SESSION['cart_items'])) {
    $cart = $_SESSION['cart_items'];
    $isCartEmpty = false;
}


?>

<link rel="stylesheet" href="/src/css/home.css">

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

<div class="container" style="padding-top: 8rem; color: white;">
    <h1 class="mb-4">Votre panier</h1>

    <?php if ($isCartEmpty): ?>
        <div class="alert alert-warning" role="alert">
            Votre panier est vide pour le moment.
        </div>
        <a href="build.php" class="btn btn-primary">Composer un itinéraire</a>
    <?php else: ?>
        <div class="card bg-dark text-white mb-4" style="max-width: 600px;">
            <div class="card-header fs-3">
                Commande en cours (non payée)
            </div>
            <div class="card-body">
                <p><strong>Nombre de jours désirés :</strong> <?= htmlspecialchars($cart['desired_time']) ?></p>
                <p><strong>Nombre de participants :</strong> <?= htmlspecialchars($cart['person_count']) ?></p>

                <p><strong>Transport des bagages :</strong> <?= $cart['bagage'] ? "Oui" : "Non" ?></p>
                <p><strong>Repas :</strong> <?= $cart['food'] ? "Oui" : "Non" ?></p>
                <p><strong>Location de matériel :</strong> <?= $cart['location'] ? "Oui" : "Non" ?></p>
            </div>
        </div>

        <a href="checkout.php" class="btn btn-success me-3">Procéder au paiement</a>
        <a href="build.php" class="btn btn-secondary">Modifier mon itinéraire</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php

include_once 'includes/templates/footer.php';

if (!$isCartEmpty) unset($_SESSION['cart_items']);

?>
