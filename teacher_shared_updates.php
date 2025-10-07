<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Get teacher_id from GET parameter
$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;
if ($teacher_id === 0) {
    echo json_encode(array("status" => "error", "message" => "Teacher ID is required"));
    exit;
}

// Fetch meetings created by this teacher
$sql = "SELECT title, meeting_date, start_time, end_time, purpose, status, meeting_link 
        FROM meeting
        WHERE teacher_id = '$teacher_id' 
        ORDER BY meeting_date DESC, start_time DESC";

$result = $conn->query($sql);

$meetings = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meetings[] = array(
            "title" => $row['title'],
            "time_range" => $row['meeting_date'] . " " . $row['start_time'] . " → " . $row['end_time'],
            "purpose" => $row['purpose'],
            "status" => $row['status'],
            "meeting_link" => !empty($row['meeting_link']) ? $row['meeting_link'] : null
        );
    }
}

echo json_encode(array(
    "status" => "success",
    "meetings" => $meetings
));

$conn->close();
?>
