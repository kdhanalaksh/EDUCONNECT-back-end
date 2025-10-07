<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include __DIR__ . '/dbconns.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    // Required fields
    if (!isset($input['name'], $input['email'], $input['password'], $input['role'], $input['contact_number'], $input['gender'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $name           = $input['name'];
    $email          = $input['email'];
    $password       = $input['password'];
    $role           = $input['role'];
    $contact_number = $input['contact_number'];
    $gender         = $input['gender'];

    // Profile picture is optional
    $profile_picture = isset($input['profile_picture']) ? $input['profile_picture'] : null;

    // Step 1: Check if user already exists
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "User already exists"]);
        exit;
    }

    // Step 2: Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Step 3: Insert user
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, role, contact_number, gender, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $role, $contact_number, $gender, $profile_picture);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User registered successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
