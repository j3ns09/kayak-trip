<?php

session_start();

include_once 'includes/config/config.php';
include_once 'includes/functions.php';
include_once 'includes/templates/header.html';

$items = null;
if (existsSession('cart_items')) {
    $items = $_SESSION['cart_items'];
}

if (existsSession('user_id')) {
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
        <?php if (!$isConnected): ?>
            <?php include_once 'login.php'; ?>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-7 bg-white bg-opacity-75 p-5 min-vh-100">
                    <h4 class="fw-bold mb-4">Informations</h4>
                    <div>
                        <?php foreach ($items['stops'] as $stop): ?>
                            <div class="mb-3">
                                <h5><?= $stop['name'] ?></h5>
                                <?php
                                $accommodations = getAccommodationsByStop($pdo, $stop['id']) ?? [];
                                if (!empty($accommodations)): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input no-accommodation"
                                            type="checkbox"
                                            name="no-acc-<?= $stop['id'] ?>"
                                            id="no-acc-<?= $stop['id'] ?>"
                                            value="1">
                                        <label class="form-check-label fw-semibold text-danger" for="no-acc-<?= $stop['id'] ?>">
                                            Je ne souhaite pas réserver d’hébergement pour cet arrêt
                                        </label>
                                    </div>
                                    <div class="accordion" id="accordion-<?= $stop['id'] ?>">
                                        <?php foreach ($accommodations as $acc): ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-<?= $stop['id'] ?>-<?= $acc['id'] ?>">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-<?= $stop['id'] ?>-<?= $acc['id'] ?>"
                                                            aria-expanded="false"
                                                            aria-controls="collapse-<?= $stop['id'] ?>-<?= $acc['id'] ?>">
                                                        <span class="fw-bold"><?= htmlspecialchars($acc['name']) ?></span>
                                                        <span class="ps-3">
                                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                                <?php if ($i < $acc['stars']): ?>
                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                <?php else: ?>
                                                                    <i class="bi bi-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                    </button>
                                                </h2>
                                                <div id="collapse-<?= $stop['id'] ?>-<?= $acc['id'] ?>"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="heading-<?= $stop['id'] ?>-<?= $acc['id'] ?>"
                                                    data-bs-parent="#accordion-<?= $stop['id'] ?>">
                                                    <div class="accordion-body">
                                                        <p class="text-muted"><?= htmlspecialchars($acc['description']) ?></p>
                                                        <?php if (!empty($acc['rooms'])): ?>
                                                            <ul class="list-group">
                                                                <?php foreach ($acc['rooms'] as $room): ?>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input acc-checkbox"
                                                                                type="checkbox"
                                                                                name="room-<?= $stop['id'] ?>[]"
                                                                                id="room-<?= $room['id'] ?>"
                                                                                value="<?= $room['id'] ?>"
                                                                                data-price="<?= $room['base_price'] ?>">
                                                                            <label class="form-check-label" for="room-<?= $room['id'] ?>">
                                                                                <?= $room['name'] ?>
                                                                                <small class="text-muted">(capacité : <?= $room['capacity'] ?>)</small>
                                                                            </label>
                                                                        </div>
                                                                        <span class="badge bg-primary rounded-pill"><?= $room['base_price'] ?> €</span>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php else: ?>
                                                            <p class="text-muted fst-italic">Aucune chambre disponible pour cet hébergement.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
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
                        $qty = $items['person_count'] ?? 1;
                        displayItem($opt, $qty);
                    endforeach;
                    echo '</div>';
                    ?>
                    <hr>
                    <label for="discount-code" class="form-label">Code de promotion</label>
                    <div id="discount-group" class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text" id="valid-icon"><i class="bi bi-exclamation-circle-fill text-warning" data-bs-toggle="tooltip" data-bs-title="Pas de code promo sélectionné"></i></span>
                            <input type="text" id="discount-code" class="form-control col-3" placeholder="Entrez un code de promotion..." aria-describedby="cp-icon form-text1">
                            <input type="hidden" id="discount-value">
                            <button class="input-group-text" id="cp-icon"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2"><span>Frais de service</span><span>Inclus</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total</span><span id="total" data-basePrice="0">0 €</span></div>

                    <span id="duration" style="display:none;"><?= $items['desired_time']['duration'] ?? 0 ?></span>
                    <span id="start-date" style="display:none;"><?= frenchDate($items['desired_time']['dates'][0] ?? '') ?></span>
                    <span id="end-date" style="display:none;"><?= frenchDate($items['desired_time']['dates'][1] ?? '') ?></span>
                    <span id="person-count" style="display:none;"><?= $items['person_count'] ?? 1 ?></span>

                    <button id="checkout-btn" class="btn btn-success w-100 mt-4">Valider et passer au paiement</button>
                <?php endif; ?>
            </div>
            </div>
    </div>
</div>

<?php include_once 'includes/templates/offcanvas.php' ?>
<?php include_once 'includes/templates/chat.php' ?>

<script src="/src/js/cart/main.js" type="module"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php include_once 'includes/templates/footer.html'; ?>