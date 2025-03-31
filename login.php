<?php
require_once 'config.php'; // Include the database configuration

session_start(); // Start a session to store user data after login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_or_username = trim($_POST['email_or_username']);
    $password = $_POST['password'];

    if ($email_or_username && $password) {
        // Hardcoded admin credentials
        $admin_username = 'saegisadmin';
        $admin_password = '12345';

        // Check if the credentials match the admin
        if ($email_or_username === $admin_username && $password === $admin_password) {
            // Admin login successful
            $_SESSION['admin_id'] = 1; // Hardcoded admin ID
            $_SESSION['admin_username'] = $admin_username;
            header("Location: admin-dashboard.php");
            exit();
        } else {
            // Check if the email exists in the users table
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email_or_username]);
            $user = $stmt->fetch();

            if ($user) {
                // Check the user's status
                if ($user['status'] === 'pending') {
                    echo "<script>alert('Your account is pending approval by the admin.');</script>";
                } elseif ($user['status'] === 'rejected') {
                    echo "<script>alert('Your account has been rejected by the admin.');</script>";
                } elseif ($user['status'] === 'accepted' && password_verify($password, $user['password'])) {
                    // Password is correct and user is accepted, start a session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Invalid email or password');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password');</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill all fields');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Login - SAEGIS Campus Address Book</title>
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
            <h2>LOGIN</h2>
            <form action="login.php" method="POST">
                <input type="text" name="email_or_username" placeholder="Email or Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="forgot-password.php" class="link">Forgot Password?</a>
                <button type="submit" class="btn">LOGIN</button>
            </form>
            <p>Don't have an account? <a href="register.php" class="link">Register</a></p>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>