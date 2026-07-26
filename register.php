<?php
/**
 * Register Page
 * 
 * Handles user registration functionality.
 */

declare(strict_types=1);

// Include required files
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'repositories/UserRepository.php';

// Define variables and initialize with empty values
$username = $email = $password = '';
$username_err = $email_err = $password_err = $general_err = $success_msg = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    $usernameValidation = validateUsername($_POST['username'] ?? '');
    if (!$usernameValidation['valid']) {
        $username_err = $usernameValidation['error'];
    } else {
        $username = trim($_POST['username']);
    }

    // Validate email
    $emailValidation = validateEmail($_POST['email'] ?? '');
    if (!$emailValidation['valid']) {
        $email_err = $emailValidation['error'];
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    $passwordValidation = validatePassword($_POST['password'] ?? '');
    if (!$passwordValidation['valid']) {
        $password_err = $passwordValidation['error'];
    } else {
        $password = trim($_POST['password']);
    }

    // Check for existing user if there are no input errors
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        // Check if username already exists
        if (usernameExists($username)) {
            $username_err = 'This username is already taken.';
        }
        
        // Check if email already exists
        if (emailExists($email)) {
            $email_err = 'This email is already registered.';
        }
    }

    // Insert new user if all checks pass
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($general_err)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create user using repository
        $result = createUser($username, $email, $hashed_password);
        
        if ($result['success']) {
            $success_msg = 'Registration successful. You can now login.';
            // Clear form fields after successful registration
            $username = $email = $password = '';
        } else {
            $general_err = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Removed inline styles -->
</head>
<body>
    <div class="wrapper">
        <h2>Register</h2>
        <p>Please fill this form to create an account.</p>

        <?php if (!empty($general_err)): ?>
            <div class="form-group error"><?php echo $general_err; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_msg)): ?>
            <div class="form-group success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if (!empty($username_err)): ?>
                    <span class="error"><?php echo $username_err; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if (!empty($email_err)): ?>
                    <span class="error"><?php echo $email_err; ?></span>
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
                <input type="submit" name="submit" class="btn" value="Register">
            </div>
        </form>
    </div>
</body>
</html>
