<?php
require_once __DIR__ . '/../db.php';

// Define the locations
$locations = ['AUCKLAND', 'CHRISTCHURCH', 'DUNEDIN', 'QUEENSTOWN', 'WELLINGTON'];

// Initialize the data array
$data = [
    'labels' => [], // Months
    'auckland_outgoing' => [],
    'auckland_incoming' => [],
    'christchurch_outgoing' => [],
    'christchurch_incoming' => [],
    'dunedin_outgoing' => [],
    'dunedin_incoming' => [],
    'queenstown_outgoing' => [],
    'queenstown_incoming' => [],
    'wellington_outgoing' => [],
    'wellington_incoming' => []
];

// Get all outgoing and incoming trips by month
$query = 'SELECT strftime("%Y-%m", start_date) AS month, origin, destination, COUNT(*) as trip_count 
          FROM trip_whole 
          GROUP BY month, origin, destination';
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize an array to track months we've already processed
$processed_months = [];

foreach ($results as $row) {
    $month = $row['month'];

    // Ensure that each month is added only once
    if (!in_array($month, $data['labels'])) {
        $data['labels'][] = $month;
        $processed_months[$month] = [
            'auckland_outgoing' => 0, 'auckland_incoming' => 0,
            'christchurch_outgoing' => 0, 'christchurch_incoming' => 0,
            'dunedin_outgoing' => 0, 'dunedin_incoming' => 0,
            'queenstown_outgoing' => 0, 'queenstown_incoming' => 0,
            'wellington_outgoing' => 0, 'wellington_incoming' => 0
        ];
    }

    // For each location, update the outgoing and incoming counts
    foreach ($locations as $location) {
        $location_key_outgoing = strtolower($location) . '_outgoing';
        $location_key_incoming = strtolower($location) . '_incoming';

        // Update outgoing trips
        if ($row['origin'] === $location) {
            $processed_months[$month][$location_key_outgoing] += $row['trip_count'];
        }

        // Update incoming trips
        if ($row['destination'] === $location) {
            $processed_months[$month][$location_key_incoming] += $row['trip_count'];
        }
    }
}

// Populate the final arrays for outgoing and incoming data per location
foreach ($data['labels'] as $month) {
    foreach ($locations as $location) {
        $location_key_outgoing = strtolower($location) . '_outgoing';
        $location_key_incoming = strtolower($location) . '_incoming';

        $data[$location_key_outgoing][] = $processed_months[$month][$location_key_outgoing];
        $data[$location_key_incoming][] = $processed_months[$month][$location_key_incoming];
    }
}

// Send JSON response
echo json_encode($data);
?>
