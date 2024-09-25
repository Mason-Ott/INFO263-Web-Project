<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips</title>
    <link rel="stylesheet" href="CSS_Files/vehicle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="JS_Handlers/vehicle_handler.js"></script>

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

<div class="container">
    <div class="row-4 text-center">
        <h1><section id="regoTitle"></section></h1>
    </div>

    <div class="row-4 text-center">
        <h2>Vehicle Data:</h2>
        <section id="vehicleData"></section>
    </div>

    <div class="row-4 text-center">
        <h2>Trips:</h2>
        <section class="data-section" id="tripData"></section>
    </div>

    <div class="row-4 text-center">
        <h2>Maintenance:</h2>
        <section class="data-section" id="maintenanceData"></section>
    </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>