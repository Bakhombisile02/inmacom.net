<?php

// Database connection details
$host = "localhost";
$username = "inmacom_db";
$password = "AccessInmacom";
$database = "inmacom_db";

// Establish connection
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    // If connection fails, show an error message in a pop-up
    echo "<script>alert('Database connection failed: " . addslashes(mysqli_connect_error()) . "');</script>";
    exit(); // Stop further execution
}

// Optional: Remove the success alert for cleaner output
// echo "<script>alert('Database connected successfully!');</script>";

?>