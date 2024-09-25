<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips</title>
    <link rel="stylesheet" href="trips.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="trip.js"></script>
    <script src="vehicle_handler.js"></script>

</head>
<body>

    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="vehicles.php">Vehicles</a></li>
                <li><a href="trips_page.php">Trips</a></li>
                <li><a href="maintenance.php">Maintenance</a></li>
                <li><a href="relocations.php">Relocations</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="row text-center">
            <div class="col">
                <h1>Trip Count: <span id="trip-count"></span></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Currency
                    </button>
                    <ul class="dropdown-menu">
                        <?php foreach ($vehicleCategories as $vehicle_category): ?>
                            <li><button class="dropdown-item" type="button">
                                <?= htmlspecialchars($vehicle_category['vehicle_category']); ?>
                            </button></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="col-3">
                b
            </div>

            <div class="col-2">
                c
            </div>

            <div class="col-3">
                d
            </div>

            <div class="col-2">
                e
            </div>
        </div>

        <!-- Text Input for Search Term -->
        <label for="searchInput">Search:</label>
        <input type="text" id="distanceMin" oninput="getTripData()" placeholder="Minimum Distance">

        <!-- Text Input for another filter -->
        <label for="anotherInput">Another Filter:</label>
        <input type="text" id="distanceMax" oninput="getTripData()" placeholder="Maximum Distance">



        <div class="pagination"></div>

        <section class="tripdata-section" id="data"></section>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>