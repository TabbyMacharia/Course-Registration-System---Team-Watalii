<?php
// backend/config/db.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "course_registration_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>