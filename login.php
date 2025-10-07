<?php
header("Content-Type: application/json");
require_once __DIR__ . '/dbconns.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw input (JSON or form-data)
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        $input = $_POST;
    }

    // Validate input
    if (empty($input['email']) || empty($input['password']) || empty($input['role'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Email, password, and role are required"
        ]);
        exit;
    }

    $email = $conn->real_escape_string(trim($input['email']));
    $password = $input['password']; // Plain entered password
    $role = $conn->real_escape_string(trim($input['role']));

    // Fetch user
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM user WHERE email = ? AND role = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            unset($user['password']); // Don’t expose hash
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => $user
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid password"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => ucfirst($role) . " user not found"
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed"
    ]);
}
?>
