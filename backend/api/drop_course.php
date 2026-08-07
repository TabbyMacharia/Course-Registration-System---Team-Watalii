<?php
// backend/api/drop_course.php
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
    echo json_encode(["success" => false, "message" => "Invalid course offering selected."]);
    exit();
}

$stmt = $conn->prepare("UPDATE registrations SET status = 'dropped' WHERE student_id = ? AND offering_id = ?");
$stmt->bind_param("ii", $student_id, $offering_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Course dropped successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to drop course."]);
}
?>
