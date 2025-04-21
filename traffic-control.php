<?php
header('Content-Type: application/json');
require_once 'config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get current mode
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'auto';

// Initialize response
$response = [
    'lights' => [
        'north' => 'red',
        'south' => 'red',
        'east' => 'red',
        'west' => 'red'
    ],
    'traffic' => [
        'north' => rand(0, 20),
        'south' => rand(0, 20),
        'east' => rand(0, 20),
        'west' => rand(0, 20)
    ],
    'timestamp' => date('Y-m-d H:i:s')
];

// Process different modes
if ($mode === 'auto') {
    // Auto mode - simple alternating pattern for demo
    $greenDirection = ['north-south', 'east-west'][time() % 2];
    
    if ($greenDirection === 'north-south') {
        $response['lights']['north'] = 'green';
        $response['lights']['south'] = 'green';
    } else {
        $response['lights']['east'] = 'green';
        $response['lights']['west'] = 'green';
    }
} 
elseif ($mode === 'manual' && isset($_GET['direction'])) {
    // Manual mode
    $direction = $_GET['direction'];
    $opposite = [
        'north' => 'south',
        'south' => 'north',
        'east' => 'west',
        'west' => 'east'
    ][$direction];
    
    $response['lights'][$direction] = 'green';
    $response['lights'][$opposite] = 'green';
}

// Update database (simplified)
foreach ($response['lights'] as $dir => $status) {
    $stmt = $conn->prepare("UPDATE traffic_lights SET status = ? WHERE direction = ?");
    $stmt->bind_param("ss", $status, $dir);
    $stmt->execute();
}

// Insert traffic data
foreach ($response['traffic'] as $dir => $count) {
    $stmt = $conn->prepare("INSERT INTO traffic_data (direction, vehicle_count) VALUES (?, ?)");
    $stmt->bind_param("si", $dir, $count);
    $stmt->execute();
}

echo json_encode($response);
$conn->close();
?>