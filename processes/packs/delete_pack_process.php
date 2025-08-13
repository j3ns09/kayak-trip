<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($id) {
        deletePack($pdo, $id);
    }
}

redirect("admin/dashboard");
exit();

?>