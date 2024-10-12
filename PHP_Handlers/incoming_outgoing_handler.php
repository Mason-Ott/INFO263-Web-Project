<?php
require_once __DIR__ . '/../db.php';


$locations = ['AUCKLAND', 'CHRISTCHURCH', 'DUNEDIN', 'QUEENSTOWN', 'WELLINGTON'];

$data = [
    'labels' => [],
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

$query = 'SELECT strftime("%Y-%m", start_date) AS month, origin, destination, COUNT(*) as trip_count 
          FROM trip_whole 
          GROUP BY month, origin, destination';
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $month = $row['month'];

    if (!in_array($month, $data['labels'])) {
        $data['labels'][] = $month;
    }

    foreach ($locations as $location) {
        if ($row['origin'] === $location) {
            $data[strtolower($location).'_outgoing'][] = $row['trip_count'];
        } elseif ($row['destination'] === $location) {
            $data[strtolower($location).'_incoming'][] = $row['trip_count'];
        }
    }
}

// Send JSON response
echo json_encode($data);


?>
