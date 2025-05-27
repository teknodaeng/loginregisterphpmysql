<?php
// Include database connection
require_once 'db.php';

// Define variables and initialize with empty values
$username = $email = $password = "";
$username_err = $email_err = $password_err = $general_err = $success_msg = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
        // Add email format validation if needed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
        // Add password complexity validation if needed
    }

    // Check for existing user if there are no input errors
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_email);

            // Set parameters
            $param_username = $username;
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Check if username or email already exists
                    $stmt->bind_result($id);
                    $stmt->fetch();
                    // Determine if it's username or email that exists
                    $check_sql = "SELECT username FROM users WHERE username = ?";
                    if($check_stmt = $conn->prepare($check_sql)){
                        $check_stmt->bind_param("s", $param_username);
                        $check_stmt->execute();
                        $check_stmt->store_result();
                        if($check_stmt->num_rows > 0){
                            $username_err = "This username is already taken.";
                        } else {
                            $email_err = "This email is already registered.";
                        }
                        $check_stmt->close();
                    }
                }
            } else {
                $general_err = "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            $stmt->close();
        } else {
            $general_err = "Database error: Could not prepare statement.";
        }
    }

    // Insert new user if all checks pass
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($general_err)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = $hashed_password; // Store hashed password

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $success_msg = "Registration successful. You can now login.";
                // Clear form fields after successful registration
                $username = $email = $password = "";
            } else {
                $general_err = "Something went wrong. Please try again later.";
            }
            // Close statement
            $stmt->close();
        }  else {
            $general_err = "Database error: Could not prepare statement for insertion.";
        }
    }
    // Close connection
    // $conn->close(); // It's often better to let PHP close it at the end of script execution unless specifically needed.
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
                <?php if (!empty($username_err)): ?>
                    <span class="error"><?php echo $username_err; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
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
