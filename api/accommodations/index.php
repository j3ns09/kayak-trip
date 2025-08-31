<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];

include_once $root . '/includes/config/config.php';
include_once $root . '/includes/functions.php';

header("Content-Type: application/json");

if (existsSession('user_id')) {
    $key = getApiKey($pdo, $_SESSION['user_id']);

    if (!$key) {
        echo json_encode(["ok" => false, "error" => "Erreur: Pas de clé pour l'API"]);
        exit();
    }
} else {
    echo json_encode(["ok" => false, "error" => "Erreur: Utilisateur non connecté"]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    $stopId = filter_input(INPUT_GET, 'stop_id', FILTER_VALIDATE_INT);
    $page   = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

    if (is_null($stopId)) {
        $stmt = $pdo->prepare("
            SELECT a.id, a.name, a.description, a.stars, s.name AS stop_name,
                   COUNT(r.id) AS rooms_count,
                   COALESCE(AVG(r.base_price), 0) AS avg_price
            FROM accommodations a
            LEFT JOIN stops s ON a.stop_id = s.id
            LEFT JOIN rooms r ON r.accommodation_id = a.id
            GROUP BY a.id
        ");
        $stmt->execute();
        $accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = $pdo->query("SELECT COUNT(*) FROM accommodations")->fetchColumn();

        foreach ($accommodations as &$acc) {
            $roomStmt = $pdo->prepare("
                SELECT ra.is_available, ra.price_override, r.base_price AS price
                FROM rooms r
                LEFT JOIN room_availability ra 
                ON r.id = ra.room_id 
                AND ra.date >= CURDATE()
                WHERE r.accommodation_id = :acc_id
            ");
            $roomStmt->bindValue(":acc_id", $acc['id'], PDO::PARAM_INT);
            $roomStmt->execute();
            $rooms = $roomStmt->fetchAll(PDO::FETCH_ASSOC);

            $available = false;
            $prices = [];

            foreach ($rooms as $room) {
                if ($room['is_available'] == 1) {
                    $available = true;
                }
                $prices[] = $room['price_override'] ?? $room['price'];
            }

            if (!$available) {
                $accStmt = $pdo->prepare("
                    SELECT is_available
                    FROM accommodation_availability
                    WHERE accommodation_id = :id
                    AND date >= CURDATE()
                    ORDER BY date ASC
                    LIMIT 1
                ");
                $accStmt->bindValue(":id", $acc['id'], PDO::PARAM_INT);
                $accStmt->execute();
                $accAvail = $accStmt->fetch(PDO::FETCH_ASSOC);

                if ($accAvail) {
                    $available = (bool)$accAvail['is_available'];
                }
            }

            $acc['available'] = $available;
            $acc['price'] = !empty($prices) ? min($prices) : $acc['avg_price'];
        }
    } else {
        $accommodations = getAccommodationsByStop($pdo, $stopId);
        $total = count($accommodations);
    }

    if (!$accommodations) {
        echo json_encode(["ok" => false, "error" => "Pas d'hébergements ou mauvaise réponse", "response" => $accommodations]);
        exit();
    }

    echo json_encode([
        "ok" => true,
        "waiter" => $_SESSION['user_id'],
        "accommodations" => $accommodations,
        "total" => $total,
        "page" => $page,
        "pages" => ceil($total)
    ]);
}

exit();

?>