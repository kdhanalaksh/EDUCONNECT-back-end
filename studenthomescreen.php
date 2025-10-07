<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Read input JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['student_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$student_id = intval($input['student_id']);

// 1️⃣ Fetch student details from user table
$sql = "SELECT id, name, email, role, contact_number, gender, profile_picture, teacher_id 
        FROM user WHERE id = ? AND role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Student not found"]);
    exit;
}

$student = $result->fetch_assoc();
$teacher_id = $student['teacher_id'];

// 2️⃣ Fetch teacher details from user table
$sql_teacher = "SELECT id, name, email, role, contact_number, gender, profile_picture 
                FROM user WHERE id = ? AND role = 'teacher'";
$stmt_teacher = $conn->prepare($sql_teacher);
$stmt_teacher->bind_param("i", $teacher_id);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();

$teacher = $result_teacher->num_rows > 0 ? $result_teacher->fetch_assoc() : null;

// 3️⃣ Fetch meetings assigned to this teacher
$sql_meetings = "SELECT id, teacher_id, title, meeting_date, start_time, end_time, purpose, notify_parents, status, created_at, updated_at, meeting_link 
                 FROM meeting WHERE teacher_id = ?";
$stmt_meetings = $conn->prepare($sql_meetings);
$stmt_meetings->bind_param("i", $teacher_id);
$stmt_meetings->execute();
$result_meetings = $stmt_meetings->get_result();

$meetings = [];
while ($row = $result_meetings->fetch_assoc()) {
    $meetings[] = $row;
}

// 4️⃣ Final JSON response
$response = [
    "status" => "success",
    "student" => $student,
    "teacher" => $teacher,
    "meetings" => $meetings
];

echo json_encode($response, JSON_PRETTY_PRINT);

// Close connections
$stmt->close();
$stmt_teacher->close();
$stmt_meetings->close();
$conn->close();
?>
