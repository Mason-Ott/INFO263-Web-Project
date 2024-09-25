<?php

header('Content-Type: application/json');

include_once __DIR__ . '/../db.php';

$rego = isset($_GET['rego']) ? $_GET['rego'] : '';

// Get vehicle data
$vehicleQuery = '
    SELECT vehicle.vehicle_category, vehicle.odometer, 
           commissioned_date.date AS commissioned_date, 
           decommissioned_date.date AS decommissioned_date,
           (vehicle.odometer - maintenance.mileage) AS distance_since_maintenance
    FROM vehicle
    LEFT JOIN sim_day_date AS commissioned_date 
        ON vehicle.commissioned = commissioned_date.sim_day
    LEFT JOIN sim_day_date AS decommissioned_date 
        ON vehicle.decommissioned = decommissioned_date.sim_day
    LEFT JOIN maintenance ON vehicle.vehicle_rego = maintenance.vehicle_rego
    WHERE vehicle.vehicle_rego = :rego';

$vehicleStmt = $pdo->prepare($vehicleQuery);
$vehicleStmt->bindValue(':rego', $rego);
$vehicleStmt->execute();
$vehicleData = $vehicleStmt->fetch(PDO::FETCH_ASSOC);

// Get trip data
$tripQuery = 'SELECT * FROM trip_whole WHERE vehicle_rego = :rego ORDER BY start_date ASC';
$tripStmt = $pdo->prepare($tripQuery);
$tripStmt->bindValue(':rego', $rego);
$tripStmt->execute();
$tripData = $tripStmt->fetchAll(PDO::FETCH_ASSOC);

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
    ORDER BY maintenance.start_day ASC';
$maintenanceStmt = $pdo->prepare($maintenanceQuery);
$maintenanceStmt->bindValue(':rego', $rego);
$maintenanceStmt->execute();
$maintenanceData = $maintenanceStmt->fetchAll(PDO::FETCH_ASSOC);

// Combine all data into a single response
$response = [
    'vehicle' => $vehicleData,
    'trips' => $tripData,
    'maintenance' => $maintenanceData
];

echo json_encode($response);

?>
