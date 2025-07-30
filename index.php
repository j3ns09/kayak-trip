<?php 

include_once 'includes/store.php';

include_once 'includes/config/config.php';
include_once 'includes/functions.php';

include_once 'includes/templates/header.php';

$userId = $_SESSION['user_id'] !== false ? $_SESSION['user_id'] : null;
$userInfo = getDisplayableUserInfo($pdo, $userId);

$isConnected = !is_null($userId);
?>

<link rel="stylesheet" href="/src/css/home.css">

<nav class="navbar navbar-dark bg-dark fixed-top" style="height: 6rem;">
    <div class="container-fluid">
        <a class="navbar-brand ms-3 fs-2 title" href="#"><i class="bi bi-dot"></i> KAYAK TRIP<hr class="mt-1"/></a>
        <div class="ms-auto mb-3 me-2">
            <?php if ($isConnected): ?>
                <p class="text-white px-3 py-2 rounded fs-5 my-0 shadow-sm">Bonjour, <span class="fw-bold"><?= strtoupper($userInfo['last_name']) . " " . $userInfo['first_name'] ?></span></p>
                
            <?php else: ?>
                <a href="login.php" class="btn btn-warning text-white fs-5">
                    <span class="fw-bolder">
                        Se connecter
                    </span>
                </a>
            <?php endif; ?>
        </div>
        <button class="navbar-toggler mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div id="main" class="container row align-items-center">
    <div class="container text-white ps-5">
        <div class="container ps-3 row gx-1 justify-content-around align-items-center">
            <div class="col-6 px-5">
                <h1 class="display-1 fw-semibold">Explorez la Loire en Kayak</h1>
                <p class="fst-italic fs-5">Composez votre itinéraire ou optez pour l'un de nos packs tout inclus</p>
            </div>
            
            <div class="col-6 row gx-5 ps-5">
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




<div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between py-4">
        <?php if ($isConnected): ?>
            <div class="nav flex-column gap-3">
                <a href="#" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-house-door-fill me-2"></i> Accueil
                </a>
                <a href="profile.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-person-fill me-2"></i> Profil
                </a>

                <?php if (isAdmin($pdo, $userId)): ?>
                    <a href="admin/dashboard.php" class="btn btn-outline-light fw-bold">
                        <i class="bi bi-person-fill-gear"></i> Espace Administrateur
                    </a>
                <?php endif; ?>
                
                <a href="packs.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-backpack2-fill"></i> Packs
                </a>
                <a href="build.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-signpost-2-fill"></i> Composer son itinéraire
                </a>
            </div>
            
            <div class="border-top pt-3 mt-4">
                <a href="processes/user/access/logout_process.php" class="btn btn-danger w-100 fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i> Se déconnecter
                </a>
            </div>

            <?php else: ?>
                <a href="admin/login.php" class="btn btn-warning text-dark fw-bold fs-5">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Connexion
                </a>
            <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['event']) && $_SESSION['event'] === 'logout'): ?> 
<div class="toast-container position-fixed top-0 end-0 p-3 my-4 ">
  <div id="logoutToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <!-- <img src="..." class="rounded me-2" alt="..."> -->
      <strong class="me-auto">Déconnexion</strong>
      <small>Maintenant</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Déconnexion réussie.
    </div>
  </div>
</div>
<?php unset($_SESSION['event']); endif; ?>

<?php if (isset($_SESSION['event']) && $_SESSION['event'] === 'login'): ?> 
<div class="toast-container position-fixed top-0 end-0 p-3 my-4 ">
  <div id="logoutToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <!-- <img src="..." class="rounded me-2" alt="..."> -->
      <strong class="me-auto">Connexion</strong>
      <small>Maintenant</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Vous êtes maintenant connecté.
    </div>
  </div>
</div>
<?php unset($_SESSION['event']); endif; ?>

<script type="module" src="/src/js/index/main.js"></script>

<?php include_once "includes/templates/footer.php"; ?>