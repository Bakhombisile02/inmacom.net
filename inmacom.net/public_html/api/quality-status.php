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
    // If connection fails, return an error
    echo json_encode(array("error" => "Database connection failed: " . mysqli_connect_error()));
    exit(); // Stop further execution if connection fails
}
session_start();

$query = "SELECT * FROM `view_water_quality`";
if ($results = mysqli_query($con, $query)) {
    $response = array();
    while ($row = mysqli_fetch_assoc($results)) {
        $response[] = $row;
    }
    $output = array("type" => "success", "text" => "successfully got data", "quality" => $response);
    echo json_encode($output);
} else {
    $output = array("type" => "failed", "text" => "Error connecting to database");
    echo json_encode($output);
}

mysqli_close($con);
?>