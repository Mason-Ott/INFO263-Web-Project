<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relocations</title>
    <link rel="stylesheet" href="CSS_Files/relocations.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="JS_Handlers/relocations_handler.js"></script>

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

<div class="container">
    <!-- Header displaying number of Relocations based of filters -->
    <div class="row text-center">
        <div class="col">
            <h1>Relocation Count: <span id="relocations-count"></span></h1>
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
            <label for="startDate">Relocation Start Date (From)</label>
            <input type="date" id="startDate" class="form-control" name="startDate" onchange="getRelocationData()">
        </div>

        <!-- Date input for End Date -->
        <div class="col-3">
            <label for="endDate">Relocation End Date (To)</label>
            <input type="date" id="endDate" class="form-control" name="endDate" onchange="getRelocationData()">
        </div>

        <!-- Dropdown for Origin -->
        <div class="col-2">
            Origin
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedOrigin">ANY</span> <!-- Set default text here -->
                </button>
                <ul class="dropdown-menu" id="originMenu">
                    <li><button class="dropdown-item origin-item" type="button" data-category="ANY">ANY</button></li>
                    <!-- retrieve distinct origins from database-->
                    <?php
                    $originQuery = $pdo->query("SELECT DISTINCT origin FROM relocation_whole");
                    $originCategories = $originQuery->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($originCategories as $origin): ?>
                        <li>
                            <button class="dropdown-item origin-item" type="button" data-category="<?= htmlspecialchars($origin['origin']); ?>">
                                <?= htmlspecialchars($origin['origin']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Dropdown for Destination -->
        <div class="col-2">
            Destination
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedDestination">ANY</span> <!-- Set default -->
                </button>
                <ul class="dropdown-menu" id="destinationMenu">
                    <li><button class="dropdown-item destination-item" type="button" data-category="ANY">ANY</button></li>
                    <!-- retrieve distinct destinations from database-->
                    <?php
                    $destinationQuery = $pdo->query("SELECT DISTINCT destination FROM relocation_whole");
                    $destinationCategories = $destinationQuery->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($destinationCategories as $destination): ?>
                        <li>
                            <button class="dropdown-item destination-item" type="button" data-category="<?= htmlspecialchars($destination['destination']); ?>">
                                <?= htmlspecialchars($destination['destination']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-4 ">
            <!-- Text Input for Minimum Distance -->
            <label for="distanceMin">Minimum Distance:</label>
            <input type="number" id="distanceMin" step="100" min="0" max="1500" value="0" oninput="getRelocationData()" placeholder="Minimum Distance">
        </div>

        <div class="col-4">
            <!-- Text Input for Maximum Distance -->
            <label for="distanceMax">Maximum Distance:</label>
            <input type="number" id="distanceMax" step="100" min="0" max="1500" value="1500" oninput="getRelocationData()" placeholder="Maximum Distance">
        </div>

        <div class="col-4">
            <div class="col-3">
                <label for="rego">Rego:</label>
                <input type="text" id="rego" oninput="getRelocationData()" placeholder="Registeration">
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
                        <li><button class="dropdown-item sort-item" type="button" data-category="distance">Distance</button></li>
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

    <!-- Section for Relocation data output -->
    <section class="relocationdata-section" id="data"></section>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>