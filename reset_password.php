<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Update the password
        $hashed_password = hash('sha256', $new_password);
        $stmt = $conn->prepare("UPDATE admins SET password = ?, verification_code = NULL, verification_code_expiry = NULL WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        $success = "Your password has been reset successfully.";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
    }
} elseif (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-section"></div>
        <div class="form-section">
            <div class="login-container">
                <h3>Reset Password</h3>
                <form method="POST" action="" onsubmit="return validatePasswords()">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <p id="passwordError" class="error" style="display: none;">Passwords do not match.</p>
                    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                    <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
                    <button type="submit" class="btn">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function validatePasswords() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const passwordError = document.getElementById('passwordError');

        if (newPassword !== confirmPassword) {
            passwordError.style.display = 'block';
            return false;
        } else {
            passwordError.style.display = 'none';
            return true;
        }
    }
    </script>
</body>
</html>