<?php
require_once 'config.php'; // Include the database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $terms = isset($_POST['terms']);

    if ($first_name && $last_name && $email && $password && $terms) {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$first_name, $last_name, $email, $hashed_password])) {
                echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill all fields and agree to the terms.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Create an Account - SAEGIS Campus Address Book</title>
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
            <h2>CREATE AN ACCOUNT</h2>
            <form action="register.php" method="POST">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="checkbox-container">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I agree to the Terms and Conditions</label>
                </div>
                <button type="submit" class="btn">REGISTER</button>
            </form>
            <p>Already have an account? <a href="login.php" class="link">Login</a></p>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>