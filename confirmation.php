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

$order = $_SESSION['order'] ?? null;

if (!$order) {
    header("Location: cart.php");
    exit;
}

$accommodations = $order['accommodations'] ?? [];
$services       = $order['services'] ?? [];
$discountCode   = $order['discountCode'] ?? '';
$total          = $order['total'] ?? 0;
$desiredTime    = $order['desiredTime'] ?? [];
$personCount    = $order['personCount'] ?? 1;

include_once 'includes/templates/header.html';
include_once 'includes/templates/navbar.php';

?>

<link rel="stylesheet" href="/src/css/home.css">

<div class="container min-vh-100" style="margin-top:6rem;">
    <div class="row">
        <div class="col-lg-8 bg-white bg-opacity-75 p-5">
            <h3 class="fw-bold mb-4">Confirmation de commande</h3>

            <h5 class="mt-3"><i class="bi bi-calendar-event-fill"></i> Informations sur le voyage</h5>
            <ul>
                <li><strong>Durée :</strong> <?= $desiredTime['duration'] ?? 0 ?> jour(s)</li>
                <li><strong>Date de départ :</strong> <?= $desiredTime['dates'][0] ?? '' ?></li>
                <li><strong>Date de retour :</strong> <?= $desiredTime['dates'][1] ?? '' ?></li>
                <li><strong>Nombre de personnes :</strong> <?= $personCount ?></li>
            </ul>

            <h5 class="mt-4"><i class="bi bi-house-fill"></i> Hébergements sélectionnés</h5>
            <?php if (!empty($accommodations)): ?>
                <ul class="list-group">
                    <?php foreach ($accommodations as $stopId => $accId): 
                        $acc = getAccommodation($pdo, $accId);
                        ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <?= htmlspecialchars($acc['name'] ?? "Hébergement #$accId") ?>
                                <small class="text-muted">(Arrêt #<?= $stopId ?>)</small>
                            </span>
                            <span><?= $acc['base_price_per_night'] ?? '-' ?> € / nuit</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="fst-italic text-muted">Aucun hébergement choisi.</p>
            <?php endif; ?>

            <h5 class="mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-kayak-icon lucide-kayak"><path d="M18 17a1 1 0 0 0-1 1v1a2 2 0 1 0 2-2z"/><path d="M20.97 3.61a.45.45 0 0 0-.58-.58C10.2 6.6 6.6 10.2 3.03 20.39a.45.45 0 0 0 .58.58C13.8 17.4 17.4 13.8 20.97 3.61"/><path d="m6.707 6.707 10.586 10.586"/><path d="M7 5a2 2 0 1 0-2 2h1a1 1 0 0 0 1-1z"/></svg>
                 Services inclus</h5>
            <?php if (!empty($services)): ?>
                <ul class="list-group">
                    <?php foreach ($services as $srvId => $qty): 
                        $srv = getService($pdo, $srvId);
                        ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= htmlspecialchars($srv['name'] ?? "Service #$srvId") ?> (x<?= $qty ?>)</span>
                            <span><?= $srv['price'] * $qty ?> €</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="fst-italic text-muted">Aucun service sélectionné.</p>
            <?php endif; ?>

            <?php if (!empty($discountCode)): ?>
                <h5 class="mt-4">🎟️ Code promo appliqué</h5>
                <p><strong><?= htmlspecialchars($discountCode) ?></strong></p>
            <?php else: ?>
                <h5 class="mt-4">Aucun code de promotion appliqué</h5>
            <?php endif; ?>
        </div>

        <!-- Colonne droite récap -->
        <div class="col-lg-4 p-5 bg-light">
            <h4 class="fw-bold mb-4">Récapitulatif</h4>

            <div class="d-flex justify-content-between mb-2">
                <span>Sous-total</span>
                <span><?= number_format($total, 2, ',', ' ') ?> €</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Frais de service</span>
                <span>Inclus</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                <span>Total</span>
                <span><?= number_format($total, 2, ',', ' ') ?> €</span>
            </div>

            <form action="pay.php" method="POST">
                <button type="submit" class="btn btn-success w-100 mb-2"><i class="bi bi-credit-card"></i> Payer maintenant</button>
                <a href="cart.php" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-return-left"></i> Retour au panier</a>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/templates/footer.html'; ?>
