<?php
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "iruma_hardware_shop"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
