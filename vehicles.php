<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles</title>
    <link rel="stylesheet" href="vehicles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="vehicles.js"></script>
</head>
<body>
    <?php

    if (isset($_GET['id']) and !empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        //Filters

        //pagination
        if (isset($_GET['offset']) and !empty($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        $limit = 25;

        // Filtering
        $rego = isset($_GET['rego']) ? $_GET['rego'] : '';
        $commissioned = isset($_GET['commissioned']) ? $_GET['commissioned'] : '';
        $decommissioned = isset($_GET['decomissioned']) ? $_GET['decomissioned'] : '';
        $omin = isset($_GET['omin']) ? $_GET['omin'] : 0;
        $omax = isset($_GET['omax']) ? $_GET['omax'] : 70000;
        $vehicleCategories = isset($_GET['category']) ? (array) $_GET['category'] : [];

        //$count = getVehicleCount($rego, $commissioned, $decommissioned, $omin, $omax, $vehicleCategories);
    }



    ?>
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

    <h1>Vehicle Count: <span id="vehicle-count"></span></h1>

    <span id="offset" style="display:none;">0</span>
    <span id="limit" style="display:none;">25</span>

    <label for="rego-input">Registration number:</label>
    <input id="rego-input" type="text" value="<?php echo $rego; ?>">

    <label for="commissioned-date">Commissioned since:</label>
    <input id="commissioned-date" type="date" value="<?php echo $commissioned; ?>">

    <label for="decommissioned-date">Decommissioned since:</label>
    <input id="decommissioned-date" type="date" value="<?php echo $decommissioned; ?>">

    <label for="fromSlider">Minimum Odometer:</label>
    <input id="fromSlider" type="range" value="<?php echo $omin; ?>" min="0" max="70000" step="100" oninput="minRangeValue.innerText = this.value">
    <p id="minRangeValue">0</p>
    <label for="toSlider">Maximum Odometer:</label>
    <input id="toSlider" width="" type="range" value="<?php echo $omax; ?>" min="0" max="70000" step="100" oninput="maxRangeValue.innerText = this.value">
    <p id="maxRangeValue">70000</p>

    <select id="vehicle-categories" class="form-select">
        <?php
        var_dump($vehicleCategories);
        foreach ($vehicleCategories as $vehicle_category): ?>
            <option value="<?= htmlspecialchars($vehicle_category['vehicle_category']); ?>">
                <?= htmlspecialchars($vehicle_category['vehicle_category']); ?>
            </option>
        <?php endforeach; ?>
    </select>


    <section class="vehicledata-section" id="data"> </section>


</body>
</html>