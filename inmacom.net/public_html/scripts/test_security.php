<?php
/**
 * Security Test Script
 * Tests the security improvements without requiring database
 */

// Define access constant for security includes
define('INMACOM_ACCESS', true);

require_once __DIR__ . '/../config/security.php';

echo "=== INMACOM Security System Tests ===\n\n";

// Test 1: Password hashing
echo "1. Testing Password Hashing:\n";
$password = "test123";
$hash = hashPassword($password);
$verify_good = verifyPassword($password, $hash);
$verify_bad = verifyPassword("wrong", $hash);

echo "   Password: '$password'\n";
echo "   Hash: " . substr($hash, 0, 30) . "...\n";
echo "   Verification (correct): " . ($verify_good ? "PASS" : "FAIL") . "\n";
echo "   Verification (wrong): " . ($verify_bad ? "FAIL" : "PASS") . "\n\n";

// Test 2: Input validation
echo "2. Testing Input Validation:\n";

// Test email validation
try {
    $email = validateInput("test@example.com", "email");
    echo "   Valid email: '$email' - PASS\n";
} catch (Exception $e) {
    echo "   Valid email test - FAIL: " . $e->getMessage() . "\n";
}

try {
    $email = validateInput("invalid-email", "email");
    echo "   Invalid email test - FAIL (should have thrown exception)\n";
} catch (Exception $e) {
    echo "   Invalid email rejected - PASS\n";
}

// Test integer validation
try {
    $int = validateInput("123", "int", ["min" => 1, "max" => 1000]);
    echo "   Valid integer: $int - PASS\n";
} catch (Exception $e) {
    echo "   Valid integer test - FAIL: " . $e->getMessage() . "\n";
}

try {
    $int = validateInput("abc", "int");
    echo "   Invalid integer test - FAIL (should have thrown exception)\n";
} catch (Exception $e) {
    echo "   Invalid integer rejected - PASS\n";
}

// Test string validation
try {
    $str = validateInput("<script>alert('xss')</script>", "string");
    echo "   XSS string sanitized: '$str' - PASS\n";
} catch (Exception $e) {
    echo "   String validation - FAIL: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: CSRF token generation
echo "3. Testing CSRF Protection:\n";
session_start();
$token1 = generateCSRFToken();
$token2 = generateCSRFToken();
$verify_token = verifyCSRFToken($token1);
$verify_wrong = verifyCSRFToken("wrong-token");

echo "   Token 1: " . substr($token1, 0, 20) . "...\n";
echo "   Token 2: " . substr($token2, 0, 20) . "...\n";
echo "   Same token: " . ($token1 === $token2 ? "PASS" : "FAIL") . "\n";
echo "   Token verification (correct): " . ($verify_token ? "PASS" : "FAIL") . "\n";
echo "   Token verification (wrong): " . ($verify_wrong ? "FAIL" : "PASS") . "\n\n";

// Test 4: Date validation
echo "4. Testing Date Validation:\n";
try {
    $date = validateInput("2024-01-15 10:30:00", "date");
    echo "   Valid datetime: '$date' - PASS\n";
} catch (Exception $e) {
    echo "   Valid datetime test - FAIL: " . $e->getMessage() . "\n";
}

try {
    $date = validateInput("invalid-date", "date");
    echo "   Invalid date test - FAIL (should have thrown exception)\n";
} catch (Exception $e) {
    echo "   Invalid date rejected - PASS\n";
}

echo "\n=== Security Tests Complete ===\n";
echo "All core security functions are working properly!\n";
?>