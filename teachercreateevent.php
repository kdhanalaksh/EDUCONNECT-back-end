<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $visibility = $_POST['visibility'];

    $sql = "INSERT INTO event (title,date,time,type,description,visibility)
            VALUES ('$title','$date','$time','$type','$description','$visibility')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Event created"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
