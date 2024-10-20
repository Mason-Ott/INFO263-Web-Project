<?php
require_once __DIR__ . '/../db.php';

//if (isset($_GET['type']) && $_GET['type'] === 'quarterly_indicators') {
    // Fetch quarterly indicators data
    $query = 'SELECT * FROM quarterly_indicators';
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the data to be sent as JSON
    $labels = [];
    $hire_revenue = [];
    $vehicle_purchasing = [];
    $maintenance_expenses = [];
    $relocation_expenses = [];
    $upgrade_losses = [];
    $profit = [];

    foreach ($results as $row) {
        $labels[] = $row['Period'];
        $hire_revenue[] = $row['Hire revenue'];
        $vehicle_purchasing[] = $row['Vehicle purchasing'];
        $maintenance_expenses[] = $row['Maintenance expenses'];
        $relocation_expenses[] = $row['Relocations expenses'];
        $upgrade_losses[] = $row['Upgrade losses'];
        $profit[] = $row['Profit'];
    }

    // Send the data as JSON
    echo json_encode([
        'labels' => $labels,
        'hire_revenue' => $hire_revenue,
        'vehicle_purchasing' => $vehicle_purchasing,
        'maintenance_expenses' => $maintenance_expenses,
        'relocation_expenses' => $relocation_expenses,
        'upgrade_losses' => $upgrade_losses,
        'profit' => $profit
    ]);
//}


?>
