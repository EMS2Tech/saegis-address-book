<?php
// Database configuration
$host = 'localhost'; // Your database host (usually 'localhost')
$dbname = 'saegis_address_book'; // Database name
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password (default is empty for XAMPP/WAMP)

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate a random token for password reset
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}
?>