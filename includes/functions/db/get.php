<?php

function getUserId(PDO $pdo, string $email) : int | bool {
    $r = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    $r->execute();
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getUserStatus(PDO $pdo, int $userId) : int | bool {
    $r = $pdo->query("SELECT is_online FROM users WHERE user_id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getDisplayableUserInfo(PDO $pdo, int $userId) : array | bool {
    $r = $pdo->query("SELECT first_name, last_name, phone, email FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

// Api
function getApiKey(PDO $pdo, int $userId) : string | bool {
    $r = $pdo->query("SELECT api_key FROM api_keys WHERE user_id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getAllUsers(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, first_name, last_name, email, phone, is_admin FROM users");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllStops(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, name, description, latitude, longitude FROM stops");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAccommodations(PDO $pdo) : array | bool {
    $r = $pdo->query("
        SELECT 
        a.id,
        a.name,
        a.description,
        s.name AS stop_name,
        a.base_price_per_night,
        COUNT(DISTINCT r.id) AS nb_chambres,
        COALESCE(SUM(r.capacity), 0) AS capacite_totale,
        (
            SELECT 
                GROUP_CONCAT(DISTINCT DATE_FORMAT(rv.date, '%d/%m/%Y') ORDER BY rv.date ASC SEPARATOR ', ')
            FROM room_availability rv
            JOIN rooms r2 ON rv.room_id = r2.id
            WHERE r2.accommodation_id = a.id AND rv.is_available = 0
        ) AS dates_fermeture
    FROM accommodations a
    JOIN stops s ON a.stop_id = s.id
    LEFT JOIN rooms r ON r.accommodation_id = a.id
    GROUP BY a.id, a.name, a.description, s.name, a.base_price_per_night
    ");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPacks(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, name, duration, description, price FROM packs");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllThreads(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getThread(PDO $pdo, int $threadId) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE id = $threadId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getOpenThreadFromUser(PDO $pdo, int $userId) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE user_id = $userId AND is_closed = 0");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getThreadsFromUser(PDO $pdo, int $userId) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE user_id = $userId");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMessagesFromThread(PDO $pdo, int $threadId) : array | bool {
    $r = $pdo->query("SELECT id, thread_id, sender_type, sender_id, message, sent_at FROM chat_messages WHERE thread_id = $threadId");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMessages(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, thread_id, sender_type, sender_id, message, sent_at FROM chat_messages");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getThreadState(PDO $pdo, int $threadId) : int | bool {
    $r = $pdo->query("SELECT is_closed FROM chat_threads WHERE id = $threadId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getAllPromotions(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT code, description, discount_value, valid_from, valid_to, first_time_only FROM promotions");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllServices(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, name, description, price, is_active FROM services");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

// TODO: Terminer sql
function getOrders(PDO $pdo, int $userId) {
    $sql = "
    SELECT 
        booking.id

    ";
}

?>