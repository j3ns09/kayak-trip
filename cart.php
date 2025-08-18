<?php

session_start();

include_once 'includes/config/config.php';
include_once 'includes/templates/header.php';
// include_once 'includes/templates/navbar.php';

$items = null;
if (isset($_SESSION['cart_items'])) {
    $items = $_SESSION['cart_items'];
}

function money($cents) {
    return number_format($cents/100, 2, ',', ' ') . ' €';
}

$userId = null;
if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
}

$isConnected = !is_null($userId);
?>

<link rel="stylesheet" href="/src/css/home.css">

<div class="container-fluid min-vh-100" style="margin-top:6rem;">
    <div class="row">
        <?php
        if ($isConnected) :
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
                <?php if (empty($productRows)): ?>
                    <p>Votre panier est vide. <a href="packs.php">Retour aux offres</a></p>
                <?php else: ?>
                    <?php foreach ($productRows as $id => $p): $qty = $cart[$id]; ?>
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?= htmlspecialchars($p['image'] ?: '/assets/no-image.png') ?>" alt="<?= htmlspecialchars($p['title']) ?>" style="width:70px;height:70px;object-fit:cover;border-radius:8px;">
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-semibold"><?= htmlspecialchars($p['title']) ?></div>
                                <div class="text-muted small">Prix unitaire: <?= money((int)$p['price_cents']) ?></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control form-control-sm me-2" style="width:70px" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="0">
                                <button type="submit" formaction="cart.php" name="action" value="remove" class="btn btn-sm btn-outline-danger" onclick="this.form.id.value=<?= $id ?>">×</button>
                                <input type="hidden" name="id" value="<?= $id ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span>Sous-total</span><span><?= money($totalCents) ?></span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Frais de service</span><span>Inclus</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span><?= money($totalCents) ?></span></div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-secondary">Mettre à jour</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/templates/footer.php'; ?>
