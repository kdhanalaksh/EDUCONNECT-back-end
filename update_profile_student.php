<?php
header("Content-Type: application/json");
include __DIR__ . '/dbconns.php'; // your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $student_id = isset($data['student_id']) ? intval($data['student_id']) : 0;
    $name = isset($data['name']) ? trim($data['name']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $contact_number = isset($data['contact_number']) ? trim($data['contact_number']) : '';
    $gender = isset($data['gender']) ? trim($data['gender']) : '';
    $teacher_id = isset($data['teacher_id']) ? intval($data['teacher_id']) : null;

    if ($student_id <= 0 || empty($name) || empty($email)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid input data"
        ]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE user SET name = ?, email = ?, contact_number = ?, gender = ?, teacher_id = ? WHERE id = ?");
    $stmt->bind_param("ssssii", $name, $email, $contact_number, $gender, $teacher_id, $student_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Student updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update student"
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
