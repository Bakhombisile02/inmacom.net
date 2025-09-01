<?php
session_start();
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
$dataset = mysqli_escape_string($con, $_POST['dataset']);
$station_id = mysqli_escape_string($con, $_POST['station_id']);

$data = '';
$date = '';

$query = "SELECT * FROM ". $dataset ." WHERE `station_id` = '$station_id' ORDER BY `flow_levels`.`date` ASC;";

if ($results = mysqli_query($con, $query)) {

    $response = array();
    while ($row = mysqli_fetch_assoc($results)) {
        $response[] = $row;
    }
    $output = array("type" => "successful", "text" => "successully got data", "data" => $response);
    echo json_encode($output);
} else {
    die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
}

mysqli_close($con);