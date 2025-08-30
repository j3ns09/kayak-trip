<div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between py-4">
        <?php if ($isConnected): ?>
            <div class="nav flex-column gap-3">
                <a href="/index.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-house-door-fill me-2"></i> Accueil
                </a>
                <a href="/profile.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-person-fill me-2"></i> Profil
                </a>
                <a href="/packs.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-backpack2-fill"></i> Packs
                </a>
                <a href="/build.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-signpost-2-fill"></i> Composer son itinéraire
                </a>
                <a href="/cart.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-cart"></i> Panier
                </a>
                <a href="/orders.php" class="btn btn-outline-light fw-bold">
                    <i class="bi bi-receipt"></i> Mes commandes
                </a>
                <?php if (isAdmin($pdo, $userId)): ?>
                    <a href="/admin/dashboard.php" class="btn btn-outline-light fw-bold">
                        <i class="bi bi-person-fill-gear"></i> Espace Administrateur
                    </a>
                    
                    <a href="/admin/chat.php" class="btn btn-outline-light fw-bold">
                        <i class="bi bi-chat-right-dots-fill"></i> Espace Discussion
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="border-top pt-3 mt-4">
                <a href="processes/user/access/logout_process.php" class="btn btn-danger w-100 fw-bold">
                    <i class="bi bi-box-arrow-right me-2"></i> Se déconnecter
                </a>
            </div>

            <?php else: ?>
                <a href="/login.php" class="btn btn-warning text-dark fw-bold fs-5">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Connexion
                </a>
            <?php endif; ?>
    </div>
</div>