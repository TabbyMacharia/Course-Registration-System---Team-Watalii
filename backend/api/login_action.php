<?php
// backend/api/login_action.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit();
}

$stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user['password_hash'])) {
    if ($user['role'] !== 'student') {
        echo json_encode(["success" => false, "message" => "Access denied. Only students can log in here."]);
        exit();
    }

    $stmt_student = $conn->prepare("SELECT student_id, first_name, last_name FROM students WHERE user_id = ?");
    $stmt_student->bind_param("i", $user['user_id']);
    $stmt_student->execute();
    $student = $stmt_student->get_result()->fetch_assoc();

    if (!$student) {
        echo json_encode(["success" => false, "message" => "Student profile record not found."]);
        exit();
    }

    $_SESSION['user_id']      = $user['user_id'];
    $_SESSION['student_id']   = $student['student_id'];
    $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "student_name" => $_SESSION['student_name']
    ]);
    exit();
}

echo json_encode(["success" => false, "message" => "Invalid username/email or password."]);
?>