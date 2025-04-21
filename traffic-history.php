<?php
header('Content-Type: application/json');
require_once 'config.php';

$hours = isset($_GET['hours']) ? intval($_GET['hours']) : 24;
$interval = $hours * 60 * 60; // Convert to seconds

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Get aggregated traffic data for each direction
$query = "SELECT 
    direction,
    UNIX_TIMESTAMP(DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00')) * 1000 as hour,
    AVG(vehicle_count) as avg_count
FROM traffic_data
WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? HOUR)
GROUP BY direction, hour
ORDER BY hour ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hours);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    'north' => [],
    'south' => [],
    'east' => [],
    'west' => []
];

while ($row = $result->fetch_assoc()) {
    $data[$row['direction']][] = [
        $row['hour'],
        round($row['avg_count'])
    ];
}

echo json_encode($data);

$conn->close();
?>