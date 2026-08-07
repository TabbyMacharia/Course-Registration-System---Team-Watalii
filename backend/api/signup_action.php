<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

$role       = trim($_POST['role'] ?? 'student');
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$identifier = trim($_POST['identifier'] ?? '');
$username   = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';

if (empty($first_name) || empty($last_name) || empty($identifier) || empty($username) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit();
}

$stmt_check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username or email is already registered."]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$conn->begin_transaction();

try {
    // 1. Insert login record
    $stmt_user = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt_user->bind_param("ssss", $username, $email, $hashed_password, $role);
    $stmt_user->execute();
    $user_id = $stmt_user->insert_id;

    // 2. Insert role-specific profile record
    if ($role === 'lecturer') {
        $stmt_profile = $conn->prepare("INSERT INTO lecturers (user_id, department_id, staff_number, first_name, last_name) VALUES (?, 1, ?, ?, ?)");
        $stmt_profile->bind_param("isss", $user_id, $identifier, $first_name, $last_name);
    } else {
        $stmt_profile = $conn->prepare("INSERT INTO students (user_id, program_id, registration_number, first_name, last_name) VALUES (?, 1, ?, ?, ?)");
        $stmt_profile->bind_param("isss", $user_id, $identifier, $first_name, $last_name);
    }
    
    $stmt_profile->execute();
    $conn->commit();

    echo json_encode(["success" => true, "message" => "Account created successfully! Redirecting to login..."]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Failed to create account: " . $e->getMessage()]);
}
?>