<?php
include 'dbconns.php';
header('Content-Type: application/json');

if (!isset($_GET['teacher_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing teacher_id"]);
    exit;
}

$teacher_id = intval($_GET['teacher_id']);

// Query to fetch students under this teacher
$sql = "
    SELECT DISTINCT
        sp.user_id AS student_user_id,
        u.name AS student_name,
        u.email,
        sp.class_id,
        sp.dob,
        sp.parent_name,
        sp.parent_contact,
        c.class_name,
        c.section
    FROM student_profile sp
    INNER JOIN user u ON sp.user_id = u.id
    INNER JOIN class c ON sp.class_id = c.id
    WHERE c.teacher_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(["status" => "success", "students" => $students]);
