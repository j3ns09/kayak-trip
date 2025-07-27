<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Vacances Kayak sur la Loire</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    <nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bolder montserrat ms-3" href="#"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
            <div class="ms-auto mb-3 me-2">
                <a href="admin/login.php" class="btn btn-warning text-white fw-bold montserrat fs-5">Se connecter</a>
            </div>
            <button class="navbar-toggler mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="w-100 position-relative">
        <img src="photo_kayak.png" alt="background image" class="img-fluid w-100 d-block">
        <div class="container position-absolute top-50 start-0 translate-middle-y text-white ps-5">
            <div class="ps-3">
                <h1 class="display-1 fw-semibold">Explorez la Loire en<br>Kayak</h1>
                <p class="fst-italic fs-5">Composez votre itinéraire ou optez pour l’un de nos packs tout inclus</p>
                
                <div class="mt-4 d-flex flex-column gap-3">
                    <a href="#" class="btn btn-light bg-opacity-75 text-white fw-semibold px-4 py-2 rounded-5">Composer mon itinéraire</a>
                    <a href="#" class="btn btn-light bg-opacity-50 text-white fw-semibold px-4 py-2" style="backdrop-filter: blur(5px); border-radius: 25px;">Voir les packs disponibles</a>
                </div>

                <hr class="border border-light border-2 opacity-100" style="width: 300px;">
            </div>
        </div>
    </div>




    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
        </div>
        <div class="offcanvas-body">
            <a href="admin/login.php" class="btn btn-warning text-white fw-bold montserrat fs-5">Se connecter</a>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>
</html>