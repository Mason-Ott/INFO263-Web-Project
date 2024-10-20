<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rental Service</title>
    <link rel="stylesheet" href="css_files/base.css">
    <link rel="stylesheet" href="css_files/index.css">
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
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a href="vehicles.php">Vehicles</a></li>
                    <li><a href="trips.php">Trips</a></li>
                    <li><a href="maintenance.php">Maintenance</a></li>
                    <li><a href="relocations.php">Relocations</a></li>
                    <li><a href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
                    <li><a href="charts.php">Charts</a></li>
                    <li><a href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    require_once 'php_handlers/index_handler.php';
    ?>


    <h1>Summary</h1>
    <section class="section">
        <div class="category">
            <h3>Trips</h3>
            <p>Completed:
                <span><?php echo htmlspecialchars($trip_completed); ?></span>
            </p>
            <p>Upgrade:
                <span><?php echo htmlspecialchars($trip_upgraded); ?></span>
            </p>
        </div>

        <div class="category">
            <h3>Refused</h3>
            <p>Bookings:
                <span><?php echo htmlspecialchars($refused_bookings); ?></span>
            </p>
            <p>Walk-ins:
                <span><?php echo htmlspecialchars($refused_walk_ins); ?></span>
            </p>
        </div>

        <div class="category">
            <h3>Vehicles</h3>
            <p>Relocated:
                <span><?php echo htmlspecialchars($vehicles_relocated); ?></span>
            </p>
            <p>Serviced:
                <span><?php echo htmlspecialchars($vehicles_serviced); ?></span>
            </p>
        </div>
    </section>

    <h1>Car Types</h1>

    <section class="section">
        <?php foreach ($vehicle_types as $row): ?>
            <div class="category">
                <h3><?php echo htmlspecialchars($row['vehicle_category']); ?></h3>
                <p><?php echo htmlspecialchars($row['vehicle_count']); ?> vehicles</p>
                <p>Starting price: NZD $<?php echo htmlspecialchars($row['daily_hire_rate']); ?>/day, $<?php echo htmlspecialchars($row['monthly_lease_cost']); ?>/month</p>
            </div>
        <?php endforeach; ?>
    </section>

</body>
</html>
