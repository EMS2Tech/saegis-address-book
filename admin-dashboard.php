<?php
session_start();
require_once 'config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'accepted' WHERE id = ?");
        $stmt->execute([$user_id]);
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    // Refresh the page to show updated status
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Admin Dashboard - SAEGIS Campus Address Book</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="manifest" href="manifest.json">
    <link rel="icon" href="images/logo.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="images/logo.png" alt="SAEGIS Campus Logo" class="logo">
            <h2>ADMIN DASHBOARD</h2>
            <p>Registered Users</p>
            <a href="logout.php" class="link">Logout</a>
        </div>

        <div class="user-list">
            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <div class="user-item">
                        <div class="user-details">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($user['status']); ?></p>
                            <p><strong>Registered:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                        </div>
                        <?php if ($user['status'] === 'pending'): ?>
                            <div class="user-actions">
                                <form action="admin-dashboard.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="action-btn accept-btn">Accept</button>
                                </form>
                                <form action="admin-dashboard.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="action-btn reject-btn">Reject</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>