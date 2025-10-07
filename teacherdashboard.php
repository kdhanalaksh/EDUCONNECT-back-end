<?php
require_once('dbconns.php'); // ensure correct name
header('Content-Type: application/json');

// Read JSON body (if available)
$inputJSON = file_get_contents("php://input");
$data = json_decode($inputJSON, true);

// Get teacher_id from GET, POST form, or JSON
$teacher_id = 0;
if (isset($_GET['teacher_id'])) {
    $teacher_id = intval($_GET['teacher_id']);
} elseif (isset($_POST['teacher_id'])) {
    $teacher_id = intval($_POST['teacher_id']);
} elseif (isset($data['teacher_id'])) {
    $teacher_id = intval($data['teacher_id']);
}

// Validate teacher_id
if ($teacher_id <= 0) {
    echo json_encode(["error" => "Teacher ID is required"]);
    exit;
}

// Fetch teacher profile
$query = "SELECT 
    u.name, u.profile_picture, t.subject_id,
    s.name AS subject_name,
    c.id AS class_id, c.class_name, c.section
 FROM user u
 JOIN teacher_profile t ON u.id = t.user_id
 JOIN subject s ON t.subject_id = s.id
 JOIN class c ON c.teacher_id = u.id
 WHERE u.id = ? AND u.role = 'teacher'
 LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

if (!$profile) {
    echo json_encode(["error" => "Teacher not found or not a teacher"]);
    exit;
}

$class_id    = $profile['class_id'];
$subject_id  = $profile['subject_id'];
$today       = date('Y-m-d');

// Today's attendance
$stmt = $conn->prepare("SELECT COUNT(*) as present FROM attendance 
    WHERE class_id=? AND subject_id=? AND date=? AND status='Present'");
$stmt->bind_param("iis", $class_id, $subject_id, $today);
$stmt->execute();
$today_present = $stmt->get_result()->fetch_assoc()['present'] ?? 0;

// Total students
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM student_profile WHERE class_id=?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$total_students = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Last class
$stmt = $conn->prepare("SELECT MAX(date) as last_date FROM attendance 
    WHERE class_id=? AND subject_id=? AND date < ?");
$stmt->bind_param("iis", $class_id, $subject_id, $today);
$stmt->execute();
$last_date = $stmt->get_result()->fetch_assoc()['last_date'] ?? null;

$last_present = 0;
if ($last_date) {
    $stmt = $conn->prepare("SELECT COUNT(*) as present FROM attendance 
        WHERE class_id=? AND subject_id=? AND date=? AND status='Present'");
    $stmt->bind_param("iis", $class_id, $subject_id, $last_date);
    $stmt->execute();
    $last_present = $stmt->get_result()->fetch_assoc()['present'] ?? 0;
}

// Weekly attendance
$week_start = date('Y-m-d', strtotime('monday this week'));
$stmt = $conn->prepare("SELECT COUNT(*) as present FROM attendance 
    WHERE class_id=? AND subject_id=? AND date BETWEEN ? AND ? AND status='Present'");
$stmt->bind_param("iiss", $class_id, $subject_id, $week_start, $today);
$stmt->execute();
$week_present = $stmt->get_result()->fetch_assoc()['present'] ?? 0;

$stmt = $conn->prepare("SELECT COUNT(DISTINCT date) as sessions FROM attendance 
    WHERE class_id=? AND subject_id=? AND date BETWEEN ? AND ?");
$stmt->bind_param("iiss", $class_id, $subject_id, $week_start, $today);
$stmt->execute();
$total_sessions = $stmt->get_result()->fetch_assoc()['sessions'] ?? 1;

function percent($num, $den) { return $den ? round(($num / $den) * 100) : 0; }

// Assignment submissions
$stmt = $conn->prepare("SELECT COUNT(*) as submitted FROM assignment_request ar
 JOIN homework h ON ar.assignment_id = h.id
 WHERE h.class_id=? AND ar.status='Approved' AND h.subject_id=? AND h.due_date=?");
$stmt->bind_param("iis", $class_id, $subject_id, $today);
$stmt->execute();
$submitted = $stmt->get_result()->fetch_assoc()['submitted'] ?? 0;

// Quiz completed
$stmt = $conn->prepare("SELECT COUNT(*) as quiz_completed FROM homework 
    WHERE class_id=? AND subject_id=? AND status='Completed'
    AND (title LIKE '%quiz%' OR description LIKE '%quiz%') AND due_date=?");
$stmt->bind_param("iis", $class_id, $subject_id, $today);
$stmt->execute();
$quiz_completed = $stmt->get_result()->fetch_assoc()['quiz_completed'] ?? 0;

// Course requests
$stmt = $conn->prepare("SELECT COUNT(*) as course_requests FROM course_enrollment 
    WHERE class_id=? AND status='Requested'");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$cr_req = $stmt->get_result()->fetch_assoc()['course_requests'] ?? 0;

// Output JSON
echo json_encode([
    "teacher" => [
        "name"            => $profile['name'],
        "profile_picture" => $profile['profile_picture'],
        "current_subject" => $profile['subject_name'],
        "class"           => $profile['class_name'] . '-' . $profile['section']
    ],
    "attendance_overview" => [
        "today" => [
            "present" => $today_present,
            "total"   => $total_students,
            "percent" => percent($today_present, $total_students)
        ],
        "last_class" => [
            "present" => $last_present,
            "total"   => $total_students,
            "percent" => percent($last_present, $total_students)
        ],
        "weekly" => [
            "percent" => percent($week_present, ($total_students * $total_sessions))
        ]
    ],
    "recent_class_updates" => [
        [
            "message" => "Assignment submitted - $submitted students",
            "time"    => "10:15 AM"
        ],
        [
            "message" => "Quiz completed - $quiz_completed students",
            "time"    => "09:45 AM"
        ],
        [
            "message" => "Course request - $cr_req requests",
            "time"    => "09:30 AM"
        ]
    ]
], JSON_PRETTY_PRINT);

?>
