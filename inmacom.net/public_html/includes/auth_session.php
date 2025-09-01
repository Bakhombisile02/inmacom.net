<?php
require_once __DIR__ . '/secure_init.php';

// Check authentication
requireAuth();

// Optional: Check session timeout (30 minutes)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_destroy();
    if (isAjaxRequest()) {
        jsonResponse('error', 'Session expired. Please log in again.');
    } else {
        header("Location: /login.php");
        exit();
    }
}

// Update last activity time
$_SESSION['login_time'] = time();
?>