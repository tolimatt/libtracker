<?php include 'db_config.php'; ?>

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
        <div class="dashboard-box">ATTENDANCE</div>
        <div class="dashboard-box">BORROWED BOOK HISTORY</div>
    </div>
</body>
</html>