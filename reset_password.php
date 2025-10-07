<?php
header("Content-Type: application/json");
include __DIR__ . '/dbconns.php';

// Parse raw JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Get fields from JSON
$email = $input['email'] ?? null;
$password = $input['password'] ?? null;
$confirm_password = $input['confirm_password'] ?? null;

// Validate input
if (!$email || !$password || !$confirm_password) {
    echo json_encode([
        "status" => false,
        "message" => "Email, password, and confirm password are required."
    ]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode([
        "status" => false,
        "message" => "Passwords do not match."
    ]);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => false,
        "message" => "User not found."
    ]);
    exit;
}

// Hash and update password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
$update->bind_param("ss", $hashed_password, $email);
$update->execute();

echo json_encode([
    "status" => true,
    "message" => "Password reset successful."
]);
?>
