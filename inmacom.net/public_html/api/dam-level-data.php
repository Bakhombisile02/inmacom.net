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

if (isset($_SESSION["user_id"]))
    $user_id = $_SESSION["user_id"];
if (isset($_POST['getdata'])) {

    if ($_POST['getdata'] == 'datamanager') {
        $sql = "SELECT user_stations.user_id, station.code, station.name, station.category, dam_levels.* 
        FROM station 
        INNER JOIN user_stations ON station.code = user_stations.station_id 
        INNER JOIN dam_levels ON dam_levels.station_id = user_stations.station_id 
        WHERE user_id = '$user_id';";
    } else {
        $sql = "SELECT dam_levels.*, station.name FROM `dam_levels` 
        INNER JOIN station ON dam_levels.station_id = station.code";
    }

    if ($results = mysqli_query($con, $sql)) {

        $response = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $response[] = $row;
        }
        $output = array("type" => "successful", "text" => "successully got data", "data" => $response);
        echo json_encode($output);
    } else {
        die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
    }
} else if (isset($_POST['delete'])) {

    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "DELETE FROM `dam_levels` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else if (isset($_REQUEST['station'])) {

    $station = mysqli_real_escape_string($con, $_REQUEST['station']);
    $fsc = mysqli_real_escape_string($con, $_REQUEST['fsc']);
    $storage = mysqli_real_escape_string($con, $_REQUEST['storage']);
    $date = date_create(mysqli_real_escape_string($con, $_REQUEST['date_time']));

    $formated_date = date_format($date, 'Y-m-d H:i:s');

    $query    = "INSERT INTO `dam_levels`(`station_id`, `fsc`, `value`, `date`) 
        VALUES ('$station','$fsc','$storage', '$formated_date')";

    $result   = mysqli_query($con, $query);

    if ($result) {
        $response = array("type" => "success", "text" => "Successfully Saved");
        echo json_encode($response);
    } else {
        $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
        echo json_encode($output);
    }
} else if (isset($_POST['getrecord'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "SELECT * FROM `view_dam_levels` WHERE `dam_levels_id` ='$id'";

    if ($results = mysqli_query($con, $sql)) {

        $response = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $response[] = $row;
        }
        $output = array("type" => "successful", "text" => "successully got data", "data" => $response);
        echo json_encode($output);
    } else {
        die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
    }
} else if (isset($_REQUEST['updaterecord'])) {

    $station = mysqli_real_escape_string($con, $_REQUEST['station_id']);
    $fsc   = mysqli_real_escape_string($con, $_REQUEST['fsc']);
    $storage    = mysqli_real_escape_string($con, $_REQUEST['storage']);
    $id    = mysqli_real_escape_string($con, $_REQUEST['id']);

    $query    = "UPDATE `dam_levels` 
    SET `station_id`='$station',`fsc`='$fsc',`value`='$storage' 
    WHERE id = '$id'";

    $result   = mysqli_query($con, $query);

    if ($result) {
        $response = array("type" => "success", "text" => "Successfully Saved");
        echo json_encode($response);
    } else {
        $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
        echo json_encode($output);
    }
}
mysqli_close($con);
