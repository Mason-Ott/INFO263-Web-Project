<?php
require_once 'PHP_Handlers/user_management_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="CSS_Files/user_management.css">
</head>
<body>
    <h2>User Management System</h2>
    <div class="logout">
        <form method="post">
            <input type="hidden" name="logout" value="true">
            <input type="submit" value="Logout">
        </form>
    </div>
</body>
</html>