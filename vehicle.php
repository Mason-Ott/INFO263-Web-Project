<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css_files/base.css">
    <script rel="stylesheet" src="js_handlers/vehicle_handler.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
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
                    <li><a href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Vehicle Rego Title-->
    <div class="row-4 text-center">
        <h1><section id="regoHeader"></section></h1>
        <section id="vehicleData"></section>
        <br>
    </div>

    <!-- Trips Data output -->
    <div class="row-3 text-center">
        <h2 id="tripHeader">Trips:</h2>
        <div class="pagination" id="pagination1"></div>
        <section class="data-section" id="tripData"></section>
    </div>

    <!-- Relocations Data output -->
    <div class="row-3 text-center">
        <h2 id="relocationHeader">Relocations:</h2>
        <div class="pagination" id="pagination2"></div>
        <section class="data-section" id="relocationData"></section>
    </div>

    <!-- Maintenance Data output -->
    <div class="row-3 text-center">
        <h2 id="maintenanceHeader">Maintenance:</h2>
        <div class="pagination" id="pagination3"></div>
        <section class="data-section" id="maintenanceData"></section>
    </div>


    <!-- Odometer Chart -->
    <div class="chart-container">
        <canvas id="odometerChart"></canvas>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>