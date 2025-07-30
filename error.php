<?php

$code = filter_input(INPUT_GET, "code", FILTER_VALIDATE_INT);

$allowed = [404, 500];

if (!in_array($code, $allowed) || empty($code)) {
    $code = 404;
}

http_response_code($code);

include $_SERVER["DOCUMENT_ROOT"] . "/errors/{$code}.html";
exit();
