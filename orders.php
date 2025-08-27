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

// TODO: Fix affichage commandes

?>


<div class="container min-vh-100" style="margin-top:6rem;">
    <h3 class="fw-bold mb-4">Mes commandes</h3>

    <?php if (empty($orders)): ?>
        <p class="text-muted fst-italic">Vous n’avez encore passé aucune commande.</p>
    <?php else: ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading<?= $index ?>">
                        <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?= $index ?>" 
                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                aria-controls="collapse<?= $index ?>">
                            Commande #<?= $order['id'] ?> – <?= date("d/m/Y", strtotime($order['created_at'])) ?>  
                            (<?= $order['total'] ?> €)
                        </button>
                    </h2>
                    <div id="collapse<?= $index ?>" 
                         class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                         aria-labelledby="heading<?= $index ?>" 
                         data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            <p><strong>Durée :</strong> <?= $order['duration'] ?> jour(s)</p>
                            <p><strong>Dates :</strong> <?= $order['start_date'] ?> → <?= $order['end_date'] ?></p>
                            <p><strong>Personnes :</strong> <?= $order['person_count'] ?></p>

                            <?php if (!empty($order['discount_code'])): ?>
                                <p><strong>Code promo :</strong> <?= htmlspecialchars($order['discount_code']) ?></p>
                            <?php endif; ?>

                            <h6 class="mt-3">Hébergements</h6>
                            <?php
                            $stmtAcc = $pdo->prepare("SELECT * FROM order_accommodations WHERE order_id = ?");
                            $stmtAcc->execute([$order['id']]);
                            $accs = $stmtAcc->fetchAll(PDO::FETCH_ASSOC);

                            if ($accs):
                                echo '<ul class="list-group mb-2">';
                                foreach ($accs as $acc) {
                                    $accInfo = getAccommodation($pdo, $acc['accommodation_id']);
                                    echo "<li class='list-group-item'>" . htmlspecialchars($accInfo['name']) . "</li>";

                                    $stmtRooms = $pdo->prepare("SELECT * FROM order_rooms WHERE order_id = ?");
                                    $stmtRooms->execute([$order['id']]);
                                    $rooms = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);
                                    if ($rooms) {
                                        echo "<ul class='ps-4'>";
                                        foreach ($rooms as $room) {
                                            $roomInfo = getRoom($pdo, $room['room_id']);
                                            echo "<li>" . htmlspecialchars($roomInfo['name']) . " – " . $room['price'] . " €</li>";
                                        }
                                        echo "</ul>";
                                    }
                                }
                                echo '</ul>';
                            else:
                                echo "<p class='fst-italic text-muted'>Aucun hébergement choisi</p>";
                            endif;
                            ?>

                            <h6 class="mt-3">Services</h6>
                            <?php
                            $stmtSrv = $pdo->prepare("SELECT * FROM order_services WHERE order_id = ?");
                            $stmtSrv->execute([$order['id']]);
                            $srvs = $stmtSrv->fetchAll(PDO::FETCH_ASSOC);

                            if ($srvs):
                                echo '<ul class="list-group">';
                                foreach ($srvs as $srv) {
                                    $srvInfo = getService($pdo, $srv['service_id']);
                                    echo "<li class='list-group-item d-flex justify-content-between'>";
                                    echo "<span>" . htmlspecialchars($srvInfo['name']) . " (x{$srv['quantity']})</span>";
                                    echo "<span>" . ($srv['price'] * $srv['quantity']) . " €</span>";
                                    echo "</li>";
                                }
                                echo '</ul>';
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

<?php include_once 'includes/templates/footer.html'; ?>
