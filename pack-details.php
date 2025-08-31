<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';
include_once $root . '/includes/templates/header.html';

if (existsSession('user_id')) {
    $userId = $_SESSION['user_id'];
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);

$packId = filter_input(INPUT_GET, 'pack_id', FILTER_VALIDATE_INT);
if (!$packId) {
    header("Location: /packs.php");
    exit();
}

$pack = getPack($pdo, $packId);
if (!$pack) {
    echo "<div class='container pt-5 mt-5'><h3>Pack non trouvé</h3><a href='/packs.php' class='btn btn-primary'>Retour</a></div>";
    include_once $root . '/includes/templates/footer.php';
    exit();
}

include_once $root . '/includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/home.css">
<link rel="stylesheet" href="/src/css/pack_details.css">

<div class="container-fluid min-vh-100">
    <div class="container glass-card shadow">
        <h1 class="mb-3"><?= htmlspecialchars($pack['name']) ?></h1>
        <p><strong>Durée :</strong> <?= (int)$pack['duration'] ?> jours</p>
        <p><strong>Prix :</strong> <?= $pack['price'] ?> €</p>
        <p><strong>Pour :</strong> <?= $pack['person_count'] ?> personne(s)</p>
        <p><?= nl2br(htmlspecialchars($pack['description'])) ?></p>
    
        <h3 class="mt-4">Services inclus :</h3>
        <?php if (!empty($pack['services'])): ?>
            <ul>
                <?php foreach ($pack['services'] as $service): ?>
                    <li><?= htmlspecialchars($service['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun service défini pour ce pack.</p>
        <?php endif; ?>

        <h3 class="mt-4">Étapes du pack :</h3>
        <div class="stop-list">
            <?php if (count($pack['stops']) === 0): ?>
                <p>Aucune étape définie pour ce pack.</p>
            <?php else: ?>
                <?php foreach ($pack['stops'] as $stop): ?>
                    <div class="stop-item">
                        <strong><?= htmlspecialchars($stop['name']) ?></strong>
                        <?php if (!empty($stop['accommodations'])): ?>
                            <div class="accommodation-list">
                                Hébergements possibles :
                                <ul>
                                    <?php foreach ($stop['accommodations'] as $acc): ?>
                                        <span>
                                            <li><?= htmlspecialchars($acc['name']) ?></li>
                                            <?= displayAccStars($acc) ?>
                                        </span>
                                        
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="accommodation-list text-muted">Aucun hébergement défini pour cette étape.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    
        <a href="/packs.php" class="btn btn-outline-light mt-3">Retour aux packs</a>
        <?php if ($isConnected): ?>
            <form action="/add_to_cart.php" method="POST" class="mt-3">
                <input type="hidden" name="pack_id" value="<?= $packId ?>">
                <button type="submit" class="btn btn-success">Réserver ce pack</button>
            </form>
        <?php else: ?>
            <div class="mt-3">
                <a href="/login.php" class="btn btn-warning">Connectez-vous pour réserver</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php
include_once $root . '/includes/templates/offcanvas.php';
include_once $root . '/includes/templates/footer.php';
?>
