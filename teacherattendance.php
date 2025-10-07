<?php
include 'dbconns.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['class_id'], $_POST['subject_id'], $_POST['date'], $_POST['student_id'], $_POST['status'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $class_id = intval($_POST['class_id']);
    $subject_id = intval($_POST['subject_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $student_id = intval($_POST['student_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // ✅ Prevent duplicate attendance (same student, class, subject, date)
    $check = "SELECT id FROM attendance 
              WHERE class_id = '$class_id' 
              AND subject_id = '$subject_id' 
              AND date = '$date' 
              AND student_id = '$student_id'";

    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        // Already exists → Update status instead of inserting duplicate
        $update = "UPDATE attendance 
                   SET status = '$status'
                   WHERE class_id = '$class_id'
                   AND subject_id = '$subject_id'
                   AND date = '$date'
                   AND student_id = '$student_id'";
        if (mysqli_query($conn, $update)) {
            echo json_encode(["status" => "success", "message" => "Attendance updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update attendance"]);
        }
    } else {
        // Insert new attendance
        $insert = "INSERT INTO attendance (class_id, subject_id, date, student_id, status) 
                   VALUES ('$class_id', '$subject_id', '$date', '$student_id', '$status')";
        if (mysqli_query($conn, $insert)) {
            echo json_encode(["status" => "success", "message" => "Attendance recorded"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to record attendance"]);
        }
    }
}
?>
