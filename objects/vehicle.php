<?php
require_once 'db.php';

$VehicleDataQuery = "SELECT vehicle.vehicle_rego, vehicle.vehicle_category, vehicle.odometer, commissioned_date.date AS commissioned, decommissioned_date.date AS decommissioned
          FROM vehicle 
          LEFT JOIN sim_day_date commissioned_date ON vehicle.commissioned = commissioned_date.sim_day
          LEFT JOIN sim_day_date decommissioned_date ON vehicle.decommissioned = decommissioned_date.sim_day
          LIMIT 50";
$vehicleData = $pdo->query($VehicleDataQuery)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($vehicleData); // Return JSON data

$vehicleCategoryQuery = "SELECT DISTINCT vehicle_category FROM vehicle";
$vehicleCategories = $pdo->query($vehicleCategoryQuery)->fetchAll(PDO::FETCH_ASSOC);
?>
