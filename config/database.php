<?php
/**
 * Database Configuration
 * 
 * Provides database connection parameters and establishes
 * a secure connection to the MySQL database.
 */

declare(strict_types=1);

// Database connection parameters
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'user_auth_test');

/**
 * Get database connection
 * 
 * Returns the database connection object or null if connection fails.
 * 
 * @return mysqli|null Database connection object or null on failure
 */
function getDbConnection(): ?mysqli {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            return null;
        }
        
        // Set charset to UTF-8 for proper character encoding
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Close database connection
 * 
 * Explicitly closes the database connection when done.
 */
function closeDbConnection(): void {
    global $conn;
    if ($conn !== null) {
        $conn->close();
        $conn = null;
    }
}

// Initialize database connection
$conn = getDbConnection();

if ($conn === null) {
    die("Database connection failed. Please check your configuration.");
}
