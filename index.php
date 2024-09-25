<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rental Service</title>
    <link rel="stylesheet" href="CSS_Files/index.css">
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
        </ul>
    </nav>
</header>

<?php
require_once 'PHP_Handlers/SimulationSummary.php';
?>


<h2>Summary</h2>
<section class="summary-section">
    <div class="summary-category">
        <h3>Trips</h3>
        <p>Completed:
            <span>
                <?php
                echo $trip_completed->fetchColumn();
                ?>
            </span>
        </p>
        <p>Upgrade:
            <span>
                <?php
                echo $trip_upgraded->fetchColumn();
                ?>
            </span>
        </p>
    </div>

    <div class="summary-category">
        <h3>Refused</h3>
        <p>Bookings:
            <span>
                <?php
                    echo $refused_bookings->fetchColumn();
                ?>
            </span>
        </p>
        <p>Walk-ins:
            <span>
                <?php

                echo $refused_walk_ins->fetchColumn();
                ?>
            </span>
        </p>
    </div>

    <div class="summary-category">
        <h3>Vehicles</h3>
        <p>Relocated:
            <span>
                <?php

                echo $vehicles_relocated->fetchColumn();
                ?>
            </span>
        </p>
        <p>Serviced:
            <span>
                <?php
                echo $vehicles_serviced->fetchColumn();
                ?>
            </span>
        </p>
    </div>
</section>

<h2>Car Types</h2>

<section class="cartype-section">
    <?php
    foreach ($vehicle_types as $row):
    ?>
        <div class='cartype-category'>
            <?php
                echo htmlspecialchars($row['vehicle_category']) . " - " . htmlspecialchars($row['vehicle_count']) . " vehicles <br>";
                echo "Starting price: NZD $" . htmlspecialchars($row['daily_hire_rate']) . "/day, $" . htmlspecialchars($row['monthly_lease_cost']) . "/month";
            ?>
        </div>
    <?php endforeach; ?>
    </section>;

</body>
</html>
