<?php
header('Content-Type: application/json');
require_once 'config.php';

// Simulate random traffic data with more realistic patterns
$directions = ['north', 'south', 'east', 'west'];
$trafficData = [];

// Get current hour to simulate daily patterns
$currentHour = date('G');
$isPeakHour = ($currentHour >= 7 && $currentHour <= 9) || ($currentHour >= 16 && $currentHour <= 18);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

foreach ($directions as $dir) {
    // Base traffic pattern
    $base = rand(1, 10);
    
    // Directional bias
    if ($dir == 'east' || $dir == 'west') {
        $base += 3; // More traffic on east-west (main road)
    }
    
    // Peak hour adjustment
    if ($isPeakHour) {
        $base *= 2;
        if ($dir == 'east' || $dir == 'west') {
            $base += 10; // Even more on main road during peak
        }
    }
    
    // Random spikes
    $spike = (rand(1, 10) > 8) ? rand(10, 30) : 0;
    $count = $base + $spike;
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO traffic_data (direction, vehicle_count) VALUES (?, ?)");
    $stmt->bind_param("si", $dir, $count);
    $stmt->execute();
    
    $trafficData[$dir] = $count;
}

// Get current light status for response
$query = "SELECT direction, status FROM traffic_lights";
$result = $conn->query($query);
$lightStatus = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lightStatus[$row['direction']] = $row['status'];
    }
}

$response = [
    'traffic' => $trafficData,
    'lights' => $lightStatus,
    'timestamp' => date('Y-m-d H:i:s')
];

echo json_encode($response);

$conn->close();
?>