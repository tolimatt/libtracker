<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <canvas id="userChart"></canvas>
    </div>
    <div class="dashboard-row">
        <div class="dashboard-box">ATTENDANCE</div>
        <div class="dashboard-box">BORROWED BOOK HISTORY</div>
    </div>

    <?php
    include 'db_config.php';

    $query = "SELECT department, COUNT(*) as user_count FROM user GROUP BY department";
    $result = $conn->query($query);

    $departments = [];
    $user_counts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row['department'];
            $user_counts[] = $row['user_count'];
        }
    }
    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($departments); ?>,
                datasets: [{
                    label: 'Users Created',
                    data: <?php echo json_encode($user_counts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
</body>
</html>