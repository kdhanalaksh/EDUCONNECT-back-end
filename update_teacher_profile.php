<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Read input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Log incoming request for debugging
file_put_contents("teacher_update_log.txt", date("Y-m-d H:i:s") . " - Request: " . json_encode($input) . "\n", FILE_APPEND);

if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

// Extract and sanitize inputs
$user_id        = isset($input['user_id']) ? intval($input['user_id']) : 0;
$name           = isset($input['name']) ? $conn->real_escape_string($input['name']) : null;
$email          = isset($input['email']) ? $conn->real_escape_string($input['email']) : null;
$phone          = isset($input['phone']) ? $conn->real_escape_string($input['phone']) : null;
$gender         = isset($input['gender']) ? $conn->real_escape_string($input['gender']) : null;
$subject_id     = isset($input['subject_id']) ? intval($input['subject_id']) : null;
$qualifications = isset($input['qualifications']) ? $conn->real_escape_string($input['qualifications']) : null;
$experience     = isset($input['experience']) ? intval($input['experience']) : null;

// Validate required fields
if (!$user_id || !$name || !$email || !$phone || !$gender || !$subject_id || !$qualifications || !$experience) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// --- Update user table ---
$sql_user = "
    UPDATE user 
    SET 
        name = '$name', 
        email = '$email', 
        contact_number = '$phone', 
        gender = '$gender'
    WHERE id = '$user_id'
";
$user_result = $conn->query($sql_user);

// Check for errors
if (!$user_result) {
    echo json_encode(["status" => "error", "message" => "User update failed: " . $conn->error]);
    exit;
}

// --- Update teacher_profile table ---
$sql_profile = "
    UPDATE teacher_profile 
    SET 
        subject_id = '$subject_id',
        qualifications = '$qualifications',
        experience = '$experience'
    WHERE user_id = '$user_id'
";
$profile_result = $conn->query($sql_profile);

// Check result
if ($profile_result) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Profile update failed: " . $conn->error]);
}

// Close connection
$conn->close();
?>
