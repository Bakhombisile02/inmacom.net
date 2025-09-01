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

    $sql = "SELECT * FROM `external_links`";

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
    $sql = "SELECT * FROM `external_links` WHERE `id` ='$id'";

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
    $sql = "DELETE FROM `external_links` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else if (isset($_POST['edit'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $link_name  = mysqli_real_escape_string($con, $_POST['link_name']);
    $url = mysqli_real_escape_string($con, $_POST['url']);

    $query    = "UPDATE `external_links` SET `link_name`='$link_name',`link_url`='$url' WHERE `id` ='$id'";
    $result   = mysqli_query($con, $query);

    if ($result) {
        $response = array("type" => "success", "text" => "Successfully Added");
        echo json_encode($response);
    } else {
        $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
        die(json_encode($output));
    }
} else {
    if (isset($_POST['savedata'])) {

        $link_name  = mysqli_real_escape_string($con, $_POST['link_name']);
        $url = mysqli_real_escape_string($con, $_POST['url']);

        $query    = "INSERT INTO `external_links`(`link_name`, `link_url`) 
        VALUES ('$link_name','$url')";
        $result   = mysqli_query($con, $query);

        if (!$result) {
            $output = array("type" => "failed", "text" => "Failed to write to database, " . mysqli_error($link));
            die(json_encode($output));
        }

        $response = array("type" => "success", "text" => "Successfully Added");
        echo json_encode($response);
    }
}

mysqli_close($con);
