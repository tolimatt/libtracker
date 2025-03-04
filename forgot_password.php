<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique verification code
        $verification_code = rand(100000, 999999);

        // Store the verification code in the database with an expiration time
        $stmt = $conn->prepare("UPDATE admins SET verification_code = ?, verification_code_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->bind_param("is", $verification_code, $email);
        $stmt->execute();

        // Send the verification code to the user's email
        $subject = "Password Reset Verification Code";
        $message = "Your password reset verification code is: $verification_code";

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'esperanzagabrieljose@gmail.com'; // SMTP username
            $mail->Password = 'byfz isfg qlgh syxl'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'LibTrack');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            $success = "Verification code has been sent to your email.";
            header("Location: verify_code.php?email=$email");
            exit();
        } catch (Exception $e) {
            $error = "Failed to send the email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="global.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-section"></div>
        <div class="form-section">
            <div class="login-container">
                <h3>Forgot Password</h3>
                <p>Please enter your email address to receive a verification code.</p><br>
                <form method="POST" action="">
                    <div class="form-group">
                        
                        <input class="input_email" type="email" id="email" name="email" placeholder="email address" required>
                    </div>
                    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                    <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
                    <button type="submit" class="btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>