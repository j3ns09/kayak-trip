<?php

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/store.php';

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

include_once $root . '/includes/templates/header.php';

if (isset($_SESSION['user_id'])) {
    if (!isAdmin($pdo, $_SESSION['user_id'])) {
        redirect('index');
    }
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
    <a href="#promos"><i class="bi bi-percent"></i> Promotions</a>
    <a href="#services"><i class="bi bi-tools"></i> Services</a>
    <a href="#tarifs"><i class="bi bi-calendar2-week"></i> Tarifs saisonniers</a>
    <a href="#messagerie"><i class="bi bi-envelope"></i> Messagerie</a>
    <a href="#newsletter"><i class="bi bi-newspaper"></i> Newsletter</a>
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
                <label for="description" class="form-label">Type</label>
                <select name="description" id="description" class="form-select">
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
                <tr>
                    <td>1</td>
                    <td>Ancenis</td>
                    <td>47.366259</td>
                    <td>-1.16807</td>
                    <td>Etape</td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div id="hebergements" class="section">
        <h2>Hébergements</h2>
        <form id="form_hebergement" class="row g-3">
            <div class="col-md-4">
                <label for="nom_hebergement" class="form-label">Nom de l’hébergement</label>
                <input type="text" class="form-control" id="nom_hebergement" name="nom_hebergement">
            </div>

            <div class="col-md-2">
                <label for="type_hebergement" class="form-label">Type</label>
                <select class="form-select" id="type_hebergement" name="type_hebergement">
                    <option disabled selected>Choisir un type</option>
                    <option value="camping">Camping</option>
                    <option value="gite">Gîte</option>
                    <option value="hotel">Hôtel</option>
                    <option value="chambre_hote">Chambre d’hôtes</option>
                    <option value="refuge">Refuge</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="arret" class="form-label">Point d'arrêt</label>
                <select name="arret" id="arret-list" class="form-select">
                    <option disabled selected>Choisir un point d'arrêt</option>
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </div>
        </form>
        <hr class="text-white">
        <p>Modifier les périodes de fermeture pour travaux ou maintenance.</p>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>Point d’arrêt</th>
                    <th>Hébergement</th>
                    <th>Disponibilités</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Orléans</td>
                    <td>Hôtel des Quais</td>
                    <td>Fermé : 1/08/2025 - 15/08/2025</td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="promos" class="section">
        <h2>Promotions</h2>
        <form class="row g-3">
            <div class="col-md-4">
                <label>Période</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Remise (%)</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-success">Appliquer</button>
            </div>
        </form>
        <div class="mt-3">
            <p><strong>Code promo première réservation :</strong> <code>KAYAK2025</code></p>
        </div>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Remise</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01/07/2025 - 15/07/2025</td>
                    <td>15%</td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="services" class="section">
        <h2>Services complémentaires</h2>
        <form class="row g-3">
            <div class="col-md-6">
                <label>Nom du service</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-success">Ajouter</button>
            </div>
        </form>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Transport de bagages</td>
                    <td>20</td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Location de tentes</td>
                    <td>30</td>
                    <td>
                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="tarifs" class="section">
        <h2>Tarification saisonnière</h2>
        <form class="row g-3">
            <div class="col-md-4">
                <label>Du</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Au</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Tarif (€)</label>
                <input type="number" class="form-control">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-success">Appliquer</button>
            </div>
        </form>
    </div>


    <div id="messagerie" class="section">
        <h2>Messagerie commerciale</h2>
        <ul class="list-group list-group-flush bg-transparent text-white mt-4">
            <li class="list-group-item bg-dark text-white d-flex justify-content-between">
                jean@example.com
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </li>
            <li class="list-group-item bg-dark text-white d-flex justify-content-between">
                anna@example.com
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
            </li>
        </ul>
    </div>

    <div id="newsletter" class="section">
        <h2>Newsletter</h2>
        <form class="d-flex gap-3">
            <input type="email" class="form-control" placeholder="Adresse e-mail">
            <button class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>

<script type="module" src="/src/js/admin/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>