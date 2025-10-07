<?php
include 'dbconns.php';
header('Content-Type: application/json');

// Get user_id from request
if (!isset($_GET['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Missing user_id"]);
    exit;
}
$user_id = intval($_GET['user_id']);

// Query to get teacher profile details with subject name
$sql = "
    SELECT 
        u.id AS user_id,
        u.name,
        u.email,
        u.contact_number,
        u.gender,
        u.profile_picture,
        tp.subject_id,
        s.name AS subject_name,
        tp.qualifications,
        tp.experience
    FROM user u
    LEFT JOIN teacher_profile tp ON u.id = tp.user_id
    LEFT JOIN subject s ON tp.subject_id = s.id
    WHERE u.id = $user_id AND u.role = 'teacher'
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $teacher = $result->fetch_assoc();
    echo json_encode(["status" => "success", "teacher" => $teacher]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}
$conn->close();
?>
