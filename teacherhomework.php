<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO homework (class_id, subject_id, title, description, due_date, status)
            VALUES ('$class_id','$subject_id','$title','$description','$due_date','Pending')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Homework created"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
