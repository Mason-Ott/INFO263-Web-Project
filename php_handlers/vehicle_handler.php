<?php

header('Content-Type: application/json');

include_once __DIR__ . '/../db.php';

$rego = isset($_GET['rego']) ? $_GET['rego'] : '';
$offset1 = filter_var($_GET['offset1'] ?? 0, FILTER_VALIDATE_INT);
$offset2 = filter_var($_GET['offset2'] ?? 0, FILTER_VALIDATE_INT);
$offset3 = filter_var($_GET['offset3'] ?? 0, FILTER_VALIDATE_INT);

// Get vehicle data
$vehicleQuery = '
    SELECT vehicle.vehicle_category, vehicle.odometer, 
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
    WHERE vehicle.vehicle_rego = :rego';

$vehicleStmt = $pdo->prepare($vehicleQuery);
$vehicleStmt->bindValue(':rego', $rego);
$vehicleStmt->execute();
$vehicleData = $vehicleStmt->fetch(PDO::FETCH_ASSOC);

// Get trip data
$tripQuery = 'SELECT * FROM trip_whole WHERE vehicle_rego = :rego ORDER BY start_date LIMIT 20 OFFSET :offset';
$tripStmt = $pdo->prepare($tripQuery);
$tripStmt->bindValue(':rego', $rego);
$tripStmt->bindValue(':offset', $offset1);
$tripStmt->execute();
$tripData = $tripStmt->fetchAll(PDO::FETCH_ASSOC);

// Get trip count data
$tripCountQuery = 'SELECT COUNT(*) AS count FROM trip WHERE vehicle_rego = :rego';
$tripCountStmt = $pdo->prepare($tripCountQuery);
$tripCountStmt->bindValue(':rego', $rego);
$tripCountStmt->execute();
$totalTrips = $tripCountStmt->fetch(PDO::FETCH_ASSOC);
$totalPages1 = ceil($totalTrips['count'] / 20);
$currentPage1 = floor($offset1 / 20) + 1;
$tripCountData = [
    'totalTrips' => $totalTrips,
    'totalPages' => $totalPages1,
    'currentPage' => $currentPage1
];


// Get relocation data
$relocationQuery = 'SELECT * FROM relocation_whole WHERE vehicle_rego = :rego ORDER BY start_date ASC LIMIT 20 OFFSET :offset';
$relocationStmt = $pdo->prepare($relocationQuery);
$relocationStmt->bindValue(':rego', $rego);
$relocationStmt->bindParam(':offset', $offset2);
$relocationStmt->execute();
$relocationData = $relocationStmt->fetchAll(PDO::FETCH_ASSOC);

// Get relocation count data
$relocationCountQuery = 'SELECT COUNT(*) AS count FROM relocation WHERE vehicle_rego = :rego';
$relocationCountStmt = $pdo->prepare($relocationCountQuery);
$relocationCountStmt->bindValue(':rego', $rego);
$relocationCountStmt->execute();
$totalRelocations = $relocationCountStmt->fetch(PDO::FETCH_ASSOC);
$totalPages2 = ceil($totalRelocations['count'] / 20);
$currentPage2 = floor($offset2 / 20) + 1;
$relocationCountData = [
    'totalRelocations' => $totalRelocations,
    'totalPages' => $totalPages2,
    'currentPage' => $currentPage2
];

// Get maintenance data
$maintenanceQuery = '
    SELECT maintenance.location, maintenance.mileage, 
           start_date.date AS start_date, 
           end_date.date AS end_date
    FROM maintenance
    LEFT JOIN sim_day_date AS start_date 
        ON maintenance.start_day = start_date.sim_day
    LEFT JOIN sim_day_date AS end_date 
        ON maintenance.end_day = end_date.sim_day
    WHERE maintenance.vehicle_rego = :rego
    ORDER BY maintenance.start_day ASC
    LIMIT 20 OFFSET :offset';
$maintenanceStmt = $pdo->prepare($maintenanceQuery);
$maintenanceStmt->bindValue(':rego', $rego);
$maintenanceStmt->bindValue(':offset', $offset3);
$maintenanceStmt->execute();
$maintenanceData = $maintenanceStmt->fetchAll(PDO::FETCH_ASSOC);

// Get maintenance data
$maintenanceCountQuery = 'SELECT COUNT(*) AS count FROM maintenance WHERE maintenance.vehicle_rego = :rego';
$maintenanceCountStmt = $pdo->prepare($maintenanceCountQuery);
$maintenanceCountStmt->bindValue(':rego', $rego);
$maintenanceCountStmt->execute();
$totalMaintenance = $maintenanceCountStmt->fetch(PDO::FETCH_ASSOC);
$totalPages3 = ceil($totalRelocations['count'] / 20);
$currentPage3 = floor($offset3 / 20) + 1;
$maintenanceCountData = [
    '$totalMaintenance' => $totalMaintenance,
    'totalPages' => $totalPages3,
    'currentPage' => $currentPage3
];


// Combine all data into a single response
$response = [
    'vehicle' => $vehicleData,
    'trips' => $tripData,
    'relocations' => $relocationData,
    'maintenance' => $maintenanceData,
    'tripCount' => $tripCountData,
    'relocationCount' => $relocationCountData,
    'maintenanceCount' => $maintenanceCountData
];

echo json_encode($response);

?>
