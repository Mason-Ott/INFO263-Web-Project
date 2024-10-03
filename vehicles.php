<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles</title>
    <link rel="stylesheet" href="CSS_Files/vehicles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="JS_Handlers/vehicles_handler.js"></script>

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
    <!-- Header displaying number of vehicles based of filters -->
    <div class="row text-center">
        <div class="col">
            <h1>Vehicle Count: <span id="vehicle-count"></span></h1>
        </div>
    </div>

    <div class="row">
        <!-- Dropdown for Vehicle Category -->
        <div class="col-2">
            Vehicle Type
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedVehicleType">ALL</span> <!-- Set default -->
                </button>
                <ul class="dropdown-menu" id="vehicleMenu">
                    <li><button class="dropdown-item vehicle-item" type="button" data-category="ALL">ALL</button></li>
                    <!-- retrieve distinct vehicle categories from database-->
                    <?php
                    require_once 'db.php';
                    $categoriesQuery = "SELECT DISTINCT vehicle_category FROM vehicle";
                    $categoriesStmt = $pdo->query($categoriesQuery);
                    $vehicleCategories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($vehicleCategories as $vehicle_category): ?>
                        <li>
                            <button class="dropdown-item vehicle-item" type="button" data-category="<?= htmlspecialchars($vehicle_category['vehicle_category']); ?>">
                                <?= htmlspecialchars($vehicle_category['vehicle_category']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Date input for Commissioned date -->
        <div class="col-3">
            <label for="commissioned">Commissioned (From)</label>
            <input type="date" id="commissioned" class="form-control" name="commissioned" onchange="getVehicleData()">
        </div>

        <!-- Date input for Decommissioned date -->
        <div class="col-3">
            <label for="decommissioned">Decomissioned (To)</label>
            <input type="date" id="decommissioned" class="form-control" name="decommissioned" onchange="getVehicleData()">
        </div>

        <div class="col-3">
            <label for="rego">Rego:</label>
            <input type="text" id="rego" oninput="getVehicleData()" placeholder="Registeration">
        </div>
    </div>

    <div class="row text-center">
        <div class="col-4 ">
            <!-- Text Input for Minimum Odometer -->
            <label for="odometerMin">Minimum Odometer:</label>
            <input type="number" id="odometerMin" step="1000" min="0" max="70000" value="0" oninput="getVehicleData()" placeholder="Minimum Odometer">
        </div>

        <div class="col-4">
            <!-- Text Input for Maximum Odometer -->
            <label for="odometerMax">Maximum Odometer:</label>
            <input type="number" id="odometerMax" step="1000" min="0" max="70000" value="70000"  oninput="getVehicleData()" placeholder="Maximum Odometer">
        </div>

        <div class="col-4">
            <!-- Checkbox for requires maintenance -->
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="requiresMaintenance" oninput="getVehicleData()">
                <label class="form-check-label" for="requiresMaintenance">
                    Requires Maintenance
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- Dropdown for sort by options-->
            Sort by
            <div class="d-inline-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedSortBy" data-category="vehicle_rego">Rego</span> <!-- Set default -->
                    </button>
                    <ul class="dropdown-menu" id="sortMenu">
                        <li><button class="dropdown-item sort-item" type="button" data-category="vehicle_rego">Rego</button></li>
                        <li><button class="dropdown-item sort-item" type="button" data-category="odometer">Odometer</button></li>
                        <li><button class="dropdown-item sort-item" type="button" data-category="commissioned_date">Commission Date</button></li>
                        <li><button class="dropdown-item sort-item" type="button" data-category="decommissioned_date">Decommission Date</button></li>
                        <li><button class="dropdown-item sort-item" type="button" data-category="distance_since_maintenance">Distance since Maintenance</button></li>
                    </ul>
                </div>

                <!-- Sort Direction Icon Button -->
                <button id="sort-direction" class="btn btn-light ms-2">
                    <img id="sort-image" src="Resources/ascending-sort.png" alt="Sort Direction" style="width: 20px; height: 20px;">
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- Div for displaying pagination -->
            <div class="pagination"></div>
        </div>
    </div>

    <!-- Section for Vehicle data output -->
    <section class="vehicledata-section" id="data"></section>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>