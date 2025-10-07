<?php
header("Content-Type: application/json");
require_once __DIR__ . '/dbconns.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method Not Allowed"
    ]);
    exit;
}

// Read JSON body
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

$teacher_id = isset($data['teacher_id']) ? intval($data['teacher_id']) : 0;
if ($teacher_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Teacher ID is required"]);
    exit;
}

/*
---------------------------------------------------------
 CASE 1: UPDATE TEACHER NAME
 Must send: { "teacher_id": 5, "name": "New Name" }
---------------------------------------------------------
*/
if (!empty($data['name'])) {
    $new_name = trim($data['name']);
    $stmt = $conn->prepare("UPDATE user SET name = ? WHERE id = ? AND role = 'teacher'");
    $stmt->bind_param("si", $new_name, $teacher_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "action" => "name_update",
            "message" => "Teacher name updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error while updating name"
        ]);
    }
    exit;
}

/*
---------------------------------------------------------
 CASE 2: UPDATE TEACHER SETTING
 Must send: { "teacher_id": 5, "setting_name": "school_announcements", "setting_value": 1 }
---------------------------------------------------------
*/
$setting_name  = isset($data['setting_name']) ? trim($data['setting_name']) : '';
$setting_value = isset($data['setting_value']) ? intval($data['setting_value']) : -1;

if ($setting_name !== '' && ($setting_value === 0 || $setting_value === 1)) {
    $query = "INSERT INTO teacher_preferences (teacher_id, setting_name, setting_value) 
              VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $teacher_id, $setting_name, $setting_value);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "action" => "setting_update",
            "message" => "Setting saved"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error while saving setting"
        ]);
    }
    exit;
}

// If neither case matches → invalid request
echo json_encode(["status" => "error", "message" => "Invalid request parameters"]);
?>
