<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $type = $_POST['type']; // Academic / Event / General
    $message = $_POST['message'];

    $sql = "INSERT INTO notification (user_id, title, type, message)
            VALUES ('$user_id','$title','$type','$message')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Notification sent"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
