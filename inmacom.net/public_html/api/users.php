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

if (isset($_POST['getdata'])) {

    $sql = "SELECT * FROM `users`";

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
} else if (isset($_POST['getrecord'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "SELECT * FROM `users` WHERE `id` ='$id'";

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
    $sql = "DELETE FROM `users` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else if (isset($_POST['assign'])) {

    $user_id = mysqli_real_escape_string($con, $_REQUEST['user_id']);
    $station_id = $_REQUEST['station_id'];
    foreach ($station_id as $item) {
        $query    = "INSERT INTO `user_stations`(`user_id`, `station_id`) 
        VALUES ('$user_id','$item')";
        $result   = mysqli_query($con, $query);
        if (!$result) {
            $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
            echo json_encode($output);
        }
    }
    $response = array("type" => "success", "text" => "Successfully Added");
    echo json_encode($response);
} else if (isset($_POST['assigned'])) {

    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "SELECT user_stations.id AS usid, station.*, user_stations.user_id FROM `user_stations` 
    INNER JOIN station 
    ON user_stations.station_id = station.code
    WHERE `user_id` ='$id'";

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
} else if (isset($_POST['remove'])) {

    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "DELETE FROM `user_stations` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else if (isset($_POST['edit'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $firstname   = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastname    = mysqli_real_escape_string($con, $_POST['last_name']);
    $organization = mysqli_real_escape_string($con, $_POST['organization']);
    $telephone = mysqli_real_escape_string($con, $_POST['telephone']);
    
    $query    = "UPDATE `users` SET `first_name`='$firstname',`last_name`='$lastname',`telephone`='$telephone',`country`='$country',`organization`='$organization' WHERE `id` ='$id'";
    $result   = mysqli_query($con, $query);

    if ($result) {
        $response = array("type" => "success", "text" => "Successfully Updated");
        echo json_encode($response);
    } else {
        $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
        die(json_encode($output));
    }
}

mysqli_close($con);
