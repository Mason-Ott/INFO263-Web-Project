
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips by Destination</title>
    <link rel="stylesheet" href="CSS_Files/trips_by_destination.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <button id="goBackButton2" style="display: none;" onclick="goBack()">Go Back to Home</button>

    <script src="JS_Handlers/trips_chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <canvas id="tripsChart" width="400" height="200"></canvas>


    <script>
    $.ajax({
                url: 'PHP_Handlers/incoming_outgoing_handler.php',
                type: 'GET',
                success: function(response) {
                    var data = JSON.parse(response);
                    showTripsChart(data);

                    document.getElementById('goBackButton2').style.display = 'block';
    }
            });


        function goBack() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
