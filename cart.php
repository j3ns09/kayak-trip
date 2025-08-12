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

<?php include_once 'includes/templates/navbar.php'; ?>

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

            <div class="card-footer">
                <a href="checkout.php" class="btn btn-success me-3">Procéder au paiement</a>
                <a href="build.php" class="btn btn-secondary">Modifier mon itinéraire</a>
            </div>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php

include_once 'includes/templates/offcanvas.php';
include_once 'includes/templates/footer.php';

// if (!$isCartEmpty) unset($_SESSION['cart_items']);

?>
