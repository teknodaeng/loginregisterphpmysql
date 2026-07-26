<?php
/**
 * Logout Handler
 * 
 * Destroys the user session and redirects to login page.
 */

declare(strict_types=1);

// Initialize the session
session_start();

// Unset all session variables
$_SESSION = [];

// Delete the session cookie if cookies are enabled
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
