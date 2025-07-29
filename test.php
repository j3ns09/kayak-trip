<?php

include_once 'includes/functions.php';
include_once 'includes/config/config.php';

var_dump(userExistsPasswordCorrect($pdo, "test@test.fr", "motdepasse"));

exit();

?>