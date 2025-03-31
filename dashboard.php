<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define the sections (since we're not using a database)
$sections = [
    ['name' => 'Faculty of Computing', 'icon' => 'computing-icon.png'],
    ['name' => 'Faculty of Management', 'icon' => 'management-icon.png'],
    ['name' => 'Finance Department', 'icon' => 'finance-icon.png'],
    ['name' => 'Marketing Department', 'icon' => 'marketing-icon.png'],
    ['name' => 'Registrar Office', 'icon' => 'registrar-icon.png'],
    ['name' => 'Examination Department', 'icon' => 'examination-icon.png'],
    ['name' => 'Library', 'icon' => 'library-icon.png'],
    ['name' => 'Saegis Study Abroad', 'icon' => 'study-abroad-icon.png']
];

// Determine the view (grid or list)
$view = isset($_GET['view']) && $_GET['view'] === 'list' ? 'list' : 'grid';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>Dashboard - SAEGIS Campus Address Book</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="manifest" href="manifest.json">
    <link rel="icon" href="images/logo.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="images/logo.png" alt="SAEGIS Campus Logo" class="logo">
            <h2>SAEGIS CAMPUS</h2>
            <p>Sections</p>
            <a href="logout.php" class="link">Logout</a>
            <a href="#" class="menu-icon">
                <img src="images/menu-icon.png" alt="Menu">
            </a>
        </div>

        <div class="view-toggle">
            <a href="dashboard.php?view=grid" class="toggle-btn <?php echo $view === 'grid' ? 'active' : ''; ?>">Grid View</a>
            <a href="dashboard.php?view=list" class="toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>">List View</a>
        </div>

        <?php if ($view === 'grid'): ?>
            <div class="grid-view">
                <?php foreach ($sections as $section): ?>
                    <div class="card">
                        <img src="images/<?php echo $section['icon']; ?>" alt="<?php echo $section['name']; ?>">
                        <p><?php echo $section['name']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="list-view">
                <?php foreach ($sections as $section): ?>
                    <div class="list-item">
                        <img src="images/<?php echo $section['icon']; ?>" alt="<?php echo $section['name']; ?>">
                        <p><?php echo $section['name']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/app.js"></script>
</body>
</html>