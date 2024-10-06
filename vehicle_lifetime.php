<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Histogram</title>
    <!-- Include Plotly.js CDN -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <!-- Include your custom JavaScript file for AJAX -->
    <script src="JS_Handlers/vehicle_lifetime.js"></script>
    <link rel="stylesheet" href="CSS_Files/vehicle_lifetime.css">
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
            </ul>
        </nav>
    </header>
    <h2>Data Histogram</h2>
    <!-- Div for Plotly chart -->
    <div id="histogramChart" style="width:100%;height:500px;"></div>

    <div class="pagination"></div>

    <section class="vehicledata-section" id="data"></section>

    <script>
        // Call your JS function to load and render the chart
        getHistogramData();
    </script>
</body>
</html>
