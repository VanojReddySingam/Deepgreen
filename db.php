<?php
$conn = new mysqli("localhost", "root", "", "deepgreen"); // database: deepgreen

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
