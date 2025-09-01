<?php
/**
 * INMACOM Database Configuration
 * Secure database configuration with environment-based settings
 */

// Prevent direct access
if (!defined('INMACOM_ACCESS')) {
    http_response_code(403);
    die('Access denied');
}

// Environment configuration
$environment = getenv('INMACOM_ENV') ?: 'development';

// Database configuration by environment
$db_config = [
    'development' => [
        'host' => 'localhost',
        'database' => 'inmacom_db',
        'username' => 'inmacom_db', 
        'password' => 'AccessInmacom',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    'production' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'database' => getenv('DB_NAME') ?: 'inmacom_db',
        'username' => getenv('DB_USER') ?: 'inmacom_db',
        'password' => getenv('DB_PASS') ?: 'AccessInmacom',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ]
];

// Get current environment config
if (!isset($db_config[$environment])) {
    throw new Exception("Unknown environment: $environment");
}

define('DB_CONFIG', $db_config[$environment]);

/**
 * Get secure database connection using PDO
 * @return PDO
 * @throws Exception
 */
function getSecureDbConnection() {
    try {
        $config = DB_CONFIG;
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        
        $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        
        return $pdo;
    } catch (PDOException $e) {
        // Log error securely (don't expose details)
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}

/**
 * Get MySQLi connection (for legacy compatibility)
 * @return mysqli
 * @throws Exception
 */
function getSecureDbConnectionMySQLi() {
    try {
        $config = DB_CONFIG;
        
        $mysqli = new mysqli(
            $config['host'], 
            $config['username'], 
            $config['password'], 
            $config['database']
        );
        
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }
        
        $mysqli->set_charset($config['charset']);
        
        return $mysqli;
    } catch (Exception $e) {
        // Log error securely
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}
?>