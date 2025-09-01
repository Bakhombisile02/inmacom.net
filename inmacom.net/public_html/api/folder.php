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

    $sql = "SELECT * FROM folder";

    if ($results = mysqli_query($con, $sql)) {

        $response = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $response[] = $row;
        }
        $output = array("type" => "successful", "data" => $response);
        echo json_encode($output);
    } else {
        die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
    }
} else if (isset($_POST['getrecord'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "SELECT * FROM `folder` WHERE `id` ='$id'";

    if ($results = mysqli_query($con, $sql)) {

        $response = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $response[] = $row;
        }
        $output = array("type" => "successful", "data" => $response);
        echo json_encode($output);
    } else {
        die(json_encode(array("type" => "error", "text" => "Couldn't get data")));
    }
} else if (isset($_POST['delete'])) {

    $id = mysqli_escape_string($con, $_POST['id']);
    $sql = "DELETE FROM `folder` WHERE `id`= '$id'";
    if (mysqli_query($con, $sql)) {
        $output = array("type" => "successful", "text" => "Successfully deleted!");
        echo json_encode($output);
    } else {
        $output = array("type" => "error", "text" => "Failed to get data", "data" => mysqli_error($con));
        echo json_encode($output);
    }
} else {
    if (isset($_REQUEST['folder'])) {

        $folder = mysqli_real_escape_string($con, $_REQUEST['folder']);
        
        $query    = "INSERT INTO `folder`(`name`) 
            VALUES ('$folder')";

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