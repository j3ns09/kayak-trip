<?php

// set, get, create, delete

// Users
function setUserOnline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 1 WHERE id = $userId");
}

function setUserOffline(PDO $pdo, int $userId) {
    $r = $pdo->query("UPDATE users SET is_online = 0 WHERE id = $userId");
}

function getUserId(PDO $pdo, string $email) {
    $r = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $r->bindParam(':email', $email, PDO::PARAM_STR);
    $r->execute();
    return $r->fetch(PDO::FETCH_COLUMN);
}

function getDisplayableUserInfo(PDO $pdo, int $userId) {
    $r = $pdo->query("SELECT first_name, last_name, phone, email FROM users WHERE id = $userId");
    return $r->fetch(PDO::FETCH_ASSOC);
}

// Stops
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

function deleteStop(PDO $pdo, int $stopId) {
    $stmt = $pdo->prepare("DELETE FROM stops WHERE id = :id");
    $stmt->bindParam(':id', $stopId, PDO::PARAM_INT);
    return $stmt->execute();
}
?>