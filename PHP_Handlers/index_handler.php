<?php
require_once __DIR__ . '/../db.php';

$trip_completed = $pdo->query("SELECT `Trips Completed` FROM simulation_summary");
$trip_upgraded = $pdo->query("SELECT `Trips Upgraded` FROM simulation_summary");
$refused_bookings = $pdo->query("SELECT `Refused Bookings` FROM simulation_summary");
$refused_walk_ins = $pdo->query("SELECT `Refused Walk-ins` FROM simulation_summary");
$vehicles_relocated = $pdo->query("SELECT `Vehicles Relocated` FROM simulation_summary");
$vehicles_serviced = $pdo->query("SELECT `Vehicles Serviced` FROM simulation_summary");

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
");
?>
