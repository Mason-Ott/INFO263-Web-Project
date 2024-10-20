<?php
header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Get the query parameters from the URL
$offset = filter_var($_GET['offset'] ?? 0, FILTER_VALIDATE_INT);
$limit = filter_var($_GET['limit'] ?? 25, FILTER_VALIDATE_INT);
$odometerMin = filter_var($_GET['OdoMin'] ?? 0, FILTER_VALIDATE_INT);
$odometerMax = filter_var($_GET['OdoMax'] ?? 10000000, FILTER_VALIDATE_INT);
$vehicleType = $_GET['vehicleType'] ?? 'ALL';
$commissioned = $_GET['com'] ?? null;
$decommissioned = $_GET['decom'] ?? null;
$rego = $_GET['rego'] ?? '';
$requiresMaintenance = $_GET['requires'] ?? '';
$sortBy = $_GET['sort'] ?? 'vehicle_rego';
$sortDirection = $_GET['dir'] ?? 'asc';

// Prepare SQL query
$query = 'SELECT vehicle.vehicle_rego, vehicle.vehicle_category, vehicle.odometer, 
                 commissioned_date.date AS commissioned_date, 
                 decommissioned_date.date AS decommissioned_date,
                 (vehicle.odometer - recent_maintenance.mileage) AS distance_since_maintenance
          FROM vehicle
          LEFT JOIN sim_day_date AS commissioned_date 
              ON vehicle.commissioned = commissioned_date.sim_day
          LEFT JOIN sim_day_date AS decommissioned_date 
              ON vehicle.decommissioned = decommissioned_date.sim_day
          LEFT JOIN (
              SELECT vehicle_rego, MAX(mileage) AS mileage
              FROM maintenance
              GROUP BY vehicle_rego
          ) AS recent_maintenance ON vehicle.vehicle_rego = recent_maintenance.vehicle_rego
          WHERE vehicle.odometer >= :odometerMin AND vehicle.odometer <= :odometerMax';


// Add conditional filtering for vehicle type
if ($vehicleType !== 'ALL') {
    $query .= ' AND vehicle_category = :vehicleType';
}

// Add filtering for commissioned date if provided
if ($commissioned !== null) {
    $query .= ' AND commissioned_date.date >= :commissioned';
}

// Add filtering for decommissioned date if provided
if ($decommissioned !== null) {
    $query .= ' AND decommissioned_date.date <= :decommissioned';
}

// Add filtering for registration number
if ($rego !== '') {
    $query .= ' AND vehicle.vehicle_rego LIKE :rego';
}

// Add maintenance check if the checkbox is selected
if ($requiresMaintenance == 'true') {
    $query .= ' AND (vehicle.odometer - recent_maintenance.mileage) >= 20000';
}

// Add sort by and sort direction
if (in_array($sortBy, ['commissioned_date', 'decommissioned_date', 'distance_since_maintenance'])) {
    $query .= ' ORDER BY ' . $sortBy . ' ' . $sortDirection;
} else {
    $query .= ' ORDER BY vehicle.' . $sortBy . ' ' . $sortDirection;
}

// Append LIMIT and OFFSET
$query .= ' LIMIT :limit OFFSET :offset';

// Prepare statement
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$stmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') {
    $stmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
}
if ($commissioned !== null) {
    $stmt->bindParam(':commissioned', $commissioned, PDO::PARAM_STR);
}
if ($decommissioned !== null) {
    $stmt->bindParam(':decommissioned', $decommissioned, PDO::PARAM_STR);
}
if ($rego !== '') {
    $stmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);
}

// Execute and fetch data
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total vehicles for pagination
$countQuery = 'SELECT COUNT(distinct vehicle.vehicle_rego) as count 
               FROM vehicle
               LEFT JOIN sim_day_date AS commissioned_date 
                   ON vehicle.commissioned = commissioned_date.sim_day
               LEFT JOIN sim_day_date AS decommissioned_date 
                   ON vehicle.decommissioned = decommissioned_date.sim_day
               LEFT JOIN (
                 SELECT vehicle_rego, MAX(mileage) AS mileage
                 FROM maintenance
                 GROUP BY vehicle_rego
               ) AS recent_maintenance ON vehicle.vehicle_rego = recent_maintenance.vehicle_rego
               WHERE vehicle.odometer >= :odometerMin AND vehicle.odometer <= :odometerMax';

if ($vehicleType !== 'ALL') $countQuery .= ' AND vehicle.vehicle_category = :vehicleType';
if ($commissioned !== null) $countQuery .= ' AND commissioned_date.date >= :commissioned';
if ($decommissioned !== null) $countQuery .= ' AND decommissioned_date.date <= :decommissioned';
if ($rego !== '') $countQuery .= ' AND vehicle.vehicle_rego LIKE :rego';
if ($requiresMaintenance === 'true') {
    $countQuery .= ' AND (vehicle.odometer - recent_maintenance.mileage) >= 20000';
}


// Prepare and bind parameters for count query
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$countStmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') {
    $countStmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
}
if ($commissioned !== null) {
    $countStmt->bindParam(':commissioned', $commissioned, PDO::PARAM_STR);
}
if ($decommissioned !== null) {
    $countStmt->bindParam(':decommissioned', $decommissioned, PDO::PARAM_STR);
}
if ($rego !== '') {
    $countStmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);
}

// Execute and fetch the total count
$countStmt->execute();
$totalVehicles = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
$totalPages = ceil($totalVehicles / $limit);

// Return data as JSON
echo json_encode([
    'data' => $results,
    'count' => $totalVehicles,
    'totalPages' => $totalPages,
    'currentPage' => floor($offset / $limit) + 1,
]);
