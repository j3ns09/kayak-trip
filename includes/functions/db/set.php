<?php

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

function setPackNewValues(
    PDO $pdo,
    string $name,
    int $duration,
    string $description,
    float $price
)
{
    $stmt = $pdo->prepare("
    UPDATE packs 
    SET name = :name, duration = :duration, description = :description, price = :price
    ");

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);

    return $stmt->execute();
}
?>