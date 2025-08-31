<?php

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

function deletePack(PDO $pdo, int $packId) {
    $stmt = $pdo->prepare("DELETE FROM packs WHERE id = :id");
    $stmt->bindParam(':id', $packId, PDO::PARAM_INT);
    return $stmt->execute();
}

function deleteUser(PDO $pdo, int $userId) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    return $stmt->execute();
}

function deleteSub(PDO $pdo, int $subId) {
    $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = :id");
    $stmt->bindParam(':id', $subId, PDO::PARAM_INT);
    return $stmt->execute();
}
?>