<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Lifetime</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="JS_Handlers/vehicle_lifetime_handler.js"></script>
    <link rel="stylesheet" href="CSS_Files/vehicle_lifetime.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="vehicles.php">Vehicles</a></li>
                <li><a href="trips.php">Trips</a></li>
                <li><a href="maintenance.php">Maintenance</a></li>
                <li><a href="relocations.php">Relocations</a></li>
                <li><a href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
                <li><a href="charts.php">Charts</a></li>
                <li><a href="admin_login.php">Database Admin Login</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Data Histogram</h2>
        <!-- Div for Histogram chart -->
        <div class="chart-container">
            <div id="histogramChart"></div>
        </div>

        <!-- Div for pagination -->
        <div class="pagination"></div>

        <!-- Div for vehicle data -->
        <section class="vehicledata-section" id="data"></section>

    </div>
    <script>
        getHistogramData();
    </script>
</body>
</html>
