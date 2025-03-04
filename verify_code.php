<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    // Check if the verification code is correct and not expired
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? AND verification_code = ? AND verification_code_expiry > NOW()");
    $stmt->bind_param("si", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verification code is correct, redirect to reset password form
        header("Location: reset_password.php?email=$email");
        exit();
    } else {
        $error = "Invalid or expired verification code.";
    }
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    header('Location: forgot_password.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-section"></div>
        <div class="form-section">
            <div class="login-container">
                <h3>Verify Code</h3>
                <p>Please enter the code sent to your email.</p><br>
                <form method="POST" action="">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <div class="form-group">
                        <label for="verification_code">Verification Code</label>
                        <input type="text" id="verification_code" name="verification_code" required>
                    </div>
                    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                    <button type="submit" class="btn">Verify</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>