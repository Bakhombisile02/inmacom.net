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

if (isset($_SESSION["user_id"]))
    $user_id = $_SESSION["user_id"];

if (isset($_POST['getdata'])) {

    $sql = "SELECT * FROM `station`";

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
    $sql = "SELECT * FROM `station` WHERE `id` ='$id'";

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
    $sql = "DELETE FROM `station` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else if (isset($_POST['edit'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $code = mysqli_real_escape_string($con, $_POST['code']);
    $name   = mysqli_real_escape_string($con, $_POST['name']);
    $latitude    = mysqli_real_escape_string($con, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($con, $_POST['longitude']);
    //$category = $_POST['category'];
    $query    = "UPDATE `station` SET `code`='$code',`name`='$name',`latitude`='$latitude',`longitude`='$longitude' WHERE `id` ='$id'";
    $result   = mysqli_query($con, $query);

    if ($result) {
        $response = array("type" => "success", "text" => "Successfully Updated");
        echo json_encode($response);
    } else {
        $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
        die(json_encode($output));
    }
} else {
    if (isset($_POST['code'])) {
        
        $code = mysqli_real_escape_string($con, $_POST['code']);
        $name   = mysqli_real_escape_string($con, $_POST['name']);
        $latitude    = mysqli_real_escape_string($con, $_POST['latitude']);
        $longitude = mysqli_real_escape_string($con, $_POST['longitude']);
        $category = $_POST['category'];

        foreach ($category as $item) {
            $query    = "INSERT INTO `station`(`code`, `name`, `latitude`, `longitude`, `category`) 
            VALUES ('$code','$name','$latitude','$longitude','$item')";
            $result   = mysqli_query($con, $query);

            $query2 = "INSERT INTO `user_stations`(`user_id`, `station_id`) 
                VALUES ('$user_id','$code')";
            $result2   = mysqli_query($con, $query2);

            if (!$result) {
                $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
                die(json_encode($output));
            }
        }

        $response = array("type" => "success", "text" => "Successfully Added");
        echo json_encode($response);
    }
}

mysqli_close($con);
