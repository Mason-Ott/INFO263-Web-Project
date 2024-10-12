<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">Vehicle</title>
    <link rel="stylesheet" href="CSS_Files/vehicle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="JS_Handlers/vehicle_handler.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
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
        <!-- Vehicle Rego Title-->
        <div class="row-4 text-center">
            <h1><section id="regoHeader"></section></h1>
        </div>

        <!-- Vehicle Data output -->
        <div class="row-3 text-center">
            <h2>Vehicle Data:</h2>
            <section id="vehicleData"></section>
        </div>

        <!-- Trips Data output -->
        <div class="row-3 text-center">
            <h2 id="tripHeader">Trips:</h2>
            <div id="pagination1"></div>
            <section class="data-section" id="tripData"></section>
        </div>

        <!-- Relocations Data output -->
        <div class="row-3 text-center">
            <h2 id="relocationHeader">Relocations:</h2>
            <div id="pagination2"></div>
            <section class="data-section" id="relocationData"></section>
        </div>

        <!-- Maintenance Data output -->
        <div class="row-3 text-center">
            <h2 id="maintenanceHeader">Maintenance:</h2>
            <div id="pagination3"></div>
            <section class="data-section" id="maintenanceData"></section>
        </div>
    </div>

    <!-- Odometer Chart -->
    <div class="chart-container">
        <canvas id="odometerChart"></canvas>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>