<?php
require_once 'config.php'; // Include the database configuration

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer
// If not using Composer, uncomment the following lines:
// require 'vendor/PHPMailer/src/Exception.php';
// require 'vendor/PHPMailer/src/PHPMailer.php';
// require 'vendor/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if ($email) {
        // Check if the email exists in the users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate a reset token and set expiration (1 hour from now)
            $token = generateToken();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store the token in the password_resets table
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expires_at]);

            // Send the reset email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings (configure with your email provider)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@gmail.com'; // Replace with your email
                $mail->Password = 'your-app-password'; // Replace with your app password (not your regular password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('your-email@gmail.com', 'SAEGIS Campus Address Book');
                $mail->addAddress($email);

                // Content
                $reset_link = "http://yourdomain.com/reset-password.php?token=$token";
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Hi {$user['first_name']},<br><br>"
                            . "You requested a password reset. Click the link below to reset your password:<br>"
                            . "<a href='$reset_link'>Reset Password</a><br><br>"
                            . "This link will expire in 1 hour.<br>"
                            . "If you did not request this, please ignore this email.<br><br>"
                            . "Best regards,<br>SAEGIS Campus Team";

                $mail->send();
                echo "<script>alert('A password reset link has been sent to your email.'); window.location.href='login.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Failed to send email. Error: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Email not found.');</script>";
        }
    } else {
        echo "<script>alert('Please enter a valid email address.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Forgot Password - SAEGIS Campus Address Book</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="manifest" href="manifest.json">
    <link rel="icon" href="images/logo.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="images/logo.png" alt="SAEGIS Campus Logo" class="logo">
        </div>
        <div class="form-container">
            <h2>FORGOT PASSWORD</h2>
            <p>Enter your email address, we will send you an email</p>
            <form action="forgot-password.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit" class="btn">SUBMIT</button>
            </form>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>