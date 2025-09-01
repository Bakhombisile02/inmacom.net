<?php
// Database connection details
$host = "localhost";
$username = "u550237388_inmacomadmin";
$password = "AccessInmacom2046";
$database = "u550237388_inmacom_db1";

// Establish connection
$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    // If connection fails, return an error
    echo json_encode(array("error" => "Database connection failed: " . mysqli_connect_error()));
    exit(); // Stop further execution if connection fails
}
// When form submitted, insert values into the database.

if (isset($_POST['getdata'])) {

    $sql = "SELECT water_quality.*, station.name FROM `water_quality` 
    INNER JOIN station ON water_quality.station_id = station.code";

    if ($results = mysqli_query($con, $sql)) {

        $response = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $response[] = array('id'=>$row['id'], 'name' => $row['name'], 'date' => $row['date'], 'parameter' =>$row['parameter'], 'value' => $row['value'], 'unit' => htmlspecialchars($row['unit'], ENT_QUOTES, 'UTF-8'));
        }
        $output = array("type" => "successful", "text" => "successully got data", "data" => $response);
        echo json_encode($output);
    } else {
        die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
    }
} else if (isset($_POST['delete'])) {

    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "DELETE FROM `water_quality` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else {
    if (isset($_REQUEST['station'])) {
        $station = mysqli_real_escape_string($con, $_REQUEST['station']);
        $value   = mysqli_real_escape_string($con, $_REQUEST['value']);
        $unit    = mysqli_real_escape_string($con, $_REQUEST['unit']);
        $date = mysqli_real_escape_string($con, $_REQUEST['date_time']);
        $parameter = mysqli_real_escape_string($con, $_REQUEST['parameter']);

        $query    = "INSERT INTO `water_quality`(`station_id`, `date`, `parameter` ,`value`, `unit`) 
            VALUES ('$station', '$date', $parameter, '$value','$unit')";
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $result   = mysqli_query($con, $query);

        if ($result) {
            $response = array("type" => "success", "text" => "Successfully Saved");
            echo json_encode($response);
        } else {
            $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
            echo json_encode($output);
        }
    }
}
mysqli_close($con);