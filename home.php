<?php 
ob_start();
include 'db_config.php'; 
date_default_timezone_set('Asia/Manila');

// Fetch all department user counts in one query
$departmentCounts = [];
$query = "SELECT department, COUNT(*) as user_count FROM user GROUP BY department";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $departmentCounts[$row['department']] = $row['user_count'];
}

// Fetch attendance data per department
$attendanceData = [];
$query = "SELECT department, COUNT(*) as attendance_count FROM attendance GROUP BY department";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $attendanceData[$row['department']] = $row['attendance_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="home.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard</title>
    
</head>
<body>
<nav class="header">
    <h1>Dashboard</h1>

    <!-- Container to keep notification and header-right together -->
    <div class="header-actions">
        <button id="notificationButton" class="notification-btn">
            <i class='bx bx-bell'></i>
        </button>
        <div class="header-right">
            <?php echo date('l, F j, Y g:i A'); ?>
        </div>
    </div>
</nav>
    <div class="dashboard-long-box">
        <h2>USER CREATED BY EACH DEPARTMENT</h2>
        <div class="department-boxes">
            <?php
            $departments = ['CAHS', 'CCJE', 'CEA', 'CELA', 'CITE', 'CMA', 'COL','SHS'];
            foreach ($departments as $department) {
                $user_count = $departmentCounts[$department] ?? 0;
                echo "<div class='department-box'>
                        <img src='images/{$department}.png' alt='{$department} Logo' class='department-logo'>
                        <div class='user-info'>
                            <div class='department-label'>{$department}</div>
                            <div class='user-count'>{$user_count}</div>
                        </div>
                      </div>";
            }
            ?>
        </div>
    </div>
    <div class="dashboard-row">
        <div class="dashboard-box">
            <h3>ATTENDANCE</h3>
            <div class="attendance-container">
                <div class="attendance-labels">
                    <?php
                    foreach ($attendanceData as $department => $count) {
                        echo "<div>{$department}: {$count}</div>";
                    }
                    ?>
                </div>
                <div class="attendance-chart">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="dashboard-box"><h3>BORROWED BOOK</h3></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var departmentColors = {
    'CAHS': '#008000',  // Green
    'CCJE': '#808080',  // Gray
    'CEA': '#FF0000',   // Red
    'CELA': '#0000FF',  // Blue
    'CITE': '#000000',  // Black
    'CMA': '#FFFF00',   // Yellow
    'COL': '#FFA500',   // Orange (Default for missing)
    'SHS': '#800080'    // Purple (Default for missing)
};

// Pass PHP attendance data to JavaScript
var attendanceData = <?php echo json_encode($attendanceData); ?>;

// Prepare data for the chart
var labels = Object.keys(attendanceData);
var data = Object.values(attendanceData);

// Assign colors based on department
var backgroundColors = labels.map(dept => departmentColors[dept] || '#808080'); // Default gray if missing

var ctx = document.getElementById('attendanceChart').getContext('2d');
var attendanceChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: labels,
        datasets: [{
            label: 'Attendance Count',
            data: data,
            backgroundColor: backgroundColors,
            borderColor: '#fff',
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 14
                    },
                    color: "#333"
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: "rgba(0,0,0,0.7)",
                bodyFont: {
                    size: 14
                },
                padding: 10
            }
        }
    }
});


    </script>
</body>
</html>
