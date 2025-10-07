<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $homework_id = $_POST['homework_id'];
    $status = "Completed";

    $sql = "UPDATE homework SET status='$status' WHERE id='$homework_id'";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Homework marked as completed"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
