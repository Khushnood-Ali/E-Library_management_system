<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = "Mynameisno1"; // Your database password
$dbname = "e_library"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
