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

function displayToast(string $id, string $btspColor, string $title, string $time, string $message) {
    echo '
<div class="toast-container position-fixed top-0 end-0 p-3 my-4 ">
  <div id="' . $id . '" class="toast text-bg-' . $btspColor . '" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">' . $title . '</strong>
      <small>' . $time . '</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      ' . $message . '
    </div>
  </div>
</div>
';
}

function displayItem(array $option, int $qty) {
    $title = $option['name'];
    $price = $option['price'];
    $subtotal = $price * $qty;
    echo '
    <div class="d-flex align-items-center mb-3">
        <div class="ms-3 flex-grow-1">
            <div class="fw-semibold">' . $title . '</div>
            <div class="text-muted small">Prix unitaire: ' . $price . ' €</div>
            <div class="text-muted small">Quantité: ' . $qty . '</div>
        </div>
        <div class="text-end">
            <strong>' . $subtotal . ' €</strong>
        </div>
    </div>
    ';
}


function frenchDate(string $isoDate) {
    $date = (new DateTime($isoDate))->format('d/m/Y');
    return $date;
}

?>