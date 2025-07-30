<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';

setUserOffline($pdo, $_SESSION['user_id']);
unset($_SESSION['user_id']);
$_SESSION['event'] = 'logout';

redirect('index');
exit();

?>