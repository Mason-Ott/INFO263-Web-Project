<?php
session_start();

// We just have one user for this example
$username_to_password_map = [
    'admin' => 'admin'
];

// Check for login attempt
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    if (array_key_exists($username, $username_to_password_map) && $username_to_password_map[$username] === $password) {
        // Redirect to admin user management page
        header('Location: user_management.php');
        exit();
    } else {
        $login_error = true;
    }
}

?>