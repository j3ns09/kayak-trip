<?php

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

    if ($stmt->execute()) {
        return (int)$pdo->lastInsertId();
    }

    return false;
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
    string | null $dateStart,
    string | null $dateEnd,
    string $description,
    int $reduction,
    int $unique
)
{
    $stmt = $pdo->prepare("
    INSERT INTO promotions (code, valid_from, valid_to, description, discount_value, first_time_only) 
    VALUES (:code, :valid_from, :valid_to, :description, :discount_value, :first_time_only)");
    
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':discount_value', $reduction, PDO::PARAM_INT);
    $stmt->bindParam(':first_time_only', $unique, PDO::PARAM_INT);

    if (empty($dateStart)) {
        $stmt->bindValue(':valid_from', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':valid_from', $dateStart, PDO::PARAM_STR);
    }
    if (empty($dateEnd)) {
        $stmt->bindValue(':valid_to', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':valid_to', $dateEnd, PDO::PARAM_STR);
    }
    
    return $stmt->execute();
}

function createPack(
    PDO $pdo,
    string $name,
    int $duration,
    string $description,
    float $price
)
{
    $stmt = $pdo->prepare("
    INSERT INTO packs (name, duration, description, price)
    VALUES (:name, :duration, :description, :price)
    ");

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);

    return $stmt->execute();
}

function createRoute(PDO $pdo, int $userId) {
    $sqlRoutes = "INSERT INTO routes (user_id) VALUES (:user_id)";
    
    $stmt = $pdo->prepare($sqlRoutes);

    $stmt->bindParam(':userId', $userId);

    if ($stmt->execute()) {
        return (int)$pdo->lastInsertId();
    }

    return false;
}

function createRouteStops(PDO $pdo, int $routeId, array $stops_ids) {
    $queries = [];
    $i = 0;
    
    foreach ($stops_ids as $stop_id) {
        $sqlRoutesStops = "INSERT INTO route_stops (route_id, stop_id, order_in_route) VALUES (:route_id, :stop_id, :order_in_route)";
        
        $stmt = $pdo->prepare($sqlRoutesStops);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':stop_id', $stops_ids[$i]);
        $stmt->bindParam(':order_in_route', $i);

        array_push($queries, $stmt);
    }

    foreach($queries as $query) {
        $query->execute();
    }

}

?>