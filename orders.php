<?php
session_start();

include_once 'includes/config/config.php';
include_once 'includes/functions.php';

if (existsSession('user_id')) {
    $userId = $_SESSION["user_id"];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    redirect('login');
}

$isConnected = !is_null($userId);

$orders = getBookingsDetails($pdo, $userId);

include_once 'includes/templates/header.html';
include_once 'includes/templates/navbar.php';

?>

<link rel="stylesheet" href="/src/css/home.css">

<div class="container min-vh-100" style="margin-top:6rem; margin-bottom:50px">
    <h3 class="fw-bold mb-4 pt-3">Mes commandes</h3>

    <?php if (empty($orders)): ?>
        <p class="text-muted fst-italic">Vous n’avez encore passé aucune commande.</p>
    <?php else: ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): 
                $start = new DateTime($order['start_date']);
                $end = new DateTime($order['end_date']);
                $interval = $start->diff($end);
                $diff = $interval->days;

            ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading<?= $index ?>">
                        <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?= $index ?>" 
                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                aria-controls="collapse<?= $index ?>">
                            Commande #<?= $order['booking_id'] ?> – <?= date("d/m/Y", strtotime($order['created_at'])) ?>  
                            (<?= $order['total_price'] ?> €)
                            <?php if ($order['is_paid']): ?>
                                <span class="badge bg-success ms-3">Payée</span>
                            <?php else: ?>
                                <span class="badge bg-danger ms-3">Non payée</span>
                            <?php endif; ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $index ?>" 
                         class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                         aria-labelledby="heading<?= $index ?>" 
                         data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <p><strong>Durée :</strong> <?= $diff ?> jour(s)</p>
                            <p><strong>Dates :</strong> <?= $order['start_date'] ?> → <?= $order['end_date'] ?></p>
                            <p><strong>Personnes :</strong> <?= $order['person_count'] ?></p>

                            <?php if (!empty($order['discount_code'])): ?>
                                <p><strong>Code promo :</strong> <?= htmlspecialchars($order['discount_code']) ?></p>
                            <?php endif; ?>

                            <p><strong>Statut :</strong> 
                                <?php if ($order['is_paid']): ?>
                                    <span class="badge bg-success">Payée</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Non payée</span>
                                    <a href="pay.php?booking_id=<?= $order['booking_id'] ?>" 
                                    class="btn btn-sm btn-primary ms-2">
                                        <i class="bi bi-credit-card"></i> Payer maintenant
                                    </a>
                                <?php endif; ?>
                            </p>

                            <h6 class="mt-3">Hébergements</h6>
                            <?php
                            $accs = getAccommodationsFromBooking($pdo, $order['booking_id']);

                            if ($accs):
                                foreach ($accs as $acc): ?>
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1">
                                                <i class="bi bi-house-fill text-primary"></i>
                                                <?= htmlspecialchars($acc['name']) ?>
                                            </h5>
                                            <p class="text-muted small mb-2">
                                                Arrêt #<?= $acc['id'] ?>
                                            </p>

                                            <?php 
                                            $rooms = getRoomsFromBooking($pdo, $order['booking_id']);
                                            if ($rooms): ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($rooms as $room): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <i class="bi bi-door-closed"></i> 
                                                                <?= htmlspecialchars($room['room_name']) ?>
                                                            </span>
                                                            <span class="badge bg-success rounded-pill">
                                                                <?= number_format($room['price'], 2, ',', ' ') ?> €
                                                            </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="fst-italic text-muted mt-2">Aucune chambre sélectionnée</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach;
                            else:
                                echo "<p class='fst-italic text-muted'>Aucun hébergement choisi</p>";
                            endif;
                            ?>

                            <h6 class="mt-3">Services</h6>
                            <?php
                            $srvs = getServicesFromBooking($pdo, $order['booking_id']);

                            if ($srvs):
                                foreach ($srvs as $srv): ?>
                                    <div class="card mb-2 shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="bi bi-bag-check text-warning"></i>
                                                    <?= htmlspecialchars($srv['name']) ?>
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    Quantité : <?= $srv['quantity'] ?>
                                                </p>
                                            </div>
                                            <span class="badge bg-success rounded-pill">
                                                <?= number_format($srv['price'] * $srv['quantity'], 2, ',', ' ') ?> €
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach;
                            else:
                                echo "<p class='fst-italic text-muted'>Aucun service sélectionné</p>";
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php 

include_once 'includes/templates/offcanvas.php';
include_once 'includes/templates/chat.php';
include_once 'includes/templates/footer.php';

?>
