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

if (isset($_POST['getdata'])) {
    if ($_POST['getdata'] == 'All') {
        $sql = "SELECT * FROM documents";
    } else {
        $category = mysqli_escape_string($con, $_POST['getdata']);

        $sql = "SELECT * FROM `documents` WHERE `category` = '$category';";
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
} else if (isset($_POST['file_type'])) {
    if ($_POST['file_type'] == 'document') {

        $sql = "SELECT * FROM `documents` WHERE `file_type` = 'docx' OR file_type = 'xlsx' OR file_type = 'pdf' OR file_type = 'ppt';";
    } else if ($_POST['file_type'] == 'video') {

        $sql = "SELECT * FROM `documents` WHERE `file_type` = 'mp4';";
    } else if ($_POST['file_type'] == 'audio') {

        $sql = "SELECT * FROM `documents` WHERE `file_type` = 'mp3';";
    } else if ($_POST['file_type'] == 'image') {

        $sql = "SELECT * FROM `documents` WHERE `file_type` = 'png' OR file_type = 'jpg' OR file_type = 'jpeg' OR file_type = 'gif';";
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
} else if (isset($_POST['getrecord'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "SELECT * FROM `documents` WHERE `id` ='$id'";

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
    $sql = "DELETE FROM `documents` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
}

mysqli_close($con);
