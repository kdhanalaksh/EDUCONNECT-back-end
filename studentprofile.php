<?php
header("Content-Type: application/json");
include __DIR__ . '/dbconns.php'; // your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $student_id = isset($data['student_id']) ? intval($data['student_id']) : 0;

    if ($student_id <= 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid student ID"
        ]);
        exit();
    }

    // Fetch only required student data
    $stmt = $conn->prepare("SELECT id, name, email, contact_number, gender, teacher_id, profile_picture FROM user WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();

        echo json_encode([
            "status" => "success",
            "student" => $student
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Student not found"
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method"
    ]);
}
?>
