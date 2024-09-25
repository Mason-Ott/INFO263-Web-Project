<?php
header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Get the query parameters from the URL
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;

$odometerMin = isset($_GET['OdoMin']) && $_GET['OdoMin'] !== '' ? (int)$_GET['OdoMin'] : 0;
$odometerMax = isset($_GET['OdoMax']) && $_GET['OdoMax'] !== '' ? (int)$_GET['OdoMax'] : 10000000;
$vehicleType = isset($_GET['vehicleType']) ? $_GET['vehicleType'] : 'ALL';
$commissioned = isset($_GET['com']) ? $_GET['com'] : null; // Set to null if not provided
$decommissioned = isset($_GET['decom']) ? $_GET['decom'] : null; // Set to null if not provided
$rego = isset($_GET['rego']) ? $_GET['rego'] : '';

// Prepare SQL query
$query = 'SELECT vehicle.vehicle_rego, vehicle.vehicle_category, vehicle.odometer, 
           commissioned_date.date AS commissioned_date, 
           decommissioned_date.date AS decommissioned_date
    FROM vehicle
    LEFT JOIN sim_day_date AS commissioned_date 
        ON vehicle.commissioned = commissioned_date.sim_day
    LEFT JOIN sim_day_date AS decommissioned_date 
        ON vehicle.decommissioned = decommissioned_date.sim_day
    WHERE odometer >= :odometerMin AND odometer <= :odometerMax';

if ($vehicleType !== 'ALL') $query .= ' AND vehicle_category = :vehicleType';
if ($commissioned !== null) $query .= ' AND commissioned_date.date >= :commissioned';
if ($decommissioned !== null) $query .= ' AND decommissioned_date.date <= :decommissioned';
if ($rego !== '') $query .= ' AND vehicle_rego LIKE :rego';

// Append LIMIT and OFFSET
$query .= ' LIMIT :limit OFFSET :offset';

// Prepare statement
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$stmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') $stmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($commissioned !== null) $stmt->bindParam(':commissioned', $commissioned, PDO::PARAM_STR);
if ($decommissioned !== null) $stmt->bindParam(':decommissioned', $decommissioned, PDO::PARAM_STR);
if ($rego !== '') $stmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

// Execute and fetch data
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Count total vehicles for pagination
$countQuery = 'SELECT COUNT(*) as count 
    FROM vehicle 
    LEFT JOIN sim_day_date AS commissioned_date 
        ON vehicle.commissioned = commissioned_date.sim_day
    LEFT JOIN sim_day_date AS decommissioned_date 
        ON vehicle.decommissioned = decommissioned_date.sim_day
    WHERE odometer >= :odometerMin AND odometer <= :odometerMax';

if ($vehicleType !== 'ALL') $countQuery .= ' AND vehicle_category = :vehicleType';
if ($commissioned !== null) $countQuery .= ' AND commissioned_date.date >= :commissioned';
if ($decommissioned !== null) $countQuery .= ' AND decommissioned_date.date <= :decommissioned';
if ($rego !== '') $countQuery .= ' AND vehicle_rego LIKE :rego';

// Prepare and bind parameters for count query
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$countStmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') $countStmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($commissioned !== null) $countStmt->bindParam(':commissioned', $commissioned, PDO::PARAM_STR);
if ($decommissioned !== null) $countStmt->bindParam(':decommissioned', $decommissioned, PDO::PARAM_STR);
if ($rego !== '') $countStmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

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
