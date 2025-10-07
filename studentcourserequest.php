<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $status = "Requested";

    $sql = "INSERT INTO course_enrollment (student_id,class_id,status)
            VALUES ('$student_id','$class_id','$status')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Course request submitted"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
