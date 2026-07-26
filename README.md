# User Authentication System - Refactored

This is a refactored version of a PHP user authentication system with improved code organization, security, and maintainability.

## Project Structure

```
/workspace
├── config/
│   └── database.php          # Database configuration and connection management
├── includes/
│   └── auth.php              # Authentication helper functions
├── repositories/
│   └── UserRepository.php    # User data access layer
├── dashboard.php             # User dashboard (protected page)
├── login.php                 # Login page
├── logout.php                # Logout handler
├── register.php              # User registration page
├── style.css                 # Stylesheet
├── schema.sql                # Database schema
└── db.php                    # Legacy database file (deprecated)
```

## Key Improvements

### 1. Separation of Concerns
- **Database Layer** (`config/database.php`): Centralized database configuration with connection pooling
- **Authentication Helpers** (`includes/auth.php`): Reusable validation and session management functions
- **Data Access Layer** (`repositories/UserRepository.php`): Repository pattern for database operations

### 2. Code Quality
- Added `declare(strict_types=1)` for type safety
- Consistent single quotes for strings
- Proper function documentation with PHPDoc comments
- Improved error handling and logging

### 3. Security Enhancements
- Enhanced input validation with dedicated functions
- CSRF token generation and verification utilities
- Proper output sanitization with `htmlspecialchars()` using `ENT_QUOTES` and `UTF-8`
- Password hashing with `password_hash()` and `PASSWORD_DEFAULT`
- Secure session management

### 4. Validation Functions
- `validateUsername()`: Checks length, format, and allowed characters
- `validateEmail()`: Validates email format using `filter_var()`
- `validatePassword()`: Enforces minimum password length (8 characters)

### 5. Session Management
- `isLoggedIn()`: Check if user is authenticated
- `requireLogin()`: Protect pages requiring authentication
- `getCurrentUserId()`, `getCurrentUsername()`: Access session data safely

### 6. Repository Pattern
- `findUserByUsername()`: Retrieve user by username
- `findUserByEmail()`: Retrieve user by email
- `usernameExists()`, `emailExists()`: Check for existing users
- `createUser()`: Create new user with proper error handling
- `verifyCredentials()`: Authenticate user credentials
- `getUserById()`: Retrieve user by ID

## Usage

### Database Setup
Run the SQL schema to create the users table:
```bash
mysql -u root -p user_auth_test < schema.sql
```

### Configuration
Update database credentials in `config/database.php`:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');
```

### Adding Authentication to New Pages
```php
<?php
require_once 'includes/auth.php';

// Protect the page
requireLogin();

// Access user data
$username = getCurrentUsername();
?>
```

## Files Modified

### Core Files
- **login.php**: Simplified logic using repository pattern and validation helpers
- **register.php**: Cleaner validation and user creation flow
- **dashboard.php**: Uses authentication helpers instead of direct session checks
- **logout.php**: Modernized with strict types and better formatting

### New Files
- **config/database.php**: Database configuration with connection management
- **includes/auth.php**: Authentication utility functions
- **repositories/UserRepository.php**: Data access layer for user operations

### Deprecated Files
- **db.php**: Legacy database file (kept for backward compatibility but not used in refactored code)

## Best Practices Implemented

1. **DRY Principle**: Eliminated code duplication through reusable functions
2. **Single Responsibility**: Each file has one clear purpose
3. **Type Safety**: Strict typing enabled throughout
4. **Error Handling**: Proper error messages and logging
5. **Security**: Input validation, output encoding, prepared statements
6. **Maintainability**: Clear documentation and consistent coding style

## Future Enhancements

Consider adding:
- Password reset functionality
- Email verification
- Two-factor authentication
- Account lockout after failed attempts
- Remember me functionality
- Profile editing page
- Admin panel
