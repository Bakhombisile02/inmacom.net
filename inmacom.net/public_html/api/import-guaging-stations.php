<?php
//connect to mysql db
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

//read the json file contents
$jsondata = file_get_contents('../data/station_ids.geojson');

//convert json object to php associative array
$data = json_decode($jsondata, true);

foreach ($data as $value) {
    foreach ($data['features'] as $ft => $values) {
        $name = $values['properties']['STATION_NA'];
        $code = $values['properties']['STATION__I'];
        $latitude = $values['properties']['Latitude'];
        $longitude = $values['properties']['Longitude'];

        //insert into mysql table
        $sql = "INSERT INTO `station`(`code`, `name`, `latitude`, `longitude`) 
        VALUES ('$code','$name','$latitude','$longitude')";

        if ($results = mysqli_query($con, $sql)) {
        } else {
            $output = array("type" => "failed", "text" => "Failed to import. Error occured!");
            echo json_encode($output);
        }
    }
}


$output = json_encode(array('type' => 'success', 'text' => 'Imported'));
die($output);

mysqli_close($con);
