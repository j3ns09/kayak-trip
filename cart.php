<?php

session_start();

include_once 'includes/config/config.php';
include_once 'includes/functions.php';
include_once 'includes/templates/header.php';

$items = null;
if (isset($_SESSION['cart_items'])) {
    $items = $_SESSION['cart_items'];
}

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);

include_once 'includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/home.css">

<div class="container-fluid min-vh-100" style="margin-top:6rem;">
    <div class="row">
        <?php
        if (!$isConnected) :
            include_once 'login.php';
        ?>


        <?php else: ?>
        <div class="col-lg-7 bg-white bg-opacity-75 p-5">
            <h4 class="fw-bold mb-4">Informations</h4>
            <form method="POST" action="checkout.php">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <h5 class="fw-bold mt-4">Adresse de livraison</h5>
                <div class="row g-2 mb-3">
                    <div class="col"><input type="text" class="form-control" placeholder="Prénom" name="first_name" required></div>
                    <div class="col"><input type="text" class="form-control" placeholder="Nom" name="last_name" required></div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Pays/région" name="country" value="France" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Numéro et nom de rue" name="address" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col"><input type="text" class="form-control" placeholder="Code postal" name="zip" required></div>
                    <div class="col"><input type="text" class="form-control" placeholder="Ville" name="city" required></div>
                </div>
                <div class="mb-3">
                    <input type="tel" class="form-control" placeholder="Téléphone" name="phone" required>
                </div>
                <button type="submit" class="btn btn-primary">Modes de livraison →</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="col-lg-5 p-5 bg-light">
            <h4 class="fw-bold mb-4">Panier</h4>
            <form method="post" action="cart.php">
                <input type="hidden" name="action" value="update">
                <?php if (empty($items)): ?>
                    <p>Votre panier est vide. <a href="packs.php">Retour aux offres</a></p>
                <?php else: $time = $items['desired_time']['duration']; ?>
                    <div class="mb-3">
                        <strong>Durée du voyage souhaitée :</strong> <?= $time . ($time < 2 ? ' jour' : ' jours')?><br>
                        <strong>Date de départ :</strong> <?= frenchDate($items['desired_time']['dates'][0]) ?><br>
                        <strong>Date de fin :</strong> <?= frenchDate($items['desired_time']['dates'][1]) ?><br>
                        <strong>Nombre de personnes :</strong> <?= (int)$items['person_count'] ?>
                    </div>
                    <hr>

                    <?php
                    $total = 0;
                    $travel_options = [];
                    
                    foreach ($items['options'] as $opt) {
                        $option = getService($pdo, $opt['id']);
                        array_push($travel_options, $option);
                    }
                    foreach ($travel_options as $opt) :
                        $title = htmlspecialchars($opt['name'] ?? 'Produit inconnu');
                        $price = (int)($opt['price'] ?? 0);
                        $qty = (int)($items['person_count']?? 1);
                        $subtotal = $price * $qty;
                        $total += $subtotal;
                    ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-semibold"><?= $title ?></div>
                                <div class="text-muted small">Prix unitaire: <?= $price ?> €</div>
                                <div class="text-muted small">Quantité: <?= $qty ?></div>
                            </div>
                            <div class="text-end">
                                <strong><?= $subtotal ?> €</strong>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span>Frais de service</span><span>Inclus</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span><?= $total ?> €</span></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/templates/footer.php'; ?>
