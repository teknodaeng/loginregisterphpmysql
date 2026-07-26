<?php
/**
 * Login Page
 * 
 * Handles user authentication and login functionality.
 */

declare(strict_types=1);

// Start the session at the very beginning
session_start();

// Include required files
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'repositories/UserRepository.php';

// Define variables and initialize with empty values
$username = $password = '';
$username_err = $password_err = $login_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    $usernameValidation = validateUsername($_POST['username'] ?? '');
    if (!$usernameValidation['valid']) {
        $username_err = $usernameValidation['error'];
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    $passwordValidation = validatePassword($_POST['password'] ?? '');
    if (!$passwordValidation['valid']) {
        $password_err = $passwordValidation['error'];
    } else {
        $password = trim($_POST['password']);
    }

    // Check credentials if there are no input errors
    if (empty($username_err) && empty($password_err)) {
        // Verify user credentials using repository
        $user = verifyCredentials($username, $password);
        
        if ($user !== null) {
            // Password is correct, store data in session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect user to dashboard page
            header('Location: dashboard.php');
            exit;
        } else {
            // Invalid credentials
            $login_err = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Removed inline styles and the duplicated link comment -->
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php if (!empty($login_err)): ?>
            <div class="alert"><?php echo $login_err; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if (!empty($username_err)): ?>
                    <span class="error"><?php echo $username_err; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <?php if (!empty($password_err)): ?>
                    <span class="error"><?php echo $password_err; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
