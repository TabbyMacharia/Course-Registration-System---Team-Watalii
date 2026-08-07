<?php
// backend/api/add_course_offering.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lecturer') {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

$course_code = trim($_POST['course_code'] ?? '');
$course_name = trim($_POST['course_name'] ?? '');
$class_room  = trim($_POST['class_room'] ?? 'TBA');
$description = trim($_POST['description'] ?? '');

if (empty($course_code) || empty($course_name)) {
    echo json_encode(["success" => false, "message" => "Course code and name are required."]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO courses (department_id, course_code, course_name, description) VALUES (1, ?, ?, ?)");
$stmt->bind_param("sss", $course_code, $course_name, $description);

if ($stmt->execute()) {
    $course_id   = $stmt->insert_id;
    
    // Get logged in lecturer ID or default to NULL
    $lecturer_id = $_SESSION['lecturer_id'] ?? NULL;

    $stmt_offering = $conn->prepare("INSERT INTO course_offerings (course_id, lecturer_id, semester_id, class_room, max_capacity) VALUES (?, ?, 1, ?, 50)");
    $stmt_offering->bind_param("iis", $course_id, $lecturer_id, $class_room);
    $stmt_offering->execute();

    echo json_encode(["success" => true, "message" => "Course successfully published!"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add course: " . $conn->error]);
}
?>