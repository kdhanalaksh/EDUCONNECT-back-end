<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educonnect_new_backend";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed: " . $conn->connect_error]));
}
?>
