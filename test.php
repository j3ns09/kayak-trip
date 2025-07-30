<?php

include_once 'includes/functions.php';
include_once 'includes/config/config.php';


var_dump(
    getUserId($pdo, "test@test.fr")
);

exit();

?>