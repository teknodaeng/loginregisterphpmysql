<?php
// Start the session at the very beginning
session_start();

// Check if the user is logged in.
// The 'loggedin' session variable is set in login.php upon successful login.
// Also check for 'username' to be safe, though 'loggedin' should be the primary check.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || empty($_SESSION["username"])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit; // Important to prevent further script execution
}

// If the user is logged in, display the dashboard content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Removed inline styles -->
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <p>This is your dashboard. You have successfully logged in.</p>
        <p>Here, you can manage your account settings, view your activity, or access other features of the application.</p>
        
        <!-- Placeholder for more dashboard content -->
        <div>
            <h2>Your Profile</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION["user_id"]); ?></p>
            <!-- Add more profile information here if available and needed -->
        </div>

        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
