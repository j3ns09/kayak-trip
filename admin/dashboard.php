<?php
// session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
//     header("Location: ../login.php");
//     exit();
// }
// ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Admin - Kayak Loire</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container py-5">
        <h1 class="mb-4">Tableau de bord - Administration</h1>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">🗺️ Points d'arrêt</h5>
                        <p class="card-text">Ajouter, modifier ou supprimer les étapes du parcours.</p>
                        <a href="stops.php" class="btn btn-primary">Gérer les étapes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">🏨 Hébergements</h5>
                        <p class="card-text">Gérer les hébergements, leurs capacités et disponibilités.</p>
                        <a href="accommodations.php" class="btn btn-primary">Gérer les hébergements</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">🧺 Services</h5>
                        <p class="card-text">Ajouter ou désactiver les services complémentaires.</p>
                        <a href="services.php" class="btn btn-primary">Gérer les services</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">💰 Promotions</h5>
                        <p class="card-text">Configurer les réductions et codes promos.</p>
                        <a href="promotions.php" class="btn btn-primary">Gérer les promotions</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">📦 Packs</h5>
                        <p class="card-text">Créer des packs (étapes + hébergements).</p>
                        <a href="packs.php" class="btn btn-primary">Gérer les packs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">📉 Taux d’occupation</h5>
                        <p class="card-text">Afficher un graphique d’occupation des hébergements.</p>
                        <a href="stats.php" class="btn btn-primary">Voir les statistiques</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">💬 Messagerie</h5>
                        <p class="card-text">Lire et répondre aux messages des clients.</p>
                        <a href="chat.php" class="btn btn-primary">Messagerie commerciale</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">📣 Newsletter</h5>
                        <p class="card-text">Gérer les abonnés et envoyer des campagnes.</p>
                        <a href="newsletter.php" class="btn btn-primary">Gérer les newsletters</a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">
        <a href="../logout.php" class="btn btn-outline-danger">Déconnexion</a>
    </div>
</body>

</html>