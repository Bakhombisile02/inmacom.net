<?php
/**
 * INMACOM Security Bootstrap
 * Main security configuration loader
 */

// Define access constant for includes
define('INMACOM_ACCESS', true);

// Load configuration files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

// Configure secure session
configureSecureSession();

// Set secure headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// In production, add Content Security Policy
if (getenv('INMACOM_ENV') === 'production') {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'");
}

// Error reporting settings
if (getenv('INMACOM_ENV') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
?>