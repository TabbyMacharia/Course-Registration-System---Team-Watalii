<?php
// backend/api/get_registered.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT 
            co.offering_id,
            c.course_code,
            c.course_name,
            c.credit_hours,
            CONCAT(l.first_name, ' ', l.last_name) AS instructor,
            co.class_room
        FROM registrations r
        INNER JOIN course_offerings co ON r.offering_id = co.offering_id
        INNER JOIN courses c ON co.course_id = c.course_id
        INNER JOIN lecturers l ON co.lecturer_id = l.lecturer_id
        WHERE r.student_id = ? AND r.status = 'registered'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$registered = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "success" => true,
    "student_name" => $_SESSION['student_name'],
    "courses" => $registered
]);
?>