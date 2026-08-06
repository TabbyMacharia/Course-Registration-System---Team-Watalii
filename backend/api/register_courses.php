<?php
// backend/api/register_course.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

$student_id  = $_SESSION['student_id'];
$offering_id = intval($_POST['offering_id'] ?? 0);

if ($offering_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid course offering selection."]);
    exit();
}

$sql = "INSERT INTO registrations (student_id, offering_id, status) 
        VALUES (?, ?, 'registered') 
        ON DUPLICATE KEY UPDATE status = 'registered', registration_date = CURRENT_DATE()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $offering_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Course registered successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed. Please try again."]);
}
?>