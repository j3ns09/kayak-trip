<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

include_once $root . '/includes/templates/header.html';

if (existsSession('user_id')) {
    if (!isAdmin($pdo, $_SESSION['user_id'])) {
        redirectAlert('error', 'Accès non autorisé à la page ' . $_SERVER['HTTP_HOST'], 'index');
    }
} else {
    redirectAlert('error', 'Accès non autorisé à la page ' . $_SERVER['HTTP_HOST'], 'index');
}

if (existsSession('form_data')) {
    var_dump(getSession('form_data'));
}

?>

<link rel="stylesheet" href="/src/css/admin.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<div class="sidebar">
    <div class="text-center mb-4">
        <a href="/index.php" class="btn btn-outline-light btn-sm rounded-pill mx-3">
            <i class="bi bi-arrow-left-circle"></i> Retour utilisateur
        </a>
    </div>
    <h4 class="text-center fw-bold mb-4">Admin Kayak Trip</h4>
    <a href="#utilisateurs" class="active"><i class="bi bi-person"></i> Utilisateurs</a>
    <a href="#etapes"><i class="bi bi-geo-alt"></i> Points d’arrêt</a>
    <a href="#hebergements"><i class="bi bi-house-door"></i> Hébergements</a>
    <a href="#packs"><i class="bi bi-backpack2"></i> Packs</a>
    <a href="#promos"><i class="bi bi-percent"></i> Promotions</a>
    <a href="#services"><i class="bi bi-tools"></i> Services</a>
    <a href="#messagerie"><i class="bi bi-envelope"></i> Messagerie</a>
    <a href="#newsletter"><i class="bi bi-newspaper"></i> Newsletter</a>
    <a href="#orders"><i class="bi bi-receipt"></i> Commandes</a>
</div>

