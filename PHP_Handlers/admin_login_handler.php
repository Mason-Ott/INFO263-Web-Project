<?php
session_start();

$username_to_password_map = [
    'admin' => 'admin'
];

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (array_key_exists($username, $username_to_password_map) && $username_to_password_map[$username] === $password) {
        header('Location: user_management.php');
        exit();
    } else {
        $login_error = true;
    }
}

?>