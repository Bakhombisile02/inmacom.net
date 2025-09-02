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
    echo json_encode(array("error" => "Database connection failed: " . mysqli_connect_error()));
    exit();
}
session_start();

// Query to get flow/gauging stations with their data
$query = "SELECT s.*, fl.value, fl.date, fl.unit 
          FROM station s 
          LEFT JOIN flow_levels fl ON s.code = fl.station_id 
          WHERE s.category = 'Flow Levels' 
          ORDER BY s.code ASC";

if ($results = mysqli_query($con, $query)) {
    $response = array();
    while ($row = mysqli_fetch_assoc($results)) {
        $response[] = array(
            'id' => $row['id'],
            'code' => $row['code'],
            'name' => $row['name'],
            'latitude' => floatval($row['latitude']),
            'longitude' => floatval($row['longitude']),
            'category' => $row['category'],
            'value' => $row['value'],
            'date' => $row['date'],
            'unit' => $row['unit']
        );
    }
    $output = array("type" => "success", "text" => "Successfully got data", "data" => $response);
    echo json_encode($output);
} else {
    $output = array("type" => "failed", "text" => "Error connecting to database: " . mysqli_error($con));
    echo json_encode($output);
}

mysqli_close($con);
?>