<?php
$server = "localhost";
$database = "practice";
$username = "root";
$password = "";

$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>