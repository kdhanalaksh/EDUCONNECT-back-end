<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $assignment_id = $_POST['assignment_id'];

    $sql = "INSERT INTO assignment_request (student_id, assignment_id, status)
            VALUES ('$student_id','$assignment_id','Pending')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Assignment posted"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
