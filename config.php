<?php
// config/connection.php

$host = "mysql";
$username = "root";
$password = "";
$database = "mamacare";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8mb4");
?>