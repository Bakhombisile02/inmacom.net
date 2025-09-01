<?php
/**
 * Password Migration Script
 * This script updates existing plaintext passwords to secure hashed versions
 * Run this ONCE after implementing the new security system
 */

require_once __DIR__ . '/../includes/secure_init.php';

try {
    $pdo = getSecureDbConnection();
    
    // Check if passwords are already hashed (look for password_hash pattern)
    $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE password NOT LIKE '$2y$%' LIMIT 5");
    $stmt->execute();
    $unhashed_users = $stmt->fetchAll();
    
    if (empty($unhashed_users)) {
        echo "All passwords are already hashed.\n";
        exit(0);
    }
    
    echo "Found " . count($unhashed_users) . " users with unhashed passwords.\n";
    echo "IMPORTANT: This will update existing plaintext passwords to hashed versions.\n";
    echo "Make sure users know their current passwords, as they cannot be recovered.\n\n";
    
    // In a real migration, you might want to prompt for confirmation
    // For demo purposes, we'll proceed with known test passwords
    
    $test_passwords = [
        'admin@inmacom.test' => 'admin123',
        'datamanager@inmacom.test' => 'data123',
        'user@inmacom.test' => 'user123'
    ];
    
    $updated = 0;
    foreach ($unhashed_users as $user) {
        // For known test users, use predefined passwords
        if (isset($test_passwords[$user['email']])) {
            $new_password = $test_passwords[$user['email']];
        } else {
            // For unknown users, prompt for their current password
            echo "Enter current password for {$user['email']}: ";
            $handle = fopen("php://stdin", "r");
            $new_password = trim(fgets($handle));
            fclose($handle);
            
            if (empty($new_password)) {
                echo "Skipping {$user['email']} - no password provided.\n";
                continue;
            }
        }
        
        // Hash the password
        $hashed_password = hashPassword($new_password);
        
        // Update the database
        $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($update_stmt->execute([$hashed_password, $user['id']])) {
            echo "Updated password for {$user['email']}\n";
            $updated++;
        } else {
            echo "Failed to update password for {$user['email']}\n";
        }
    }
    
    echo "\nPassword migration completed. Updated $updated passwords.\n";
    echo "Users can now log in with their existing passwords using the secure authentication system.\n";
    
} catch (Exception $e) {
    echo "Error during password migration: " . $e->getMessage() . "\n";
    exit(1);
}
?>