<?php
header('Content-Type: application/json');

require_once 'db.php'; // Include database connection

// Set data values from GET parameters
$type = isset($_GET['type']) ? $_GET['type'] : null;
$rego = isset($_GET['rego']) ? $_GET['rego'] : '';
$commissioned = isset($_GET['commissioned']) ? $_GET['commissioned'] : '';
$decommissioned = isset($_GET['decommissioned']) ? $_GET['decommissioned'] : '';
$omin = isset($_GET['omin']) ? (int)$_GET['omin'] : 0;
$omax = isset($_GET['omax']) ? (int)$_GET['omax'] : 70000;
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Set default limit and offset for vehicleData
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

if ($type === 'vehicleData') {
    // Query for vehicle data
    $query = "SELECT vehicle.vehicle_rego, vehicle.vehicle_category, vehicle.odometer, commissioned_date.date AS commissioned, decommissioned_date.date AS decommissioned
                FROM vehicle 
                LEFT JOIN sim_day_date commissioned_date ON vehicle.commissioned = commissioned_date.sim_day 
                LEFT JOIN sim_day_date decommissioned_date ON vehicle.decommissioned = decommissioned_date.sim_day 
                WHERE odometer >= :omin AND odometer <= :omax";
} else if ($type === 'vehicleCount') {
    // Query for vehicle count
    $query = "SELECT COUNT(*) as vehicle_count FROM vehicle WHERE odometer >= :omin AND odometer <= :omax";
}

// Add necessary filters
if ($rego) {
    $query .= " AND vehicle_rego = :rego";
}
if ($commissioned) {
    $query .= " AND commissioned = :commissioned";
}
if ($decommissioned) {
    $query .= " AND decommissioned = :decommissioned";
}
if ($category) {
    $query .= " AND vehicle_category = :category";
}

// If fetching vehicle data, add LIMIT and OFFSET for pagination
if ($type === 'vehicleData') {
    $query .= " LIMIT :limit OFFSET :offset";
}

// Prepare the SQL statement
$stmt = $pdo->prepare($query);

// Bind common parameters
$stmt->bindParam(':omin', $omin, PDO::PARAM_INT);
$stmt->bindParam(':omax', $omax, PDO::PARAM_INT);
if ($rego) $stmt->bindParam(':rego', $rego);
if ($commissioned) $stmt->bindParam(':commissioned', $commissioned);
if ($decommissioned) $stmt->bindParam(':decommissioned', $decommissioned);
if ($category) $stmt->bindParam(':category', $category);

// Bind LIMIT and OFFSET for vehicleData request
if ($type === 'vehicleData') {
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
}

// Execute the query
$stmt->execute();

// Fetch the result based on the type of request
if ($type === 'vehicleData') {
    // Fetch all vehicle data
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else if ($type === 'vehicleCount') {
    // Fetch the vehicle count
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Return the result as a JSON object
echo json_encode($result);
?>
