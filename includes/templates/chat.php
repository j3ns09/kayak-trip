<button id="chat-toggle" class="btn btn-warning rounded-circle shadow"
        style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px;">
    <i class="bi bi-chat-dots-fill fs-3 text-white"></i>
</button>

<div id="chat-window" class="card shadow-lg d-none"
     style="position: fixed; bottom: 90px; right: 20px; width: 350px; height: 450px;">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people"></i> Chat Commercial</span>
        <button id="chat-close" class="btn-close btn-close-white"></button>
    </div>
    <div id="chat-messages" class="card-body bg-light overflow-auto" style="height: 320px;">
        <div class="text-muted small">Bienvenue ! Décrivez votre problème, un conseiller vous répondra rapidement.</div>
    </div>
    <div class="card-footer d-flex">
        <input id="chat-input" type="text" class="form-control me-2" placeholder="Écrivez un message...">
        <button id="chat-send" class="btn btn-success"><i class="bi bi-send"></i></button>
    </div>
</div>

<script>
    const userId = <?= json_encode($userId) ?>;
</script>
<script src="/src/js/chat/main.js"></script>