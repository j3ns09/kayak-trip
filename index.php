<?php 

include_once 'includes/store.php';
include_once 'includes/templates/header.php';

?>

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bolder ms-3" href="#"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
        <div class="ms-auto mb-3 me-2">
            <a href="login.php" class="btn btn-warning text-white fw-bold fs-5">Se connecter</a>
        </div>
        <button class="navbar-toggler mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div id="main" class="container row align-items-center">
    <div class="container text-white ps-5">
        <div class="container ps-3 row gx-5 justify-content-around align-items-center">
            <div class="col-6 px-5">
                <h1 class="display-1 fw-semibold">Explorez la Loire en<br>Kayak</h1>
                <p class="fst-italic fs-5">Composez votre itinéraire ou optez pour l'un de nos packs tout inclus</p>
            </div>
            
            <div class="col-6 row gx-5">
                <div class="col">
                    <a href="#" class="btn text-white col fw-semibold rounded-5 home-buttons">Composer mon itinéraire</a>                    
                </div>
                <div class="col">
                    <a href="#" class="btn text-white col fw-semibold rounded-5 home-buttons">Voir les packs disponibles</a>
                </div>
            </div>

            <!-- <hr class="border border-light border-2 opacity-100" style="width: 300px;"> -->
        </div>
    </div>
</div>


<!-- 

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <a href="admin/login.php" class="btn btn-warning text-white fw-bold montserrat fs-5">Se connecter</a>
    </div>
</div> -->

<?php include_once "includes/templates/footer.php"; ?>