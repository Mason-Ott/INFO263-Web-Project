<?php
include_once __DIR__ . '/../db.php';
if (isset($_GET['value'])) {
    $value = (int)$_GET['value']; // Ensure $value is properly cast
    $offset = filter_var($_GET['offset'] ?? 0, FILTER_VALIDATE_INT); // Validate as integer
    $limit = filter_var($_GET['limit'] ?? 25, FILTER_VALIDATE_INT);  // Validate as integer
    $limit = $limit > 0 ? $limit : 25; // Default to 25 if invalid limit

    $valueMin = $value - 50;
    $valueMax = $value + 49;

    $query = "SELECT vehicle_rego, service_days, vehicle_category, no_of_trips
    FROM trip_count
        WHERE service_days BETWEEN :valueMin AND :valueMax
          LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':valueMin', $valueMin, PDO::PARAM_INT);
    $stmt->bindParam(':valueMax', $valueMax, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $countQuery = "SELECT COUNT(DISTINCT vehicle_rego) as count
                   FROM trip_count
                   WHERE service_days BETWEEN :valueMin AND :valueMax";

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->bindParam(':valueMin', $valueMin, PDO::PARAM_INT);
    $countStmt->bindParam(':valueMax', $valueMax, PDO::PARAM_INT);
    $countStmt->execute();

    $totalVehicles = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
    $totalPages = ceil($totalVehicles / $limit);
    $currentPage = $page = $offset > 0 ? floor($offset / $limit) + 1 : 1;

    echo json_encode([
        'data' => $vehicles,
        'count' => $totalVehicles,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ]);
} else {
    // Fetch service days from the database
    $query = "SELECT (vehicle.decommissioned - vehicle.commissioned) AS service_days
FROM vehicle
WHERE commissioned != 0 AND vehicle.decommissioned IS NOT NULL;";

    $result = $pdo->query($query);

// Prepare an array to hold the service days
    $service_days = [];

// Populate the array with service days
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if ($row != 0) $service_days[] = (int)$row['service_days'];
    }

// Return the data as a JSON array
    echo json_encode($service_days);
}
?>
