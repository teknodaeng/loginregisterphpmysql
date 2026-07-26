<?php
/**
 * User Repository
 * 
 * Handles database operations for user management.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

/**
 * Find user by username
 * 
 * @param string $username The username to search for
 * @return array|null User data or null if not found
 */
function findUserByUsername(string $username): ?array {
    $conn = getDbConnection();
    if ($conn === null) {
        return null;
    }
    
    $sql = "SELECT id, username, email, password, created_at FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $stmt->close();
                return $row;
            }
        }
        $stmt->close();
    }
    
    return null;
}

/**
 * Find user by email
 * 
 * @param string $email The email to search for
 * @return array|null User data or null if not found
 */
function findUserByEmail(string $email): ?array {
    $conn = getDbConnection();
    if ($conn === null) {
        return null;
    }
    
    $sql = "SELECT id, username, email, password, created_at FROM users WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $stmt->close();
                return $row;
            }
        }
        $stmt->close();
    }
    
    return null;
}

/**
 * Check if username exists
 * 
 * @param string $username The username to check
 * @return bool True if username exists, false otherwise
 */
function usernameExists(string $username): bool {
    return findUserByUsername($username) !== null;
}

/**
 * Check if email exists
 * 
 * @param string $email The email to check
 * @return bool True if email exists, false otherwise
 */
function emailExists(string $email): bool {
    return findUserByEmail($email) !== null;
}

/**
 * Create new user
 * 
 * @param string $username The username
 * @param string $email The email
 * @param string $password The hashed password
 * @return array Result array with success status and message
 */
function createUser(string $username, string $email, string $password): array {
    $conn = getDbConnection();
    if ($conn === null) {
        return ['success' => false, 'message' => 'Database connection failed.'];
    }
    
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $stmt->close();
            return ['success' => true, 'user_id' => $userId, 'message' => 'User created successfully.'];
        }
        $stmt->close();
    }
    
    return ['success' => false, 'message' => 'Failed to create user.'];
}

/**
 * Verify user credentials
 * 
 * @param string $username The username
 * @param string $password The plain text password
 * @return array|null User data without password if successful, null otherwise
 */
function verifyCredentials(string $username, string $password): ?array {
    $user = findUserByUsername($username);
    
    if ($user === null) {
        return null;
    }
    
    if (password_verify($password, $user['password'])) {
        // Remove password from returned data
        unset($user['password']);
        return $user;
    }
    
    return null;
}

/**
 * Get user by ID
 * 
 * @param int $userId The user ID
 * @return array|null User data or null if not found
 */
function getUserById(int $userId): ?array {
    $conn = getDbConnection();
    if ($conn === null) {
        return null;
    }
    
    $sql = "SELECT id, username, email, created_at FROM users WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $stmt->close();
                return $row;
            }
        }
        $stmt->close();
    }
    
    return null;
}
