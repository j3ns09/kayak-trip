<?php

session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/functions.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/includes/config/config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $data = [
        'email' => $email,
    ];
    
    $_SESSION['form_data'] = $data;
    
    if (empty($password)) {
        redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'register');
        exit();
    }
    foreach ($data as $key => $value) {
        if (empty($value)) {
            redirectAlert('error', 'Tous les champs doivent être remplis correctement', 'register');
            exit();
        }
    }
    
    if (userExistsPasswordCorrect($pdo, $email, $password)) {
        unsetSession('form_data');
        
        $userId = getUserId($pdo, $email);
        $_SESSION['user_id'] = $userId;
        $_SESSION['event'] = 'login';
        setUserOnline($pdo, $userId);
    
        redirect('index');
        exit();
    } else {
        redirectAlert('error', 'Adresse e-mail ou mot de passe incorrect', 'login');
        exit();
    }

}    

redirect('index');
exit();

?>