<?php

// set, get, create, delete

// Users
function setUserOnline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 1 WHERE id = $userId");
}

function setUserOffline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 0 WHERE id = $userId");
}

function setThreadClosed(PDO $pdo, int $threadId) {
    $r = $pdo->query("UPDATE chat_threads SET is_closed = 1 WHERE id = $threadId");
}

function setStopNewValues(
    PDO $pdo, 
    int $stopId, 
    string $name,
    float $lat,
    float $lng,
    string $description
    )
{
    $sql = "UPDATE stops SET 
    name = :name,
    latitude = :latitude,
    longitude = :longitude,
    description = :description
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':latitude', $lat);
    $stmt->bindParam(':longitude', $lng);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $stopId, PDO::PARAM_INT);

    return $stmt->execute();
}

function setServiceNewValues(
    PDO $pdo,
    int $id,
    string $name,
    string $description,
    float $price
)
{
    $stmt = $pdo->prepare("UPDATE services SET name = :name, description = :description, price = :price WHERE id = :id");

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);

    return $stmt->execute();
}

function setDiscountNewValues(
    PDO $pdo,
    string $code,
    string $dateStart,
    string $dateEnd,
    string $description,
    int $reduction,
    int $uniqueUse
)
{
    $stmt = $pdo->prepare("UPDATE promotions SET code = :code, valid_from = :dateStart, valid_to = :dateEnd, description = :description, discount_value = :reduction, first_time_only = :uniqueUse");

    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':dateStart', $dateStart, PDO::PARAM_STR);
    $stmt->bindParam(':dateEnd', $dateEnd, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':reduction', $reduction, PDO::PARAM_INT);
    $stmt->bindParam(':uniqueUse', $uniqueUse, PDO::PARAM_INT);

    return $stmt->execute();
}

function getUserId(PDO $pdo, string $email) {
    $r = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    $r->execute();
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getUserStatus(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT is_online FROM users WHERE user_id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getDisplayableUserInfo(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT first_name, last_name, phone, email FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

// Api
function getApiKey(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT api_key FROM api_keys WHERE user_id = $userId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getAllUsers(PDO $pdo) {
    $r = $pdo->query("SELECT id, first_name, last_name, email, phone, is_admin FROM users");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllStops(PDO $pdo) {
    $r = $pdo->query("SELECT id, name, description, latitude, longitude FROM stops");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAccommodations(PDO $pdo) {
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

function getAllPacks(PDO $pdo) {
    $r = $pdo->query("SELECT id, name, description, price, available_from, available_to FROM pack_templates");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllThreads(PDO $pdo) {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getThread(PDO $pdo, int $threadId) {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE id = $threadId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getThreadsFromUser(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE user_id = $userId");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMessagesFromThread(PDO $pdo, int $threadId) {
    $r = $pdo->query("SELECT id, thread_id, sender_type, sender_id, message, sent_at FROM chat_messages WHERE thread_id = $threadId");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMessages(PDO $pdo) {
    $r = $pdo->query("SELECT id, thread_id, sender_type, sender_id, message, sent_at FROM chat_messages");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getThreadState(PDO $pdo, int $threadId) {
    $r = $pdo->query("SELECT is_closed FROM chat_threads WHERE id = $threadId");
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getAllPromotions(PDO $pdo) {
    $r = $pdo->query("SELECT code, description, discount_value, valid_from, valid_to, first_time_only FROM promotions");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllServices(PDO $pdo) {
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

function createUser(
    PDO $pdo,
    string $name,
    string $firstName,
    string $phone,
    string $email,
    string $password,
    string $confirmPassword
    )
{
    if (!$firstName || !$name || !$email || !$phone || !$password || !$confirmPassword) {
        return ['ok' => false, 'message' => "Tous les champs sont obligatoires."];
    } else if ($password !== $confirmPassword) {
        return ['ok' => false, 'message' => "Les mots de passe ne correspondent pas."];
    }

    $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        return ['ok' => false, 'message' => "Email déjà utilisé."];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = 
    "INSERT INTO users 
    (email, password, first_name, last_name, phone) 
    VALUES 
    (:email, :password, :first_name, :last_name, :phone)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

    if ($stmt->execute()) return ['ok' => true, 'message' => ""];

    return ['ok' => false, 'message' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."];

}

function createStop(
    PDO $pdo,
    string $name,
    float $lat,
    float $lng,
    string $type
    )
{
    $sql = "INSERT INTO stops (name, description, latitude, longitude) VALUES (:name, :type, :lat, :lng)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':lat', $lat);
    $stmt->bindParam(':lng', $lng);
    $stmt->bindParam(':type', $type);

    return $stmt->execute();
}

function createThread(PDO $pdo, int $userId) {
    $sql = "INSERT INTO chat_threads (user_id) VALUES (:user_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    return $stmt->execute();
}

function createMessage(
    PDO $pdo,
    int $threadId,
    string $senderType,
    int $senderId,
    string $message,
)
{
    $sql = "
    INSERT INTO chat_messages (thread_id, sender_type, sender_id, message) 
    VALUES (:thread_id, :sender_type, :sender_id, :message)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':thread_id', $threadId, PDO::PARAM_INT);
    $stmt->bindParam(':sender_type', $senderType, PDO::PARAM_STR);
    $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    return $stmt->execute();
}

function createService(
    PDO $pdo,
    string $name,
    string $description,
    float $price
)
{
    $stmt = $pdo->prepare("INSERT INTO services (name, description, price) VALUES (:name, :description, :price)");
    
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);

    return $stmt->execute();
}

function createDiscount(
    PDO $pdo,
    string $code,
    string $dateStart,
    string $dateEnd,
    string $description,
    int $reduction,
    int $unique
)
{
    $stmt = $pdo->prepare("
    INSERT INTO promotions (code, valid_from, valid_to, description, discount_value, first_time_only) 
    VALUES (:code, :valid_from, :valid_to, :description, :discount_value, :first_time_only)");
    
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':valid_from', $dateStart, PDO::PARAM_STR);
    $stmt->bindParam(':valid_to', $dateEnd, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':discount_value', $reduction, PDO::PARAM_INT);
    $stmt->bindParam(':first_time_only', $unique, PDO::PARAM_INT);

    return $stmt->execute();
}

function deleteStop(PDO $pdo, int $stopId) {
    $stmt = $pdo->prepare("DELETE FROM stops WHERE id = :id");
    $stmt->bindParam(':id', $stopId, PDO::PARAM_INT);
    return $stmt->execute();
}

function deleteService(PDO $pdo, int $serviceId) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
    $stmt->bindParam(':id', $serviceId, PDO::PARAM_INT);
    return $stmt->execute();
}

function deleteDiscount(PDO $pdo, string $code) {
    $stmt = $pdo->prepare("DELETE FROM promotions WHERE code = :code");
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    return $stmt->execute();
}

?>