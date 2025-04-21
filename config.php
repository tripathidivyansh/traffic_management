<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'traffic_management');

// Create tables if they don't exist
function initializeDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);
    
    // Create traffic_data table
    $conn->query("CREATE TABLE IF NOT EXISTS traffic_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        direction ENUM('north', 'south', 'east', 'west') NOT NULL,
        vehicle_count INT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (direction),
        INDEX (timestamp)
    )");
    
    // Create traffic_lights table
    $conn->query("CREATE TABLE IF NOT EXISTS traffic_lights (
        direction ENUM('north', 'south', 'east', 'west') PRIMARY KEY,
        status ENUM('red', 'yellow', 'green') NOT NULL DEFAULT 'red'
    )");
    
    // Initialize light directions if empty
    $result = $conn->query("SELECT COUNT(*) as count FROM traffic_lights");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO traffic_lights (direction, status) VALUES 
            ('north', 'red'), ('south', 'red'), ('east', 'red'), ('west', 'red')");
    }
    
    // Create light_changes log table
    $conn->query("CREATE TABLE IF NOT EXISTS light_changes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        green_direction ENUM('north', 'south', 'east', 'west', 'pedestrian') NOT NULL,
        change_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (change_time)
    )");
    
    $conn->close();
}

// Call the initialization function
initializeDatabase();
?>