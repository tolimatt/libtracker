<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Library Management Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="attendance.php">Attendance</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="dashboard.php">Books</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="dashboard">
            <div class="card" onclick="window.location.href='attendance.php';">
                <h2>Attendance</h2>
                <p>Track staff and student attendance</p>
            </div>
            <div class="card" onclick="window.location.href='users.php';">
                <h2>Users</h2>
                <p>Manage registered users</p>
            </div>
            <div class="card" onclick="window.location.href='transactions.php';">
                <h2>Transactions</h2>
                <p>View book borrow and return transactions</p>
            </div>
            <div class="card" onclick="window.location.href='dashboard.php';">
                <h2>Books</h2>
                <p>View and manage the book inventory</p>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Library Management System</p>
    </footer>
</body>
</html>
