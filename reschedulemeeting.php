<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Read JSON input
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if (!isset($data['id'], $data['teacher_id'], $data['meeting_date'], $data['start_time'], $data['end_time'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$id = intval($data['id']);  // <-- correct column name
$teacher_id = intval($data['teacher_id']);
$meeting_date = $data['meeting_date'];
$start_time = $data['start_time'];
$end_time = $data['end_time'];

$sql = "UPDATE meeting 
        SET meeting_date = ?, start_time = ?, end_time = ?, updated_at = NOW() 
        WHERE id = ? AND teacher_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $meeting_date, $start_time, $end_time, $id, $teacher_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Meeting rescheduled successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to reschedule meeting"]);
}

$stmt->close();
$conn->close();
?>