<div class="main-content">
    <h1 class="text-center mb-5">Panneau d’administration</h1>

    <div id="utilisateurs" class="section">
        <h2>Utilisateurs</h2>

        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersShowing">
            </tbody>
        </table>
    </div>

    <div id="etapes" class="section">
        <h2>Points d’arrêt</h2>
        <form class="row g-3" method="POST" action="/processes/stops/add_stop_process.php">
            <div class="col-md-4">
                <label for="nom_etape" class="form-label">Nom de l’étape</label>
                <input type="text" class="form-control" id="nom_etape" name="nom_etape" placeholder="Ex : Pause déjeuner">
            </div>

            <div class="col-md-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Ex : 44.835">
            </div>

            <div class="col-md-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Ex : 4.209167">
            </div>

            <div class="col-md-2">
                <label for="step-description" class="form-label">Type</label>
                <select name="description" id="step-description" class="form-select">
                    <option value="" disabled selected>Choisir un type</option>
                    <option value="embarquement">Point d'embarquement</option>
                    <option value="etape">Aire de repos / étape</option>
                    <option value="abri">Abri</option>
                    <option value="ravitaillement">Point de ravitaillement</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter l’étape</button>
            </div>
        </form>
        <div id="map">

        </div>

        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="stopsShowing">
            </tbody>
        </table>
        <nav>
            <ul id="step-pagination" class="pagination justify-content-center"></ul>
        </nav>
    </div>

    <div id="hebergements" class="section">
        <h2>Hébergements</h2>
        <form id="form_hebergement" class="row g-3">
            <div class="col-md-4">
                <label for="nom_hebergement" class="form-label">Nom de l’hébergement</label>
                <input type="text" class="form-control" id="nom_hebergement" name="nom_hebergement" required>
            </div>

            <div class="col-md-2">
                <label for="stars" class="form-label">Nombre d'étoiles</label>
                <input type="number" class="form-control" id="stars" name="stars" min="1" max="5" required>
            </div>

            <div class="col-md-3">
                <label for="arret" class="form-label">Point d'arrêt</label>
                <select name="arret" id="arret-list" class="form-select" required>
                <option disabled selected>Choisir un point d'arrêt</option>
                </select>
            </div>

            <div class="col-md-12">
                <label for="acc-description" class="form-label">Description</label>
                <textarea id="acc-description" class="form-control" name="description" rows="2" required></textarea>
            </div>

            <div class="col-md-12">
                <label class="form-label">Chambres</label>
                <div id="chambres-container"></div>
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="ajouterChambre()">+ Ajouter une chambre</button>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">Ajouter l'hébergement</button>
            </div>
        </form>
        <hr class="text-white">
        <p>Modifier les périodes de fermeture pour travaux ou maintenance.</p>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Nombre de chambres</th>
                    <th>Point d’arrêt</th>
                    <th>Description</th>
                    <th>Prix moyen</th>
                    <th>Disponibilités</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="accommodationsShowing">
            </tbody>
        </table>
        <ul id="accommodations-pagination" class="pagination justify-content-center"></ul>
    </div>

    <div id="packs" class="section">
        <h2>Packs</h2>
        <form class="row g-3" method="POST" action="/processes/packs/add_pack_process.php">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="pack-nom" class="form-label">Nom du pack</label>
                    <input type="text" name="name" id="pack-nom" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="pack-description" class="form-label">Description</label>
                    <input type="text" name="description" id="pack-description" class="form-control">
                </div>
                <div class="col-md-1">
                    <label for="pack-duree" class="form-label">Durée</label>
                    <input type="number" name="duration" id="pack-duree" class="form-control" min="1" max="10">
                </div>
                <div class="col-md-2">
                    <label for="pack-prix" class="form-label">Prix</label>
                    <input type="text" name="price" id="pack-prix" class="form-control" min="0" max="1000" placeholder="Prix en euros...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nombre de participants</label>
                    <input name="person_count" type="number" class="form-control" min="1" max="20" placeholder="De 1 à 20" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Services associés</label>
                    <select name="service_id[]" class="form-control" multiple>
                        <?php
                        $services = getAllServices($pdo);
                        foreach ($services as $service) : ?>
                            <option value="<?= $service['id'] ?>"><?= $service['name'] ?></option>
                        
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Maintenez Ctrl pour en sélectionner plusieurs</small>
                </div>
            </div>
            <div id="selects-container" class="row mb-3">
                <div class="row">
                    <label class="col-md-2 form-label">Arrêts <button id="add-stop" type="button" class="btn btn-sm btn-primary">Ajouter un arrêt</button></label>
                </div>
                <div class="col-sm-3 mt-2">
                    <select name="stop_id[]" class="form-control selects-stops">
                        <?php 
                        $stops = getAllStops($pdo);
                        foreach ($stops as $stop): ?>
                            <option value="<?= $stop['id'] ?>"><?= htmlspecialchars($stop['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-3 mt-2">
                    <select name="accommodation_id[]" class="form-control selects-accommodations">
                        <option value="">Choisissez un hébergement</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </div>
        </form>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="packsShowing">
            </tbody>
        </table>
    </div>

    <div id="promos" class="section">
        <h2>Promotions</h2>
        <form class="row g-3" method="POST" action="/processes/discounts/add_discount_process.php">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="promo-code" class="form-label">Code de promotion</label>
                    <input type="text" id="promo-code" class="form-control" name="code" placeholder="Ex: FIRSTKAYAK10..." required>
                </div>
                <div class="col-md-3">
                    <label for="promo-description" class="form-label">Description</label>
                    <input type="text" id="promo-description" name="description" class="form-control" placeholder="Description de la promotion..." required>
                </div>
                <div class="col-md-2">
                    <label for="reduction" class="form-label">Remise (%)</label>
                    <input id="reduction" name="reduction" type="number" class="form-control" placeholder="Ex: 15, 40" min="1" max="100" required>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="discount-use" name="discount-use" value="1">
                        <label for="discount-use" class="form-check-label">Utilisation unique</label>
                    </div>
                </div>
            </div>
            <div class="row col-md-6 mb-3">
                <div class="col">
                    <label for="dates-promos" class="form-label">Période</label>
                    <div id="dates-promos" class="row g-2">
                        <div class="col-6">
                            <input id="discount-start" name="discount-start" type="date" class="form-control">
                            <div class="form-text text-white">Date de début</div>
                        </div>
                        <div class="col-6">
                            <input id="discount-end" name="discount-end" type="date" class="form-control">
                            <div class="form-text text-white">Date de fin</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </div>
        </form>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Période</th>
                    <th>Description</th>
                    <th>Remise</th>
                    <th>Utilisation unique</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="discountsShowing">
            </tbody>
        </table>
    </div>

    <div id="services" class="section">
        <h2>Services complémentaires</h2>
        <form class="row g-3" method="POST" action="/processes/services/add_service_process.php">
            <div class="col-md-3">
                <label for="service-name" class="form-label">Nom</label>
                <input id="service-name" name="name" type="text" class="form-control" placeholder="Nom du service...">
            </div>
            <div class="col-md-4">
                <label for="service-description" class="form-label">Description</label>
                <input id="service-description" name="description" type="text" class="form-control" placeholder="Description du service...">
            </div>
            <div class="col-md-3">
                <label for="service-price" class="form-label">Prix</label>
                <input id="service-price" name="price" type="text" class="form-control" placeholder="Prix en euros (€)">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </div>
        </form>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="servicesShowing">
            </tbody>
        </table>
    </div>

    <div id="newsletter" class="section">
        <h2>Newsletter</h2>
        <form action="/processes/newsletter/send_news.php" method="POST" class="d-flex flex-column gap-3 mb-3">
            <div class="mb-2">
                <label for="objet" class="form-label">Objet</label>
                <input name="object" type="text" class="form-control" placeholder="Objet de la news" id="objet">
            </div>
            <div class="form-floating">
                <textarea name="content" class="form-control" placeholder="Message aux abonnés" id="mailTextArea"></textarea>
                <label for="mailTextArea">Message</label>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </form>

        <h3>Abonnés à la newsletter</h3>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <th>#</th>
                <th>Email</th>
                <th>Abonné depuis</th>
                <th>Actions</th>
            </thead>
            <tbody id="subscribersShowing">
            </tbody>
        </table>
        <ul id="news-pagination" class="pagination justify-content-center"></ul>
    </div>

    <div id="orders" class="section">
        <h2>Commandes</h2>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom/Prénom</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Créée le</th>
                    <th>Vendue</th>
                    <th>Payée</th>
                    <th>Code promo utilisé ?</th>
                </tr>
            </thead>
            <tbody id="ordersShowing">
            </tbody>
        </table>
        <ul id="orders-pagination" class="pagination justify-content-center"></ul>
    </div>
</div>

<?php

if (existsSession('error')) {
    displayToast(
        "errorToast",
        "danger",
        "Erreur",
        "Maintenant",
        getSession('error'),
    );
    unsetSession('error');
}

if (existsSession('success')) {
    displayToast(
        "successToast",
        "success",
        "Succès",
        "Maintenant",
        getSession('success'),
    );
    unsetSession('success');
}
?>

<script type="module" src="/src/js/admin/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>