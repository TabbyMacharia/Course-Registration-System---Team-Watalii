<?php
$host     = "localhost";
$username = "root";       // Default MySQL user in XAMPP/WAMP
$password = "";           // Default password (leave empty "" in XAMPP)
$dbname   = "course_registration_system";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>