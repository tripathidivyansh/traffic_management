<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Traffic Management System</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h1 class="logo">AI Traffic Control</h1>
            <ul class="nav-links">
                <li><a href="?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="?page=rules" class="<?= $page === 'rules' ? 'active' : '' ?>">Traffic Rules</a></li>
                <li><a href="?page=about" class="<?= $page === 'about' ? 'active' : '' ?>">About</a></li>
            </ul>
            <div class="day-night-toggle">
                <span>Day</span>
                <label class="switch">
                    <input type="checkbox" id="day-night-toggle">
                    <span class="slider round"></span>
                </label>
                <span>Night</span>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <?php
        switch($page) {
            case 'dashboard':
                include 'dashboard.php';
                break;
            case 'rules':
                include 'rules.php';
                break;
            case 'about':
                include 'about.php';
                break;
            default:
                include 'dashboard.php';
        }
        ?>
    </div>

    <script src="script.js"></script>
</body>
</html>