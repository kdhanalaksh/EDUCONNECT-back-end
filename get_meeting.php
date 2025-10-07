<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Get student_id from request
$input = json_decode(file_get_contents("php://input"), true);
$student_id = isset($input['student_id']) ? intval($input['student_id']) : 0;

if ($student_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid student ID"]);
    exit;
}

// ✅ Step 1: Find which teacher(s) this student is assigned to
$query = "SELECT teacher_id FROM student_teacher WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$teacherIds = [];
while ($row = $result->fetch_assoc()) {
    $teacherIds[] = $row['teacher_id'];
}

if (empty($teacherIds)) {
    echo json_encode(["status" => "success", "meetings" => []]);
    exit;
}

// ✅ Step 2: Fetch meetings created by those teachers
$in  = str_repeat('?,', count($teacherIds) - 1) . '?';
$query2 = "SELECT * FROM meeting WHERE teacher_id IN ($in) ORDER BY meeting_date DESC";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param(str_repeat('i', count($teacherIds)), ...$teacherIds);
$stmt2->execute();
$result2 = $stmt2->get_result();

$meetings = [];
while ($row = $result2->fetch_assoc()) {
    $meetings[] = $row;
}

echo json_encode(["status" => "success", "meetings" => $meetings]);
?>
