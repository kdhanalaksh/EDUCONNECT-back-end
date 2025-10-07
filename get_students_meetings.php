<?php
include 'dbconns.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

$student_id = null;
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
} elseif (isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);
} elseif (isset($input['student_id'])) {
    $student_id = intval($input['student_id']);
}

if (empty($student_id)) {
    echo json_encode(["status" => "error", "message" => "student_id required"]);
    exit;
}

error_log("Incoming student_id = " . $student_id);

// ✅ FIX: get teacher_id from students table
$sql = "SELECT teacher_id FROM students WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $teacher_id = $row['teacher_id'];

    if (!empty($teacher_id)) {
        $sql2 = "SELECT id, title, meeting_date, start_time, end_time, purpose, meeting_link, status 
                 FROM meeting 
                 WHERE teacher_id = ? 
                 ORDER BY meeting_date DESC";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $teacher_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $meetings = [];
        while ($row2 = $result2->fetch_assoc()) {
            $meetings[] = $row2;
        }

        echo json_encode(["status" => "success", "meetings" => $meetings]);
    } else {
        echo json_encode(["status" => "error", "message" => "No teacher assigned to this student"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Student not found"]);
}

$conn->close();
?>
