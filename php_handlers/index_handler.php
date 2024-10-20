<?php
require_once __DIR__ . '/../db.php';

// Fetch single result values
$trip_completed = $pdo->query("SELECT `Trips Completed` FROM simulation_summary")->fetchColumn();
$trip_upgraded = $pdo->query("SELECT `Trips Upgraded` FROM simulation_summary")->fetchColumn();
$refused_bookings = $pdo->query("SELECT `Refused Bookings` FROM simulation_summary")->fetchColumn();
$refused_walk_ins = $pdo->query("SELECT `Refused Walk-ins` FROM simulation_summary")->fetchColumn();
$vehicles_relocated = $pdo->query("SELECT `Vehicles Relocated` FROM simulation_summary")->fetchColumn();
$vehicles_serviced = $pdo->query("SELECT `Vehicles Serviced` FROM simulation_summary")->fetchColumn();

// Fetch multiple rows for vehicle types
$vehicle_types = $pdo->query("
    SELECT 
        vehicle.vehicle_category, 
        COUNT(*) AS vehicle_count, 
        costs_and_rates.daily_hire_rate, 
        costs_and_rates.monthly_lease_cost 
    FROM vehicle 
    JOIN costs_and_rates 
    ON costs_and_rates.vehicle_category = vehicle.vehicle_category 
    GROUP BY vehicle.vehicle_category
")->fetchAll(PDO::FETCH_ASSOC);  // Fetch all rows as associative array
?>
