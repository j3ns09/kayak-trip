<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/store.php';
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

<div class="container-fluid min-vh-100" style="margin-top:6rem;">
    <div class="container glass-card shadow">
        <h1 class="mb-3"><?= htmlspecialchars($pack['name']) ?></h1>
        <p><strong>Durée :</strong> <?= (int)$pack['duration'] ?> jours</p>
        <p><strong>Prix :</strong> <?= number_format($pack['price'], 2, ',', ' ') ?> €</p>
        <p><?= nl2br(htmlspecialchars($pack['description'])) ?></p>
    
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
                                        <li><?= htmlspecialchars($acc['name']) ?></li>
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
    </div>
</div>

<?php
include_once $root . '/includes/templates/offcanvas.php';
include_once $root . '/includes/templates/footer.php';
?>
