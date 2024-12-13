<?php
// Database configuration
$host = 'localhost'; 
$dbname = 'testdb3'; 
$username = 'root'; 
$password = ''; 

try {
    // Establishing the PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Setting PDO attributes for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handling connection errors
    die("Error: Unable to connect to the database. " . $e->getMessage());
}
?>