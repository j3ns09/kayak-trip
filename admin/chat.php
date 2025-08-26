<?php
$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/store.php';
include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';
include_once $root . '/includes/templates/header.html';

if (!isset($_SESSION['user_id']) || !isAdmin($pdo, $_SESSION['user_id'])) {
    redirect('index');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reply') {
    $threadId = filter_input(INPUT_POST, 'thread_id', FILTER_VALIDATE_INT);
    $message  = trim($_POST['message'] ?? '');
    $adminId  = (int)$_SESSION['user_id'];

    if ($threadId && $message !== '') {
        if (function_exists('createMessage')) {
            createMessage($pdo, $threadId, 'admin', $adminId, $message);
            redirect("admin/chat?thread_id={$threadId}");
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO chat_messages (thread_id, sender_type, sender_id, message) VALUES (:tid, 'admin', :sid, :msg)");
            $stmt->execute([':tid' => $threadId, ':sid' => $adminId, ':msg' => $message]);
            redirect("admin/chat?thread_id={$threadId}");
            exit();
        }
    }
}

if (isset($_GET['close_thread'])) {
    $toClose = filter_input(INPUT_GET, 'close_thread', FILTER_VALIDATE_INT);
    if ($toClose) {
        if (function_exists('setThreadClosed')) {
            setThreadClosed($pdo, $toClose);
        } else {
            $pdo->query("UPDATE chat_threads SET is_closed = 1 WHERE id = " . (int)$toClose);
        }
        redirect("admin/chat");
        exit();
    }
}

$threadsStmt = $pdo->query("
    SELECT ct.id, ct.user_id, ct.started_at, ct.is_closed,
           u.first_name, u.last_name, u.email
    FROM chat_threads AS ct
    JOIN users AS u ON u.id = ct.user_id
    WHERE ct.is_closed = 0
    ORDER BY ct.started_at DESC
");
$openThreads = $threadsStmt->fetchAll(PDO::FETCH_ASSOC);

$selectedThreadId = filter_input(INPUT_GET, 'thread_id', FILTER_VALIDATE_INT);
if (!$selectedThreadId && !empty($openThreads)) {
    $selectedThreadId = (int)$openThreads[0]['id'];
}

$messages = [];
$selectedUser = null;
if ($selectedThreadId) {
    $uStmt = $pdo->prepare("
        SELECT u.id, u.first_name, u.last_name, u.email, ct.started_at
        FROM chat_threads ct
        JOIN users u ON u.id = ct.user_id
        WHERE ct.id = :tid
    ");
    $uStmt->execute([':tid' => $selectedThreadId]);
    $selectedUser = $uStmt->fetch(PDO::FETCH_ASSOC);

    if ($selectedUser) {
        if (function_exists('getAllMessagesFromThread')) {
            $messages = getAllMessagesFromThread($pdo, $selectedThreadId) ?: [];
        } else {
            $mStmt = $pdo->prepare("
                SELECT id, thread_id, sender_type, sender_id, message, sent_at
                FROM chat_messages
                WHERE thread_id = :tid
                ORDER BY sent_at ASC, id ASC
            ");
            $mStmt->execute([':tid' => $selectedThreadId]);
            $messages = $mStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>

<div class="container-fluid my-4">
    <div class="mb-4">
        <a href="/index.php" class="btn btn-outline-dark btn-sm rounded-pill">
            <i class="bi bi-arrow-left-circle"></i> Retour utilisateur
        </a>
    </div>
    <h1 class="mb-4">Centre de messagerie</h1>
    <div class="row" style="min-height: 70vh;">
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-inbox"></i> Threads ouverts</span>
                    <span class="badge bg-warning text-dark"><?= count($openThreads) ?></span>
                </div>
                <ul class="list-group list-group-flush" style="max-height: 70vh; overflow: auto;">
                    <?php if (empty($openThreads)) : ?>
                        <li class="list-group-item text-muted">Aucun thread ouvert</li>
                    <?php else: ?>
                        <?php foreach ($openThreads as $t): ?>
                            <?php
                                $active = ($selectedThreadId === (int)$t['id']) ? 'active' : '';
                                $userLabel = htmlspecialchars(($t['first_name'] ?? '') . ' ' . ($t['name'] ?? ''));
                                $email = htmlspecialchars($t['email'] ?? '');
                                $started = htmlspecialchars($t['started_at'] ?? '');
                            ?>
                            <a class="list-group-item list-group-item-action <?= $active ?>" href="/admin/chat.php?thread_id=<?= (int)$t['id'] ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold"><?= $userLabel ?></div>
                                        <div class="small text-muted"><?= $email ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted"><i class="bi bi-clock"></i> <?= $started ?></div>
                                        <a class="btn btn-sm btn-outline-danger mt-1" href="/admin/chat.php?close_thread=<?= (int)$t['id'] ?>" onclick="return confirm('Fermer ce thread ?')">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="col-12 col-md-8 col-lg-9 mt-4 mt-md-0">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <?php if ($selectedUser): ?>
                            <span class="fw-semibold"><i class="bi bi-person"></i>
                                <?= htmlspecialchars(($selectedUser['first_name'] ?? '') . ' ' . ($selectedUser['name'] ?? '')) ?>
                            </span>
                            <span class="small ms-2"><?= htmlspecialchars($selectedUser['email'] ?? '') ?></span>
                        <?php else: ?>
                            <span class="fw-semibold">Aucun thread sélectionné</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($selectedThreadId): ?>
                        <span class="badge bg-light text-dark">Thread #<?= (int)$selectedThreadId ?></span>
                    <?php endif; ?>
                </div>

                <div class="card-body bg-light" style="height: 60vh; overflow: auto;">
                    <?php if ($selectedThreadId && !empty($messages)): ?>
                        <?php foreach ($messages as $m): ?>
                            <?php
                                $isClient = ($m['sender_type'] ?? '') === 'client';
                                $bubbleClass = $isClient ? 'bg-success text-white' : 'bg-dark text-white';
                                $alignClass = $isClient ? 'text-end' : 'text-start';
                                $content = htmlspecialchars($m['message'] ?? '');
                                $time = htmlspecialchars($m['sent_at'] ?? '');
                            ?>
                            <div class="mb-2 <?= $alignClass ?>">
                                <div class="d-inline-block rounded px-3 py-2 <?= $bubbleClass ?>">
                                    <?= nl2br($content) ?>
                                </div>
                                <div class="small text-muted"><?= $time ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php elseif ($selectedThreadId): ?>
                        <div class="text-muted">Aucun message pour ce thread.</div>
                    <?php else: ?>
                        <div class="text-muted">Sélectionnez un thread pour afficher les messages.</div>
                    <?php endif; ?>
                </div>

                <?php if ($selectedThreadId): ?>
                <div class="card-footer">
                    <form method="post" class="d-flex gap-2">
                        <input type="hidden" name="action" value="reply">
                        <input type="hidden" name="thread_id" value="<?= (int)$selectedThreadId ?>">
                        <input type="text" name="message" class="form-control" placeholder="Votre réponse..." required>
                        <button class="btn btn-primary"><i class="bi bi-send"></i></button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once $root . '/includes/templates/footer.html'; ?>
