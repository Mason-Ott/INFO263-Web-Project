<?php
require_once 'PHP_Handlers/admin_login_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Admin Login</title>
    <link rel="stylesheet" href="CSS_Files/base.css">
    <link rel="stylesheet" href="CSS_Files/admin_login.css">
</head>
<body>
    <script>
        <!-- Toggle Navigation Hamburger-->
        function toggleMenu() {
            const nav = document.getElementById('nav');
            const topnav = document.querySelector('.topnav');
            nav.classList.toggle('active');
            topnav.classList.toggle('active');
            console.log('Hamburger clicked, nav active:', nav.classList.contains('active'));
        }
    </script>

    <!-- Navigation Bar -->
    <header>
        <div class="topnav">
            <div class="hamburger" id="hamburger" onclick="toggleMenu()">&#9776;</div>
            <nav id="nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="trips.php">Trips</a></li>
                    <li><a href="maintenance.php">Maintenance</a></li>
                    <li><a href="relocations.php">Relocations</a></li>
                    <li><a href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
                    <li><a href="charts.php">Charts</a></li>
                    <li><a class="active" href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if (isset($login_error)) { ?>
        <div id="login-error">Invalid Credentials!</div>
        <div id="Hint">Hint: Try admin for both</div>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <div class="form-group">
            <input type="submit" value="Login">
        </div>
    </form>
</div>

</body>
</html>