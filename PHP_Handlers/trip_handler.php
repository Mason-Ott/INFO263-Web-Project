<?php

header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Get the query parameters from the URL
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;

$distanceMin = isset($_GET['DistMin']) && $_GET['DistMin'] !== '' ? $_GET['DistMin'] : '0';
$distanceMax = isset($_GET['DistMax']) && $_GET['DistMax'] !== '' ? $_GET['DistMax'] : '100000000';
$vehicleType = isset($_GET['vehicleType']) ? $_GET['vehicleType'] : 'ALL';
$origin = isset($_GET['origin']) ? $_GET['origin'] : 'ANY';
$destination = isset($_GET['destination']) ? $_GET['destination'] : 'ANY';
$startDate = isset($_GET['start']) ? $_GET['start'] : '';
$endDate = isset($_GET['end']) ? $_GET['end'] : '';
$rego = isset($_GET['rego']) ? $_GET['rego'] : '';

$query = 'SELECT * 
    FROM trip_whole 
    WHERE distance >= :distanceMin 
        AND distance <= :distanceMax';


// Append the filters to query if they are not the default value
if ($vehicleType !== 'ALL') $query .= ' AND vehicle_category = :vehicleType';
if ($origin !== 'ANY') $query .= ' AND origin = :origin';
if ($destination !== 'ANY') $query .= ' AND destination = :destination';
if ($startDate !== '') $query .= ' AND start_date >= :startDate';
if ($endDate !== '') $query .= ' AND end_date <= :endDate';
if ($rego !== '') $query .= ' AND vehicle_rego LIKE :rego';


// Append Limit and Offset parameters to the query
$query .= ' LIMIT :limit OFFSET :offset';

// Bind Constant Parameters to the query statement
$stmt = $pdo->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':distanceMin', $distanceMin, PDO::PARAM_INT);
$stmt->bindParam(':distanceMax', $distanceMax, PDO::PARAM_INT);

// Bind filters parameters to the query statement if they are not the default value
if ($vehicleType !== 'ALL') $stmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($origin !== 'ANY') $stmt->bindParam(':origin', $origin, PDO::PARAM_STR);
if ($destination !== 'ANY') $stmt->bindParam(':destination', $destination, PDO::PARAM_STR);
if ($startDate !== '') $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
if ($endDate !== '') $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
if ($rego !== '') $stmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

// Execute and data from the query
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total trip count
$countQuery = 'SELECT COUNT(*) as count 
    FROM trip_whole 
    WHERE distance >= :distanceMin 
        AND distance <= :distanceMax';


if ($vehicleType !== 'ALL') $countQuery .= ' AND vehicle_category = :vehicleType';
if ($origin !== 'ANY') $countQuery .= ' AND origin = :origin';
if ($destination !== 'ANY') $countQuery .= ' AND destination = :destination';
if ($startDate !== '') $countQuery .= ' AND start_date >= :startDate';
if ($endDate !== '') $countQuery .= ' AND end_date <= :endDate';
if ($rego !== '') $countQuery .= ' AND vehicle_rego LIKE :rego';


$countStmt = $pdo->prepare($countQuery);
$countStmt->bindParam(':distanceMin', $distanceMin);
$countStmt->bindParam(':distanceMax', $distanceMax);

if ($vehicleType !== 'ALL') $countStmt->bindParam(':vehicleType', $vehicleType, PDO::PARAM_STR);
if ($origin !== 'ANY') $countStmt->bindParam(':origin', $origin, PDO::PARAM_STR);
if ($destination !== 'ANY') $countStmt->bindParam(':destination', $destination, PDO::PARAM_STR);
if ($startDate !== '') $countStmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
if ($endDate !== '') $countStmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
if ($rego !== '') $countStmt->bindValue(':rego', $rego . '%', PDO::PARAM_STR);

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
