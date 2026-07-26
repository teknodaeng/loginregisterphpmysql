<?php
/**
 * Authentication Helper Functions
 * 
 * Provides utility functions for user authentication,
 * validation, and session management.
 */

declare(strict_types=1);

/**
 * Validate username
 * 
 * @param string $username The username to validate
 * @return array Array containing validation result and error message
 */
function validateUsername(string $username): array {
    if (empty(trim($username))) {
        return ['valid' => false, 'error' => 'Please enter a username.'];
    }
    
    if (strlen(trim($username)) < 3) {
        return ['valid' => false, 'error' => 'Username must be at least 3 characters long.'];
    }
    
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return ['valid' => false, 'error' => 'Username can only contain letters, numbers, and underscores.'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Validate email
 * 
 * @param string $email The email to validate
 * @return array Array containing validation result and error message
 */
function validateEmail(string $email): array {
    if (empty(trim($email))) {
        return ['valid' => false, 'error' => 'Please enter an email.'];
    }
    
    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'error' => 'Invalid email format.'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Validate password
 * 
 * @param string $password The password to validate
 * @return array Array containing validation result and error message
 */
function validatePassword(string $password): array {
    if (empty(trim($password))) {
        return ['valid' => false, 'error' => 'Please enter a password.'];
    }
    
    if (strlen(trim($password)) < 8) {
        return ['valid' => false, 'error' => 'Password must be at least 8 characters long.'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Require login - redirect to login page if not authenticated
 */
function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isLoggedIn() || empty($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Get current user ID
 * 
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId(): ?int {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current username
 * 
 * @return string|null Username or null if not logged in
 */
function getCurrentUsername(): ?string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return $_SESSION['username'] ?? null;
}

/**
 * Sanitize output for HTML display
 * 
 * @param string $data The data to sanitize
 * @return string Sanitized data
 */
function sanitizeOutput(string $data): string {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * 
 * @return string Generated CSRF token
 */
function generateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token The token to verify
 * @return bool True if token is valid, false otherwise
 */
function verifyCsrfToken(string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
