<?php
// Database connection parameters
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'user_auth_test');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // If connection fails, display an error message and terminate the script
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Print a success message (can be removed in production)
// echo "Connected successfully";

// The connection object $conn is now available for use in other scripts
?>
