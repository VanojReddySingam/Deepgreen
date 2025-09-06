<?php
$conn = new mysqli('localhost', 'root', '', 'deepgreen');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
