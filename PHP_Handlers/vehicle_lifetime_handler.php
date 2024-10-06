<?php
header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Determine if vehicle data query or lifetime data for chart
if (isset($_GET['value'])) {

    // Get offset, limit and bin value
    $value = (int)$_GET['value'];
    $offset = filter_var($_GET['offset'] ?? 0, FILTER_VALIDATE_INT);
    $limit = filter_var($_GET['limit'] ?? 25, FILTER_VALIDATE_INT);

    // Find min and max query values from bin value
    $valueMin = $value - 50;
    $valueMax = $value + 49;

    // Query for retrieving vehicle data to display
    $query = "SELECT vehicle_rego, service_days, vehicle_category, no_of_trips
    FROM trip_count
        WHERE service_days BETWEEN :valueMin AND :valueMax
          LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);

    // Bind parameters to query
    $stmt->bindParam(':valueMin', $valueMin, PDO::PARAM_INT);
    $stmt->bindParam(':valueMax', $valueMax, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch Data
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query for retrieving count of data for pagination
    $countQuery = "SELECT COUNT(DISTINCT vehicle_rego) as count
                   FROM trip_count
                   WHERE service_days BETWEEN :valueMin AND :valueMax";

    $countStmt = $pdo->prepare($countQuery);

    // Bind parameters to query
    $countStmt->bindParam(':valueMin', $valueMin, PDO::PARAM_INT);
    $countStmt->bindParam(':valueMax', $valueMax, PDO::PARAM_INT);
    $countStmt->execute();

    // Fetch data and calculate current page and total pages
    $totalVehicles = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
    $totalPages = ceil($totalVehicles / $limit);
    $currentPage = $page = $offset > 0 ? floor($offset / $limit) + 1 : 1;

    // Return retrieved data
    echo json_encode([
        'data' => $vehicles,
        'count' => $totalVehicles,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ]);
} else {
    // query for retrieving service days for chart
    $query = "SELECT (vehicle.decommissioned - vehicle.commissioned) AS service_days
FROM vehicle
WHERE commissioned != 0 AND vehicle.decommissioned IS NOT NULL;";

    $result = $pdo->query($query);

// Array to hold the service days
    $service_days = [];

// Populate the array with service days
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if ($row != 0) $service_days[] = (int)$row['service_days'];
    }

// Return the data as array
    echo json_encode($service_days);
}
?>
