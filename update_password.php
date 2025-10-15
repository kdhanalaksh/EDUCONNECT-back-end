<?php
header("Content-Type: application/json");
require_once __DIR__ . '/dbconns.php'; // include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        $input = $_POST;
    }

    // Validate required fields
    if (empty($input['email']) || empty($input['new_password'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Email and new_password are required"
        ]);
        exit;
    }

    $email = trim($input['email']);
    $new_password = trim($input['new_password']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email format"
        ]);
        exit;
    }

    // Validate password length
    if (strlen($new_password) < 8) {
        echo json_encode([
            "status" => "error",
            "message" => "Password must be at least 8 characters long"
        ]);
        exit;
    }

    // Check DB connection
    if (!$conn) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the DB
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Password updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update password"
        ]);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed"
    ]);
}
?>
