<?php
require_once 'config.php'; // Include the database configuration

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];

            if ($new_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the user's password
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $reset['email']]);

                // Delete the reset token
                $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);

                echo "<script>alert('Password reset successful! Please login with your new password.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Please enter a new password.');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid or expired token.'); window.location.href='forgot-password.php';</script>";
    }
} else {
    header("Location: forgot-password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Reset Password - SAEGIS Campus Address Book</title>
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
            <h2>RESET PASSWORD</h2>
            <form action="" method="POST">
                <input type="password" name="new_password" placeholder="New Password" required>
                <button type="submit" class="btn">RESET PASSWORD</button>
            </form>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>