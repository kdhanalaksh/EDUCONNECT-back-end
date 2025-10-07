<?php
include 'dbconns.php';
header('Content-Type: application/json');

// -------------------- Get JSON Input -------------------- //
$input = json_decode(file_get_contents("php://input"), true);

$teacher_id     = isset($input['teacher_id']) ? trim($input['teacher_id']) : null;
$title          = isset($input['title']) ? trim($input['title']) : null;
$meeting_date   = isset($input['meeting_date']) ? trim($input['meeting_date']) : null; // YYYY-MM-DD
$start_time     = isset($input['start_time']) ? trim($input['start_time']) : null;     // HH:MM:SS
$end_time       = isset($input['end_time']) ? trim($input['end_time']) : null;         // HH:MM:SS
$purpose        = isset($input['purpose']) ? trim($input['purpose']) : null;
$notify_parents = isset($input['notify_parents']) ? intval($input['notify_parents']) : 0;
$meeting_link   = isset($input['meeting_link']) ? trim($input['meeting_link']) : '';   // ✅ optional

// -------------------- Validate Required Fields -------------------- //
$missing = [];
if (!$teacher_id)   $missing[] = "teacher_id";
if (!$title)        $missing[] = "title";
if (!$meeting_date) $missing[] = "meeting_date";
if (!$start_time)   $missing[] = "start_time";
if (!$end_time)     $missing[] = "end_time";
if (!$purpose)      $missing[] = "purpose";

if (!empty($missing)) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields: " . implode(", ", $missing)
    ]);
    exit;
}

// -------------------- Validate Date & Time -------------------- //
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $meeting_date)) {
    echo json_encode(["status"=>"error","message"=>"Invalid meeting_date format. Use YYYY-MM-DD"]);
    exit;
}

if (!preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $start_time) || !preg_match("/^\d{2}:\d{2}(:\d{2})?$/", $end_time)) {
    echo json_encode(["status"=>"error","message"=>"Invalid start_time or end_time format"]);
    exit;
}

if (strtotime($start_time) >= strtotime($end_time)) {
    echo json_encode(["status"=>"error","message"=>"start_time must be before end_time"]);
    exit;
}

// -------------------- Insert into Database -------------------- //
$sql = "INSERT INTO meeting (teacher_id, title, meeting_date, start_time, end_time, purpose, notify_parents, meeting_link) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "isssssis",
    $teacher_id, $title, $meeting_date, $start_time, $end_time, $purpose, $notify_parents, $meeting_link
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Meeting created successfully",
        "meeting_id" => $stmt->insert_id,
        "meeting_link" => $meeting_link
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $stmt->error
    ]);
}
?>
