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
    string | null $dateStart,
    string | null $dateEnd,
    string $description,
    int $reduction,
    int | null $uniqueUse
)
{
    $stmt = $pdo->prepare("UPDATE promotions SET code = :code, valid_from = :dateStart, valid_to = :dateEnd, description = :description, discount_value = :reduction, first_time_only = :uniqueUse");

    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':reduction', $reduction, PDO::PARAM_INT);
    $stmt->bindParam(':uniqueUse', $uniqueUse, PDO::PARAM_INT);
    
    if (empty($dateStart)) {
        $stmt->bindValue(':dateStart', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':dateStart', $dateStart, PDO::PARAM_STR);
    }
    if (empty($dateEnd)) {
        $stmt->bindValue(':dateEnd', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':dateEnd', $dateEnd, PDO::PARAM_STR);
    }

    return $stmt->execute();
}

function setPackNewValues(
    PDO $pdo,
    int $id,
    string $name,
    int $duration,
    string $description,
    float $price,
    array $stops,
    array $accommodations
): bool 
{
    $stmt = $pdo->prepare("
        UPDATE packs 
        SET name = :name, duration = :duration, description = :description, price = :price
        WHERE id = :id
    ");

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);

    if (!$stmt->execute()) return false;

    $pdo->prepare("DELETE FROM pack_stops WHERE pack_id = :id")
        ->execute([':id' => $id]);

    $pdo->prepare("DELETE FROM pack_accommodations WHERE pack_id = :id")
        ->execute([':id' => $id]);

    $stmtStop = $pdo->prepare("
        INSERT INTO pack_stops (pack_id, stop_id, stop_order)
        VALUES (:pack_id, :stop_id, :stop_order)
    ");

    $stmtAcc = $pdo->prepare("
        INSERT INTO pack_accommodations (pack_id, stop_id, accommodation_id)
        VALUES (:pack_id, :stop_id, :accommodation_id)
    ");

    foreach ($stops as $i => $stopId) {
        $order = $i + 1;

        $ok = $stmtStop->execute([
            ':pack_id' => $id,
            ':stop_id' => $stopId,
            ':stop_order' => $order,
        ]);

        if (!$ok) return false;

        $accommodationId = $accommodations[$i] ?? null;

        if (!empty($accommodationId)) {
            $ok = $stmtAcc->execute([
                ':pack_id' => $id,
                ':stop_id' => $stopId,
                ':accommodation_id' => $accommodationId,
            ]);

            if (!$ok) return false;
        }
    }

    return true;
}


function setUserNewValues(
    PDO $pdo,
    int $id,
    string $firstName,
    string $lastName,
    string $email,
    string $phone,
    string | null $password,
    string | null $passwordConfirm
)
{
    if (is_null($password) && is_null($passwordConfirm) && $password !== $passwordConfirm) {
        return false;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET 
    first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, password = :password
    WHERE id = :id");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $hashedPassword);

    return $stmt->execute();
}

function setUserNewValuesAdmin(
    PDO $pdo,
    int $id,
    string $firstName,
    string $lastName,
    string $email,
    string $phone
)
{

    $stmt = $pdo->prepare("UPDATE users SET 
    first_name = :first_name, last_name = :last_name, email = :email, phone = :phone
    WHERE id = :id");

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    return $stmt->execute();
}


?>