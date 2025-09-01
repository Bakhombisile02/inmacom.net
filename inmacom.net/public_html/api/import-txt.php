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
require('../vendor/phpmailer/phpmailer/PHPMailer.php');
require('../vendor/phpmailer/phpmailer/Exception.php');
require('../vendor/phpmailer/phpmailer/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$filename = '../data/datas.txt';

if (file_exists($filename)) {
    $open = fopen($filename, 'r');

    while (!feof($open)) {
        $getTextLine = fgets($open);
        $explodeLine = explode(",", $getTextLine);

        list($station_id, $value, $date) = $explodeLine;

        $qry = "INSERT INTO flow_levels (`station_id`, `value`,`unit`,`date`) values('" . $station_id . "','" . $value . "','m^3/s','" . $date . "')";
        mysqli_query($con, $qry);
    }

    fclose($open);
} else {
    $message = "The file $filename does not exist";
    $subject = "Missing Data";
    // $message = "Please upload data";
    $email = "coordinator@bitsandpc.co.za";

    try {
        //Server settings
        // $mail->SMTPDebug = 2; //Uncomment to view debug log
        $mail->isSMTP();
        $mail->Host = 'mail.inmacom.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@inmacom.net';
        $mail->Password = 'AccessInmacom';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('admin@inmacom.net', 'Admin');
        $mail->addAddress($email, 'Recipient1');
        $mail->addAddress('admin@inmacom.ent');
        $mail->addReplyTo('noreply@inmacom.net', 'noreply');
        $mail->addCC('coordinator@bitsandpc.co.za');
        $mail->addBCC('info@bitsandpc.co.za');

        //Attachments
        // $mail->addAttachment('/backup/test.log');

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
echo $message;
mysqli_close($con);