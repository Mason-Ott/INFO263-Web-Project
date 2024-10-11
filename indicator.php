<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Dashboard</title>
    <link rel="stylesheet" href="CSS_Files/styles.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="JS_Handlers/charts.js"></script>


    <button id="goBackButton" style="display: none;" onclick="goBack()">Go Back to Home</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<!-- Quarterly Indicators Chart -->
<canvas id="myChart" width="400" height="200"></canvas>

<script>
    // $('#quarterlyButton').click(function() {
    $.ajax({
        url: 'PHP_Handlers/quarterly_handler.php',
        type: 'GET',
        data: { type: 'quarterly_indicators' },
        success: function(response) {
            var data = JSON.parse(response);
            showQuarterlyIndicatorsChart(data);
            document.getElementById('goBackButton').style.display = 'block';

        }
    });
    function goBack() {
        window.location.href = 'index.php';
    }
    // });
</script>
</body>
</html>