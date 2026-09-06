<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "barangay_system";


// =========================================
// CREATE DATABASE CONNECTION
// =========================================

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);


// =========================================
// CHECK CONNECTION
// =========================================

if ($conn->connect_error) {

    die(
        "Database connection failed: " .
        $conn->connect_error
    );

}

// =========================================
// SET CHARACTER SET
// =========================================

$conn->set_charset("utf8mb4");

?>