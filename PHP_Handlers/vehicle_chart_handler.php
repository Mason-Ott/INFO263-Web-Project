<?php
header('Content-Type: application/json');

// Connect to SQLite database
include_once __DIR__ . '/../db.php';

// Get rego from request url
$rego = $_GET['rego'];

// Query for vehicle commissioned date, decommissioned date and odometer
$stmt = $pdo->prepare("SELECT commissioned_date.date AS commissioned_date, 
                 decommissioned_date.date AS decommissioned_date,
                 vehicle.odometer
          FROM vehicle
          LEFT JOIN sim_day_date AS commissioned_date 
              ON vehicle.commissioned = commissioned_date.sim_day
          LEFT JOIN sim_day_date AS decommissioned_date 
              ON vehicle.decommissioned = decommissioned_date.sim_day
          WHERE vehicle_rego = :rego");
$stmt->bindParam(':rego', $rego);
$stmt->execute();
$dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query for vehicle trip and relocation data
$stmt = $pdo->prepare("SELECT start_date, end_Date, distance, type as movement_type, origin, destination
                    FROM vehicle_movements
                    WHERE vehicle_rego = :rego");
$stmt->bindParam(':rego', $rego);
$stmt->execute();
$movements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query for vehicle maintenance data
$stmt = $pdo->prepare("SELECT start_date.date AS start_date, 
                        end_date.date AS end_date, mileage, location
                    FROM maintenance
                    LEFT JOIN sim_day_date AS start_date 
                        ON maintenance.start_day = start_date.sim_day
                    LEFT JOIN sim_day_date AS end_date 
                        ON maintenance.end_day = end_date.sim_day
                    WHERE vehicle_rego = :rego");
$stmt->bindParam(':rego', $rego);
$stmt->execute();
$maintenance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query for vehicle odometer when commissioned
$stmt = $pdo->prepare("SELECT (v.odometer - SUM(vm.distance)) AS start_odometer
                    FROM vehicle_movements vm
                    JOIN vehicle v ON v.vehicle_rego = vm.vehicle_rego
                    WHERE v.vehicle_rego = :rego
                    ");
$stmt->bindParam(':rego', $rego);
$stmt->execute();
$start_odometer = $stmt->fetch(PDO::FETCH_ASSOC);

// Return JSON encoded data
$data = [
    'dates' => $dates,
    'movements' => $movements,
    'maintenance' => $maintenance,
    'start_odometer' => $start_odometer
];
echo json_encode($data);