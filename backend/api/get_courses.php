<?php
// backend/api/get_courses.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please log in as a student."]);
    exit();
}

$student_id = $_SESSION['student_id'];

// LEFT JOIN on lecturers ensures courses display even if lecturer info isn't linked
$sql = "SELECT 
            co.offering_id,
            c.course_code,
            c.course_name,
            c.description,
            CONCAT(IFNULL(l.first_name, 'Staff'), ' ', IFNULL(l.last_name, '')) AS instructor,
            co.class_room,
            co.max_capacity,
            CASE 
                WHEN r.status = 'enrolled' THEN 1 
                ELSE 0 
            END AS is_registered
        FROM course_offerings co
        INNER JOIN courses c ON co.course_id = c.course_id
        LEFT JOIN lecturers l ON co.lecturer_id = l.lecturer_id
        INNER JOIN semesters s ON co.semester_id = s.semester_id
        LEFT JOIN registrations r ON co.offering_id = r.offering_id AND r.student_id = ?
        WHERE s.registration_open = TRUE";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "success" => true,
    "student_name" => $_SESSION['student_name'] ?? 'Student',
    "courses" => $courses
]);
?>