<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Admin Login</title>
    <link rel="stylesheet" href="CSS_Files/admin_login.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="vehicles.php">Vehicles</a></li>
            <li><a href="trips.php">Trips</a></li>
            <li><a href="maintenance.php">Maintenance</a></li>
            <li><a href="relocations.php">Relocations</a></li>
            <li><a href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
            <li><a href="indicator.php">Quarterly Indicators</a></li>
            <li><a href="admin_login.php">Database Admin Login</a></li>
        </ul>
    </nav>
</header>

<div class="login-container">
    <h2>Admin Login</h2>
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