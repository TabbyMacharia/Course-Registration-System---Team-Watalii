<?php
// backend/api/login_action.php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

// Parse both FormData and direct JSON requests
$inputData = json_decode(file_get_contents('php://input'), true);

$username = trim($_POST['username'] ?? $inputData['username'] ?? '');
$password = $_POST['password'] ?? $inputData['password'] ?? '';

// 1. Verify required inputs are present
if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Please enter both username and password."]);
    exit();
}

// 2. Query user from database
$stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 3. Verify existence and hashed password
if (!$user || !password_verify($password, $user['password_hash'])) {
    echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    exit();
}

// 4. Populate global session variables
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['role']    = $user['role'];

// 5. Handle role-based profile session setup and redirection
if ($user['role'] === 'student') {
    $stmt_s = $conn->prepare("SELECT student_id, first_name, last_name FROM students WHERE user_id = ?");
    $stmt_s->bind_param("i", $user['user_id']);
    $stmt_s->execute();
    $student = $stmt_s->get_result()->fetch_assoc();
    
    if ($student) {
        $_SESSION['student_id']   = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
    }

    echo json_encode([
        "success" => true, 
        "role" => "student", 
        "redirect" => "courses.html"
    ]);
    exit();

} else if ($user['role'] === 'lecturer') {
    $stmt_l = $conn->prepare("SELECT lecturer_id, first_name, last_name FROM lecturers WHERE user_id = ?");
    $stmt_l->bind_param("i", $user['user_id']);
    $stmt_l->execute();
    $lecturer = $stmt_l->get_result()->fetch_assoc();

    if ($lecturer) {
        $_SESSION['lecturer_id']   = $lecturer['lecturer_id'];
        $_SESSION['lecturer_name'] = $lecturer['first_name'] . ' ' . $lecturer['last_name'];
    }

    echo json_encode([
        "success" => true, 
        "role" => "lecturer", 
        "redirect" => "lecturer.html"
    ]);
    exit();
}

echo json_encode(["success" => false, "message" => "Unrecognized account role."]);
?>