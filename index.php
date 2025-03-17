<?php
session_start();
include 'db_config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($conn) { // Check if the connection is active
        // Query to check admin credentials
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = SHA2(?, 256)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            $error = "Invalid username or password!";
        }
        $stmt->close();
    } else {
        $error = "Database connection error!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-section"></div> <!-- Logo and Background Color -->
        <div class="form-section">
            <div class="login-container">
                <h3>Admin Login</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="instruction">Please fill in your unique admin login details below</label><br>
                        <label for="username" class="Username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="Password">Password</label>
                        <input type="password" id="password" name="password" required>  
                        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                        
                    </div>
                    <button type="submit" class="btn">Sign In</button>
                    <div class="forgot_password">
                        <a href="forgot_password.php">Forgot password?</a>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>