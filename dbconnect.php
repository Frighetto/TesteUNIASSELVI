<?php

$servername = "localhost:3306";
$username = "root";
$password = "admin";
$dbname = "TesteUNIASSELVI";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "set names 'utf8'";
$conn->query($sql);

 
?>
