<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Dashboard</title>
    <link rel="stylesheet" href="CSS_Files/styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="JS_Handlers/charts.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

<!-- Quarterly Indicators Chart -->
<button id="quarterlyButton">Show Quarterly Indicators Graph</button>
<canvas id="myChart" width="400" height="200"></canvas>

<script>
    // Handle Quarterly Indicators button click
    $('#quarterlyButton').click(function() {
        $.ajax({
            url: 'PHP_Handlers/data_handler.php',
            type: 'GET',
            data: { type: 'quarterly_indicators' },
            success: function(response) {
                var data = JSON.parse(response);
                showQuarterlyIndicatorsChart(data);
            }
        });
    });
</script>
</body>
</html>
