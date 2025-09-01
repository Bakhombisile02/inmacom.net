<?php
require_once '../includes/secure_init.php';

// Rate limiting for login attempts
if (!checkRateLimit('login', 5, 300)) {
    logSecurityEvent('login_rate_limit_exceeded', ['ip' => $_SERVER['REMOTE_ADDR']]);
    jsonResponse('failed', 'Too many login attempts. Please try again later.');
}

// Require POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    jsonResponse('failed', 'Method not allowed');
}

// Verify CSRF token for additional security (optional for login)
if (isset($_POST['csrf_token']) && !verifyCSRFToken($_POST['csrf_token'])) {
    logSecurityEvent('csrf_token_invalid', ['action' => 'login']);
    jsonResponse('failed', 'Security token validation failed');
}

try {
    // Validate input
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        jsonResponse('failed', 'Email and password are required');
    }
    
    $email = validateInput($_POST['email'], 'email');
    $password = $_POST['password']; // Don't validate password content, just check length
    
    if (strlen($password) < 1) {
        jsonResponse('failed', 'Password is required');
    }
    
    if (strlen($password) > 255) {
        jsonResponse('failed', 'Password too long');
    }
    
    // Get database connection
    $pdo = getSecureDbConnection();
    
    // Use prepared statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password, role, organization, email_verified FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && verifyPassword($password, $user['password'])) {
        // Check if email is verified
        if (!$user['email_verified']) {
            logSecurityEvent('login_unverified_email', ['email' => $email]);
            jsonResponse('failed', 'Please verify your email address before logging in');
        }
        
        // Successful login
        session_regenerate_id(true); // Prevent session fixation
        
        $_SESSION['username'] = $user['first_name'] . " " . $user['last_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['organization'] = $user['organization'] ?? '';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['login_time'] = time();
        
        // Log successful login
        logSecurityEvent('login_successful', [
            'user_id' => $user['id'],
            'email' => $email,
            'role' => $user['role']
        ]);
        
        jsonResponse('success', 'Login successful', ['role' => $user['role']]);
    } else {
        // Invalid credentials
        logSecurityEvent('login_failed', [
            'email' => $email,
            'reason' => $user ? 'invalid_password' : 'user_not_found'
        ]);
        
        // Generic error message to prevent user enumeration
        jsonResponse('failed', 'Invalid email or password');
    }
    
} catch (Exception $e) {
    // Log error
    error_log("Login error: " . $e->getMessage());
    logSecurityEvent('login_error', ['error' => $e->getMessage()]);
    
    jsonResponse('failed', 'An error occurred during login');
}