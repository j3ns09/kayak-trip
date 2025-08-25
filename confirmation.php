<?php

session_start();

include_once 'includes/config/config.php';
include_once 'includes/functions.php';
include_once 'includes/templates/header.php';

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

?>

<?php
$order = getSession('order');

if (!$order) {
    echo "Aucune commande en cours.";
    exit;
}

var_dump($order);

$produits = [
    1 => "Kayak",
    2 => "Veste de sauvetage",
    3 => "Tente"
];

$hebergements = [
    18 => "Chalet bord de lac",
    20 => "Cabane dans les bois"
];


?>


<h1>Récapitulatif de votre commande</h1>

<h2>Articles</h2>
<table>
    <tr><th>Produit</th><th>Quantité</th></tr>
    <?php foreach ($order['quantities'] as $id => $qty): ?>
        <tr>
            <td><?= htmlspecialchars($produits[$id] ?? "Produit #$id") ?></td>
            <td><?= (int)$qty ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Hébergements</h2>
<table>
    <tr><th>Hébergement</th><th>Prix</th></tr>
    <?php foreach ($order['accommodations'] as $id => $price): ?>
        <tr>
            <td><?= htmlspecialchars($hebergements[$id] ?? "Hébergement #$id") ?></td>
            <td><?= number_format($price, 2, ',', ' ') ?> €</td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Informations complémentaires</h2>
<ul>
    <li><strong>Nombre de personnes :</strong> <?= (int)$order['personCount'] ?></li>
    <li><strong>Durée :</strong> <?= (int)$order['desiredTime']['duration'] ?> jour(s)</li>
    <li><strong>Dates :</strong> <?= implode(' au ', $order['desiredTime']['dates']) ?></li>
    <li><strong>Code promo :</strong> <?= $order['discountCode'] ?: 'Aucun' ?></li>
</ul>

<p class="total">Total : <?= number_format($order['total'], 2, ',', ' ') ?> €</p>

<form action="valider_commande.php" method="post">
    <input type="submit" value="Confirmer la commande">
</form>

</body>
</html>
