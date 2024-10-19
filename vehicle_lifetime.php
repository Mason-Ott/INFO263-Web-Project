<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Lifetime</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="JS_Handlers/vehicle_lifetime_handler.js"></script>
    <link rel="stylesheet" href="CSS_Files/base.css">
    <link rel="stylesheet" href="CSS_Files/vehicle_lifetime.css">
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
                    <li><a class="active" href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
                    <li><a href="charts.php">Charts</a></li>
                    <li><a href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Vehicle Lifetime Histogram</h2>
        <!-- Div for Histogram chart -->
        <div class="chart-container">
            <div id="histogramChart"></div>
        </div>

        <!-- Div for pagination -->
        <div class="pagination"></div>

        <!-- Div for vehicle data -->
        <section class="data-section" id="data"></section>

    </div>
    <script>
        getHistogramData();
    </script>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</html>
