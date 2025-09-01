<?php
/**
 * INMACOM Security Utilities
 * Contains security functions and utilities
 */

// Prevent direct access
if (!defined('INMACOM_ACCESS')) {
    http_response_code(403);
    die('Access denied');
}

/**
 * Configure secure session settings
 */
function configureSecureSession() {
    // Prevent session fixation attacks
    if (session_status() == PHP_SESSION_NONE) {
        // Configure session settings before starting
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        // Set session name
        session_name('INMACOM_SESSION');
        
        // Start session
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

/**
 * Generate CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Validate and sanitize input
 * @param mixed $input
 * @param string $type
 * @param array $options
 * @return mixed
 * @throws InvalidArgumentException
 */
function validateInput($input, $type = 'string', $options = []) {
    switch ($type) {
        case 'email':
            $clean = filter_var($input, FILTER_VALIDATE_EMAIL);
            if ($clean === false) {
                throw new InvalidArgumentException('Invalid email format');
            }
            return $clean;
            
        case 'int':
            $min = $options['min'] ?? null;
            $max = $options['max'] ?? null;
            $clean = filter_var($input, FILTER_VALIDATE_INT, [
                'options' => array_filter(['min_range' => $min, 'max_range' => $max])
            ]);
            if ($clean === false) {
                throw new InvalidArgumentException('Invalid integer');
            }
            return $clean;
            
        case 'float':
            $clean = filter_var($input, FILTER_VALIDATE_FLOAT);
            if ($clean === false) {
                throw new InvalidArgumentException('Invalid float');
            }
            return $clean;
            
        case 'string':
            $maxLength = $options['max_length'] ?? 255;
            $clean = trim($input);
            if (strlen($clean) > $maxLength) {
                throw new InvalidArgumentException("String too long (max: $maxLength)");
            }
            return htmlspecialchars($clean, ENT_QUOTES, 'UTF-8');
            
        case 'station_code':
            // Validate station codes (alphanumeric + dashes/underscores)
            if (!preg_match('/^[A-Za-z0-9_-]+$/', $input)) {
                throw new InvalidArgumentException('Invalid station code format');
            }
            return $input;
            
        case 'date':
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $input);
            if (!$date) {
                $date = DateTime::createFromFormat('Y-m-d', $input);
                if (!$date) {
                    throw new InvalidArgumentException('Invalid date format');
                }
            }
            return $date->format('Y-m-d H:i:s');
            
        default:
            throw new InvalidArgumentException("Unknown validation type: $type");
    }
}

/**
 * Hash password securely
 * @param string $password
 * @return string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is authenticated
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Check if user has required role
 * @param string|array $requiredRole
 * @return bool
 */
function hasRole($requiredRole) {
    if (!isAuthenticated()) {
        return false;
    }
    
    $userRole = $_SESSION['role'] ?? 'user';
    
    if (is_array($requiredRole)) {
        return in_array($userRole, $requiredRole);
    }
    
    return $userRole === $requiredRole;
}

/**
 * Require authentication or redirect
 * @param string $redirect
 */
function requireAuth($redirect = '/login.php') {
    if (!isAuthenticated()) {
        if (isAjaxRequest()) {
            http_response_code(401);
            echo json_encode(['type' => 'error', 'text' => 'Authentication required']);
            exit;
        } else {
            header("Location: $redirect");
            exit;
        }
    }
}

/**
 * Require specific role or return error
 * @param string|array $requiredRole
 */
function requireRole($requiredRole) {
    requireAuth();
    
    if (!hasRole($requiredRole)) {
        if (isAjaxRequest()) {
            http_response_code(403);
            echo json_encode(['type' => 'error', 'text' => 'Insufficient permissions']);
            exit;
        } else {
            http_response_code(403);
            die('Access denied');
        }
    }
}

/**
 * Check if request is AJAX
 * @return bool
 */
function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Rate limiting implementation
 * @param string $key
 * @param int $limit
 * @param int $window
 * @return bool
 */
function checkRateLimit($key, $limit = 5, $window = 300) {
    $sessionKey = "rate_limit_$key";
    
    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = ['count' => 1, 'start' => time()];
        return true;
    }
    
    $data = $_SESSION[$sessionKey];
    
    // Reset window if expired
    if (time() - $data['start'] > $window) {
        $_SESSION[$sessionKey] = ['count' => 1, 'start' => time()];
        return true;
    }
    
    // Check limit
    if ($data['count'] >= $limit) {
        return false;
    }
    
    // Increment count
    $_SESSION[$sessionKey]['count']++;
    return true;
}

/**
 * Log security events
 * @param string $event
 * @param array $data
 */
function logSecurityEvent($event, $data = []) {
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'user_id' => $_SESSION['user_id'] ?? null,
        'data' => $data
    ];
    
    // Log to file (in production, consider using a proper logging service)
    error_log("SECURITY: " . json_encode($logData));
}

/**
 * Generate secure JSON response
 * @param string $type
 * @param string $text
 * @param mixed $data
 */
function jsonResponse($type, $text, $data = null) {
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    
    $response = ['type' => $type, 'text' => $text];
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}
?>