<?php
require_once 'config.php';

$stmtPacks = $pdo->query("SELECT id, name, description, price FROM pack_templates WHERE CURDATE() BETWEEN available_from AND available_to LIMIT 3");
$packs = $stmtPacks->fetchAll(PDO::FETCH_ASSOC);

$stmtStops = $pdo->query("SELECT id, name, description FROM stops ORDER BY name ASC");
$stops = $stmtStops->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vacances Kayak sur la Loire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #007bff;
    }
    .navbar .navbar-brand, .navbar .nav-link {
      color: white !important;
    }
  </style>
</head>
<body>

<!-- 🔷 Barre de navigation -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">🛶 Kayak Loire</a>
    <div class="ms-auto">
      <a href="admin/login.php" class="btn btn-outline-light">Se connecter (Admin)</a>
    </div>
  </div>
</nav>

<!-- 💡 Contenu principal -->
<div class="container py-5">
  <h1 class="text-center mb-4">Explorez la Loire en Kayak</h1>
  
  <div class="text-center mb-5">
    <a href="composer-itineraire.php" class="btn btn-primary btn-lg m-2">🎯 Composer mon itinéraire</a>
    <a href="packs.php" class="btn btn-success btn-lg m-2">📦 Choisir un pack</a>
  </div>

  <section class="mb-5">
    <h2 class="h4"> Packs disponibles</h2>
    <div class="row">
      <?php foreach ($packs as $pack): ?>
        <div class="col-md-4">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($pack['name']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($pack['description'])) ?></p>
              <p><strong>À partir de <?= number_format($pack['price'], 2) ?> €</strong></p>
              <a href="pack-details.php?id=<?= $pack['id'] ?>" class="btn btn-outline-primary">Voir le pack</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section>
    <h2 class="h4"> Points d'arrêt le long de la Loire</h2>
    <div class="row">
      <?php foreach ($stops as $stop): ?>
        <div class="col-md-4">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($stop['name']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($stop['description'])) ?></p>
              <a href="stop-details.php?id=<?= $stop['id'] ?>" class="btn btn-outline-secondary">Détails</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

</body>
</html>
