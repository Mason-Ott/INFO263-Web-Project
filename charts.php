<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts</title>
    <link rel="stylesheet" href="CSS_Files/charts.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="CSS_Files/base.css">
    <script src="JS_Handlers/charts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                    <li><a class="active" href="charts.php">Charts</a></li>
                    <li><a href="admin_login.php">Database Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="row text-center">
        <div class="col">
            <h1>Charts:</h1>
        </div>
    </div>

    <div class="chart-container1">
        <!-- Quarterly Indicators Chart -->
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>

    <div class="chart-container2">
        <!-- Trips Chart -->
        <canvas id="tripsChart" width="400" height="300" ></canvas>
    </div>

    <script>
        $.ajax({
            url: 'PHP_Handlers/quarterly_handler.php',
            type: 'GET',
            data: { type: 'quarterly_indicators' },
            success: function(response) {
                var data = JSON.parse(response);
                showQuarterlyIndicatorsChart(data);
            }
        });

        $.ajax({
            url: 'PHP_Handlers/incoming_outgoing_handler.php',
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);
                showTripsChart(data);
            }
        });

    </script>
</body>
</html>