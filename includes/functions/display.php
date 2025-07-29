<?php

function alertMessage(string $alert, int $kind) {
  
    global $checkCircleFill, $xCircleFill, $exclamationCircleFill, $infoFill, $gearFill;

    switch ($kind) {
        case 0:
            $kindBtstp = "alert-success";
            $icon = $checkCircleFill;
            break;
        case 1:
            $kindBtstp = "alert-danger";
            $icon = $xCircleFill;
            break;
        case 2:
            $kindBtstp = "alert-warning";
            $icon = $exclamationCircleFill;
            break;
        case 3:
            $kindBtstp = "alert-info";
            $icon = $infoFill;
            break;
        default:
            $kindBtstp = "alert-light";
            $icon = $gearFill;
            break;
    }

    $html = '<div class="alert ' . $kindBtstp . ' text-center mb-0">' . $icon . '
        <span class="ms-1">' . $alert . '</span>
        </div>';
    
    echo $html;
}

function displayAlert(string $key, int $kind) {
    if (isset($_SESSION[$key]) && !is_null($_SESSION[$key])) {
        alertMessage($_SESSION[$key], $kind);
    }
}

?>