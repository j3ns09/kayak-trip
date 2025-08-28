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

function getAccommodationsByStop(PDO $pdo, int $stopId): array {
    $sql = "
        SELECT 
            a.id AS accommodation_id,
            a.name AS accommodation_name,
            a.description,
            a.stars,
            r.id AS room_id,
            r.room_name,
            r.capacity,
            r.base_price
        FROM accommodations AS a
        INNER JOIN rooms AS r ON r.accommodation_id = a.id
        WHERE a.stop_id = :stopId
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('stopId', $stopId, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $accommodations = [];

    foreach ($rows as $row) {
        $accId = $row['accommodation_id'];

        if (!isset($accommodations[$accId])) {
            $accommodations[$accId] = [
                'id' => $row['accommodation_id'],
                'name' => $row['accommodation_name'],
                'description' => $row['description'],
                'stars' => $row['stars'],
                'rooms' => []
            ];
        }

        $accommodations[$accId]['rooms'][] = [
            'id' => $row['room_id'],
            'name' => $row['room_name'],
            'capacity' => $row['capacity'],
            'base_price' => $row['base_price']
        ];
    }

    return array_values($accommodations);
}

function getAccommodation(PDO $pdo, int $accId) {
    $r = $pdo->query("SELECT id, name, description, base_price_per_night, stars FROM accommodations WHERE id = $accId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getAccommodationsFromBooking(PDO $pdo, int $bookingId) : array | bool {
    $r = $pdo->query("SELECT 
    a.id, a.name, a.description, a.stars 
    FROM accommodations as a 
    INNER JOIN booking_accommodations as ba ON a.id = ba.accommodation_id
    WHERE ba.booking_id = $bookingId");

    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAccommodationCol(PDO $pdo, int $accId, string $column) {
    $cols = ['id', 'name', 'description', 'stars'];

    if (!in_array($column, $cols)) {
        return false;
    }
    
    $stmt = $pdo->prepare("SELECT $column FROM accommodations WHERE id = :id");

    $stmt->bindParam(':id', $accId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function getAllPacks(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, name, duration, description, price FROM packs");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllThreads(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllOpenThreads(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, user_id, started_at, is_closed FROM chat_threads WHERE is_closed = 0");
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

function getPromotion(PDO $pdo, string $code) : array | bool {
    $stmt = $pdo->prepare("SELECT code, description, discount_value, valid_from, valid_to, first_time_only FROM promotions WHERE code = :code");
    $stmt->bindParam(':code', $code);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAllServices(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT id, name, description, price, is_active FROM services");
    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getService(PDO $pdo, int $serviceId) : array | bool {
    $r = $pdo->query("SELECT id, name, description, price, is_active FROM services WHERE id = $serviceId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getServiceCol(PDO $pdo, int $serviceId, string $column) {
    $cols = ['name', 'description', 'price', 'is_active'];

    if (!in_array($column, $cols)) {
        return false;
    }
    
    $stmt = $pdo->prepare("SELECT $column FROM services WHERE id = :id");

    $stmt->bindParam(':id', $serviceId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function getServicesFromBooking(PDO $pdo, int $bookingId) : array | bool {
    $r = $pdo->query("SELECT 
    s.id, s.name, s.description, s.price, s.is_active, bs.quantity 
    FROM services as s 
    INNER JOIN booking_services as bs ON s.id = bs.service_id
    WHERE bs.booking_id = $bookingId");

    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getRoom(PDO $pdo, int $roomId) {
    $r = $pdo->query("SELECT 
    r.id, r.accommodation_id, r.room_name AS name, r.capacity, 
    COALESCE(a.price_override, r.base_price) AS price
    FROM rooms AS r
    LEFT JOIN
    room_availability AS a 
    ON a.room_id = r.id 
    WHERE r.id = $roomId
    ");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getRoomCol(PDO $pdo, int $roomId, string $column) {
    $cols = ['id', 'accommodation_id', 'price', 'room_name', 'capacity'];

    if (!in_array($column, $cols)) {
        return false;
    }

    $sql = "SELECT $column FROM rooms WHERE id = :id";

    if ($column == 'price') {
        $sql = "
        SELECT COALESCE(a.price_override, r.base_price) AS price
        FROM rooms AS r
        LEFT JOIN
        room_availability AS a 
        ON a.room_id = r.id 
        WHERE r.id = :id";
    }

    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $roomId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);    
}

function getRoomsFromBooking(PDO $pdo, int $bookingId) : array | bool {
    $r = $pdo->query("SELECT 
    r.id, r.room_name, r.capacity, br.price AS price 
    FROM rooms as r 
    INNER JOIN booking_rooms as br ON r.id = br.room_id
    WHERE br.booking_id = $bookingId");

    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBookings(PDO $pdo) : array | bool {
    $r = $pdo->query("SELECT
    u.id AS user_id,
    u.first_name,
    u.last_name,
    b.id AS booking_id,
    b.start_date,
    b.end_date,
    b.created_at,
    b.total_price,
    b.promotion_code_used

    FROM bookings AS b
    INNER JOIN
    users AS u ON u.id = b.user_id
    ");

    return $r->fetchAll(PDO::FETCH_ASSOC);
}

function getBookings(PDO $pdo, int $userId) : array | bool {
    $r = $pdo->query("SELECT id, user_id, start_date, end_date, created_at, total_price, promotion_code_used FROM bookings WHERE user_id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

function getBookingsDetails(PDO $pdo, int $userId) : array | bool {
    $sql = "
    SELECT 
        b.id AS booking_id,
        b.user_id,
        b.start_date,
        b.end_date,
        b.created_at,
        b.total_price,
        b.promotion_code_used,
        b.person_count,
        b.is_paid
        
        FROM bookings as b
        
        WHERE b.user_id = :user_id
        ";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>