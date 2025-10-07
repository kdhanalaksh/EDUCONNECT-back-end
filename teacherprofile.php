<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $subject_id = $_POST['subject_id'];
    $qualifications = $_POST['qualifications'];
    $experience = $_POST['experience'];

    $sql = "INSERT INTO teacher_profile (user_id, subject_id, qualifications, experience) 
            VALUES ('$user_id', '$subject_id', '$qualifications', '$experience')";

    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Teacher profile created"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed: ".$conn->error]);
    }
}
?>
