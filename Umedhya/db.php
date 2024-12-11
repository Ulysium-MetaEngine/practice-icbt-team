<?php
$server = "localhost";
$database = "share_ride_web";
$username = "root";
$password = "";

$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>