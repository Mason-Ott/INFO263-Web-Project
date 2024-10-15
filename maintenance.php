<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <link rel="stylesheet" href="CSS_Files/maintenance.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS_Files/base.css">
    <script rel="stylesheet" src="JS_Handlers/maintenance_handler.js"></script>
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
                    <li><a class="active" href="maintenance.php">Maintenance</a></li>
                    <li><a href="relocations.php">Relocations</a></li>
                    <li><a href="vehicle_lifetime.php">Vehicle Lifetime</a></li>
                    <li><a href="charts.php">Charts</a></li>
                    <li><a href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- Header displaying number of Maintenances based of filters -->
        <div class="row text-center">
            <div class="col">
                <h1>Maintenance Count: <span id="maintenance-count"></span></h1>
            </div>
        </div>

        <div class="row">
            <!-- Dropdown for Vehicle Category -->
            <div class="col-2">
                Vehicle Type
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedVehicleType">ALL</span> <!-- Set default text here -->
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

            <!-- Date input for Start Date -->
            <div class="col-3">
                <label for="startDate">Maintenance Start Date (From)</label>
                <input type="date" id="startDate" class="form-control" name="startDate" onchange="getMaintenanceData()">
            </div>

            <!-- Date input for End Date -->
            <div class="col-3">
                <label for="endDate">Maintenance End Date (To)</label>
                <input type="date" id="endDate" class="form-control" name="endDate" onchange="getMaintenanceData()">
            </div>

            <!-- Dropdown for Location -->
            <div class="col-2">
                Location
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedLocation">ANY</span> <!-- Set default text here -->
                    </button>
                    <ul class="dropdown-menu" id="locationMenu">
                        <li><button class="dropdown-item location-item" type="button" data-category="ANY">ANY</button></li>
                        <!-- retrieve distinct locations from database-->
                        <?php
                        $locationQuery = $pdo->query("SELECT DISTINCT location FROM maintenance");
                        $locationCategories = $locationQuery->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($locationCategories as $location): ?>
                            <li>
                                <button class="dropdown-item location-item" type="button" data-category="<?= htmlspecialchars($location['location']); ?>">
                                    <?= htmlspecialchars($location['location']); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-4">
                <!-- Text Input for Minimum Odometer -->
                <label for="odometerMin">Minimum Odometer:</label>
                <input type="number" id="odometerMin" step="1000" min="0" max="70000" value="0" oninput="getMaintenanceData()" placeholder="Minimum Odometer">
            </div>

            <div class="col-4">
                <!-- Text Input for Maximum Odometer -->
                <label for="odometerMax">Maximum Odometer:</label>
                <input type="number" id="odometerMax" step="1000" min="0" max="70000" value="70000" oninput="getMaintenanceData()" placeholder="Maximum Odometer">
            </div>

            <div class="col-4">
                <!-- Text Input for Registration -->
                <div class="col-3">
                    <label for="rego">Rego:</label>
                    <input type="text" id="rego" oninput="getMaintenanceData()" placeholder="Registration">
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
                            <li><button class="dropdown-item sort-item" type="button" data-category="mileage">Odometer</button></li>
                            <li><button class="dropdown-item sort-item" type="button" data-category="start_date">Start Date</button></li>
                            <li><button class="dropdown-item sort-item" type="button" data-category="end_date">End Date</button></li>
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

        <!-- Section for Maintenance data output -->
        <section class="data-section" id="data"></section>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>