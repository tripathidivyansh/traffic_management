<?php
header('Content-Type: application/json');
require_once 'config.php';

$hours = isset($_GET['hours']) ? intval($_GET['hours']) : 24;
$type = isset($_GET['type']) ? $_GET['type'] : 'volume';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// 1. Get hourly traffic volume data
$stmt = $conn->prepare("
    SELECT HOUR(timestamp) as hour, 
           AVG(vehicle_count) as avg_count 
    FROM traffic_data 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? HOUR)
    GROUP BY HOUR(timestamp)
    ORDER BY hour
");
$stmt->bind_param("i", $hours);
$stmt->execute();
$result = $stmt->get_result();

$volumeData = [];
while ($row = $result->fetch_assoc()) {
    $volumeData[] = [
        'x' => strtotime("today + {$row['hour']} hours") * 1000,
        'y' => round($row['avg_count'])
    ];
}

// 2. Get directional data for comparison chart
$directionalData = [
    'north' => [],
    'south' => [],
    'east' => [],
    'west' => []
];

$dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$categories = [];

// Get data for each day of week
for ($i = 0; $i < 7; $i++) {
    $dayStart = date('Y-m-d', strtotime("-$i days"));
    $dayEnd = date('Y-m-d', strtotime("-$i days +1 day"));
    $categories[] = $dayNames[$i];
    
    foreach (['north', 'south', 'east', 'west'] as $dir) {
        $stmt = $conn->prepare("
            SELECT AVG(vehicle_count) as avg_count 
            FROM traffic_data 
            WHERE direction = ? 
            AND timestamp BETWEEN ? AND ?
        ");
        $stmt->bind_param("sss", $dir, $dayStart, $dayEnd);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $directionalData[$dir][] = round($row['avg_count'] ?? 0);
    }
}

// 3. Get peak hour and busiest direction
$stmt = $conn->prepare("
    SELECT HOUR(timestamp) as peak_hour, 
           direction as busiest_direction
    FROM traffic_data
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? HOUR)
    GROUP BY HOUR(timestamp), direction
    ORDER BY COUNT(*) DESC
    LIMIT 1
");
$stmt->bind_param("i", $hours);
$stmt->execute();
$result = $stmt->get_result();
$peakData = $result->fetch_assoc();

// 4. Get pedestrian crossings (assuming you have a pedestrians table)
$pedestrians = 0;
$stmt = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM pedestrians 
    WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $pedestrians = $row['count'];
}

// Prepare final response
$response = [
    'volume' => $volumeData,
    'delays' => generateDelayData($hours), // Keep sample for delays or implement similar query
    'categories' => $categories,
    'directions' => [
        ['name' => 'North', 'data' => $directionalData['north']],
        ['name' => 'South', 'data' => $directionalData['south']],
        ['name' => 'East', 'data' => $directionalData['east']],
        ['name' => 'West', 'data' => $directionalData['west']]
    ],
    'peak_hour' => $peakData ? date('g:i A', strtotime($peakData['peak_hour'] . ':00')) : 'N/A',
    'busiest_direction' => $peakData ? ucfirst($peakData['busiest_direction']) : 'N/A',
    'pedestrians' => $pedestrians
];

echo json_encode($response);

// Sample delay data function (replace with real query if available)
function generateDelayData($hours) {
    $data = [];
    $now = time();
    $interval = $hours * 3600 / 12;
    
    for ($i = 0; $i < 12; $i++) {
        $timestamp = ($now - ($hours * 3600)) + ($i * $interval);
        $data[] = [
            'x' => $timestamp * 1000,
            'y' => rand(1, 15)
        ];
    }
    return $data;
}
?>