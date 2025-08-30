<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/store.php';
include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';
include_once $root . '/includes/templates/header.html';

if (existsSession('user_id')) {
    $userId = $_SESSION["user_id"];
    if (!isAdmin($pdo, $userId)) {
        redirectAlert('error', 'Accès non autorisé à cette page', 'index');
    }
    $userInfo = getDisplayableUserInfo($pdo, $userId);
} else {
    $userId = null;
}

$isConnected = !is_null($userId);

$userId = (int)$_SESSION['user_id'];

$threadsStmt = $pdo->query("
    SELECT ct.id, ct.user_id, ct.started_at, ct.is_closed,
           u.first_name, u.last_name, u.email
    FROM chat_threads AS ct
    JOIN users AS u ON u.id = ct.user_id
    WHERE ct.is_closed = 0
    ORDER BY ct.started_at DESC
");
$openThreads = $threadsStmt->fetchAll(PDO::FETCH_ASSOC);

include_once $root . '/includes/templates/navbar.php';
?>

<link rel="stylesheet" href="/src/css/home.css">
<link rel="stylesheet" href="/src/css/admin_chat.css">

<div class="container-fluid" id="main">
    <div class="row justify-content-center">
        <div class="col-11 col-md-10">
            <h1 class="title mb-4">Centre de messagerie - Admin</h1>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card glass-card h-100 shadow">
                        <div class="card-header border-0">
                            <i class="bi bi-inbox"></i> Threads ouverts
                        </div>
                        <ul class="list-group list-group-flush scrollable" style="max-height: 70vh;" id="threads-list">
                            <?php foreach ($openThreads as $t): ?>
                                <li class="list-group-item bg-transparent text-white border-0 thread-item" data-thread-id="<?= (int)$t['id'] ?>" data-user-id="<?= (int)$t['user_id'] ?>">
                                    <strong><?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($t['email']) ?></small><br>
                                    <small class="text-muted"><i class="bi bi-clock"></i> <?= htmlspecialchars($t['started_at']) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card glass-card h-100 shadow d-flex flex-column">
                        <div class="card-header border-0" id="chat-header">
                            <span class="text-muted">Aucun thread sélectionné</span>
                        </div>
                        <div class="card-body flex-grow-1 scrollable" id="chat-messages" style="height: 55vh;">
                            <p class="text-muted">Sélectionnez un thread pour commencer la conversation.</p>
                        </div>
                        <div class="card-footer border-0 chat-footer">
                            <div class="input-group">
                                <input type="text" id="chat-input" class="form-control rounded-start" placeholder="Votre réponse...">
                                <button class="btn btn-success" id="chat-send"><i class="bi bi-send"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<script>
const adminId = <?= json_encode($userId) ?>;
let activeThreadId = null;
let activeUserId = null;
let pollingInterval;
let lastMessageTimestamp = null;
let allMessages = [];

document.addEventListener('DOMContentLoaded', () => {
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');
    const chatMessages = document.getElementById('chat-messages');
    const threads = document.querySelectorAll('.thread-item');

    threads.forEach(thread => {
        thread.addEventListener('click', async () => {
            activeThreadId = thread.dataset.threadId;
            activeUserId = thread.dataset.userId;
            const userName = thread.querySelector('strong').textContent;
            document.getElementById('chat-header').innerHTML = `<i class="bi bi-person"></i> ${userName} <span class="badge bg-light text-dark ms-2">Thread #${activeThreadId}</span>`;
            await pollMessages();
        });
    });

    chatSend.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    pollingInterval = setInterval(pollMessages, 3000);

    async function pollMessages() {
        if (!activeThreadId || !activeUserId) return;

        try {
            const response = await fetch(`/api/messages/?userId=${activeUserId}&threadId=${activeThreadId}`);
            const data = await response.json();

            if (data.ok && Array.isArray(data.messages)) {
                const newMessages = data.messages;

                if (JSON.stringify(newMessages) !== JSON.stringify(allMessages)) {
                    allMessages = newMessages;
                    await loadMessages(allMessages);
                }
            }
        } catch (e) {
            console.error("Erreur lors du polling :", e);
        }
    }

    async function loadMessages(messagesss) {
        chatMessages.innerHTML = '';

        if (!messagesss) {
            chatMessages.innerHTML = `<div class="text-danger">'Erreur lors du chargement.'</div>`;
            return;
        }

        messagesss.forEach(msg => {
            const msgDiv = document.createElement('div');
            const isMe = msg.sender_id === adminId;
            msgDiv.className = `mb-2 text-${isMe ? 'end' : 'start'}`;
            msgDiv.innerHTML = `<span class="badge bg-${isMe ? 'success' : 'dark'}">${sanitize(msg.message)}</span><br><small class="text-muted">${msg.sent_at}</small>`;
            chatMessages.appendChild(msgDiv);
        });

        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage() {
        const input = chatInput.value.trim();
        if (!input || input.length > 500 || !activeThreadId) return;

        const payload = {
            userId: adminId,
            threadId: activeThreadId,
            message: input
        };

        const res = await fetch("/api/messages/", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.ok) {
            chatInput.value = '';
            await loadMessages();
        } else {
            alert("Erreur lors de l'envoi.");
        }
    }

    function sanitize(str) {
        const div = document.createElement('div');
        div.innerText = str;
        return div.innerHTML;
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

<?php 
include_once $root . '/includes/templates/offcanvas.php';
include_once $root . '/includes/templates/footer.php'; 
?>
