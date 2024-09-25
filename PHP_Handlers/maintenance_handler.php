<?php

header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Get the query parameters from the URL with validation
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;

$odometerMin = isset($_GET['OdoMin']) && is_numeric($_GET['OdoMin']) ? (int)$_GET['OdoMin'] : 0;
$odometerMax = isset($_GET['OdoMax']) && is_numeric($_GET['OdoMax']) ? (int)$_GET['OdoMax'] : 100000000;
$vehicleType = isset($_GET['vehicleType']) ? $_GET['vehicleType'] : 'ALL';
$location = isset($_GET['location']) ? $_GET['location'] : 'ANY';
$startDate = isset($_GET['start']) ? $_GET['start'] : null;
$endDate = isset($_GET['end']) ? $_GET['end'] : null;
$rego = isset($_GET['rego']) ? $_GET['rego'] : '';

// Base query
$query = 'SELECT 
                maintenance.maintenance_id, 
                start_date.date AS start_date,
                end_date.date AS end_date,
                maintenance.location,
                maintenance.mileage,
                maintenance.vehicle_rego,
                vehicle.vehicle_category 
          FROM maintenance 
          JOIN vehicle ON maintenance.vehicle_rego = vehicle.vehicle_rego
          LEFT JOIN sim_day_date AS start_date ON maintenance.start_day = start_date.sim_day
          LEFT JOIN sim_day_date AS end_date ON maintenance.end_day = end_date.sim_day
          WHERE mileage >= :odometerMin 
          AND mileage <= :odometerMax';

// Append filters
if ($vehicleType !== 'ALL') $query .= ' AND vehicle_category = :vehicleType';
if ($location !== 'ANY') $query .= ' AND location = :location';
if ($startDate) $query .= ' AND start_date.date >= :startDate';
if ($endDate) $query .= ' AND end_date.date <= :endDate';
if ($rego) $query .= ' AND maintenance.vehicle_rego LIKE :rego';

// Append Limit and Offset
$query .= ' LIMIT :limit OFFSET :offset';

// Prepare and bind parameters
$stmt = $pdo->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$stmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') $stmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($location !== 'ANY') $stmt->bindParam(':location', $location, PDO::PARAM_STR);
if ($startDate) $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
if ($endDate) $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
if ($rego) $stmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

// Execute and fetch results
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total count query
$countQuery = 'SELECT COUNT(*) as count 
               FROM maintenance 
               JOIN vehicle ON maintenance.vehicle_rego = vehicle.vehicle_rego
               LEFT JOIN sim_day_date AS start_date ON maintenance.start_day = start_date.sim_day
               LEFT JOIN sim_day_date AS end_date ON maintenance.end_day = end_date.sim_day
               WHERE mileage >= :odometerMin 
               AND mileage <= :odometerMax';

if ($vehicleType !== 'ALL') $countQuery .= ' AND vehicle_category = :vehicleType';
if ($location !== 'ANY') $countQuery .= ' AND location = :location';
if ($startDate) $countQuery .= ' AND start_date.date >= :startDate';
if ($endDate) $countQuery .= ' AND end_date.date <= :endDate';
if ($rego) $countQuery .= ' AND maintenance.vehicle_rego LIKE :rego';

$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':odometerMin', $odometerMin, PDO::PARAM_INT);
$countStmt->bindParam(':odometerMax', $odometerMax, PDO::PARAM_INT);

if ($vehicleType !== 'ALL') $countStmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($location !== 'ANY') $countStmt->bindParam(':location', $location, PDO::PARAM_STR);
if ($startDate) $countStmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
if ($endDate) $countStmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
if ($rego) $countStmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

$countStmt->execute();
$totalTrips = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
$totalPages = ceil($totalTrips / $limit);

echo json_encode([
    'data' => $results,
    'count' => $totalTrips,
    'totalPages' => $totalPages,
    'currentPage' => floor($offset / $limit) + 1,
]);
?>
