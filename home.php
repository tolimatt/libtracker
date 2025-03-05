<?php 
ob_start();
include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="home.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="header">
        <h1>Dashboard</h1>
        <div class="header-right">
        <?php  date_default_timezone_set('Asia/Manila'); // Ensure the timezone is set correctly
        echo date('l, F j, Y g:i A'); ?>
        </div>
    </nav>
    <div class="dashboard-long-box">
        <h2>USER CREATED BY EACH DEPARTMENT</h2>
        <div class="department-boxes">
            <?php
            $departments = ['CAHS', 'CCJE', 'CEA', 'CELA', 'CITE', 'CMA', 'COL','SHS'];
            foreach ($departments as $department) {
                $query = "SELECT COUNT(*) as user_count FROM user WHERE department = '$department'";
                $result = $conn->query($query);
                $user_count = 0;
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $user_count = $row['user_count'];
                }
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
            <canvas id="attendanceChart"></canvas>
        </div>
        <div class="dashboard-box">BORROWED BOOK HISTORY</div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Fetch attendance data from the server
        fetch('fetch_attendance_data.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data); // Debugging: Log fetched data
                const departments = data.map(item => item.department);
                const attendanceCounts = data.map(item => item.attendance_count);

                console.log('Departments:', departments); // Debugging: Log departments
                console.log('Attendance Counts:', attendanceCounts); // Debugging: Log attendance counts

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: departments,
                        datasets: [{
                            label: 'Attendance per Department',
                            data: attendanceCounts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(199, 199, 199, 0.2)',
                                'rgba(83, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(199, 199, 199, 1)',
                                'rgba(83, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Attendance per Department'
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching attendance data:', error));
    });
    </script>
</body>
</html>