<?php
header("Content-Type: application/json");
require_once __DIR__ . '/dbconns.php';

$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

if ($teacher_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Teacher ID is required"]);
    exit;
}

$query = "SELECT setting_name, setting_value FROM teacher_preferences WHERE teacher_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$settings = [];
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_name']] = (bool)$row['setting_value'];
}

echo json_encode([
    "status" => "success",
    "teacher_id" => $teacher_id,
    "settings" => $settings
]);
?>
