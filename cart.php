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
$total = 0;

include_once 'includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/home.css">

<div class="container-fluid min-vh-100" style="margin-top:6rem;">
    <div>
        <?php
        if (!$isConnected) :
            include_once 'login.php';
        ?>


        <?php else: ?>
            <form class="row" method="POST" action="checkout.php">
                <div class="col-lg-7 bg-white bg-opacity-75 p-5">
                    <h4 class="fw-bold mb-4">Informations</h4>
                    <div>
                        <?php foreach ($items['stops'] as $stop): ?>
                            <div class="mb-3">
                                <h5><?= $stop['name'] ?></h5>

                                <?php
                                $accommodations = getAccommodationsByStop($pdo, $stop['id']) ?? [];
                                if (!empty($accommodations)): ?>
                                    <ul class="list-group mt-2">
                                        <?php foreach ($accommodations as $acc): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input acc-radio" 
                                                        type="radio" 
                                                        name="acc-<?= $stop['id'] ?>" 
                                                        id="radio-<?= $acc['id'] ?>"
                                                        data-price="<?= $acc['base_price_per_night']?>"
                                                        >
                                                    <div class="form-check-label" for="radio-<?= $acc['id'] ?>">
                                                        <span class="fw-semibold">
                                                            <?= htmlspecialchars($acc['name']) ?>
                                                            <span class="ps-2">
                                                                <?php for ($i = 0; $i < 5; $i++) {
                                                                    if ($i < $acc['stars']) {
                                                                        echo '<i class="bi bi-star-fill text-warning"></i>';
                                                                    } else {
                                                                        echo '<i class="bi bi-star"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </span><br>
                                                        <small class="text-muted"><?= htmlspecialchars($acc['description'] ?? '') ?></small>
                                                    </div>
                                                    <span class="badge bg-primary rounded-pill"><?= $acc['base_price_per_night'] ?> €</span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted fst-italic">Aucun hébergement disponible pour cet arrêt.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-lg-5 p-5 bg-light">
                <h4 class="fw-bold mb-4">Panier</h4>
                <input type="hidden" name="action" value="update">

                <?php if (empty($items)): ?>
                    <p>Votre panier est vide. <a href="packs.php">Retour aux offres</a></p>

                <?php else: $time = $items['desired_time']['duration']; ?>
                    <div class="mb-3">
                        <strong>Durée du voyage souhaitée :</strong> <?= $time . ($time < 2 ? ' jour' : ' jours') ?><br>
                        <strong>Date de départ :</strong> <?= frenchDate($items['desired_time']['dates'][0]) ?><br>
                        <strong>Date de fin :</strong> <?= frenchDate($items['desired_time']['dates'][1]) ?><br>
                        <strong>Nombre de personnes :</strong> <?= $items['person_count'] ?>
                    </div>
                    <hr>

                    <?php
                    $travel_options = [];

                    foreach ($items['options'] as $opt) {
                        $option = getService($pdo, $opt['id']);
                        array_push($travel_options, $option);
                    }
                    echo '<div id="spendings">';
                    foreach ($travel_options as $opt) :
                        $title = htmlspecialchars($opt['name'] ?? 'Produit inconnu');
                        $price = $opt['price'] ?? 0;
                        $qty = $items['person_count'] ?? 1;
                        $total += $price * $qty;

                        displayItem($opt, $qty);
                    ?>
                    <?php endforeach;
                    echo '</div>';
                    ?>

                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span>Frais de service</span><span>Inclus</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span id="total"><?= $total ?> €</span></div>
                <?php endif; ?>
            </div>
            </form>
    </div>
</div>

<script src="/src/js/cart/main.js" type="module"></script>
<?php include_once 'includes/templates/footer.php'; ?>