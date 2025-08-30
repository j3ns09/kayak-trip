<footer class="d-flex justify-content-between align-items-center px-3 sticky-bottom z-0" style="background-color: #18191C; height: 50px;">
    
    <p class="Text mb-0" style="color: #767F8C;">2025 Kayak Trip. Tous droits réservés</p>

    <?php if (!isSubscribed($pdo, $userId)): ?>
    <div class="d-flex align-items-center ms-auto">
        <span class="me-2" style="color:#fff;">Rejoignez notre newsletter</span>
        <form id="newsletter-form">
            <input id="iduser" type="hidden" value="<?= $userId ?>">
            <button class="btn btn-sm btn-outline-primary fw-bold" type="submit">
                S'abonner
            </button>
        </form>
    </div>
    <?php endif; ?>

</footer>

<script src="/src/js/footer/main.js" type="module"></script>

</body>
</html>