<?php
/**
 * Dashboard Page
 * 
 * Displays user dashboard after successful login.
 */

declare(strict_types=1);

// Include required files
require_once 'includes/auth.php';

// Require authentication - redirects if not logged in
requireLogin();

// Get user information
$username = getCurrentUsername();
$userId = getCurrentUserId();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h1>
        <p>This is your dashboard. You have successfully logged in.</p>
        <p>Here, you can manage your account settings, view your activity, or access other features of the application.</p>
        
        <div>
            <h2>Your Profile</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($userId, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
