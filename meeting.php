<?php
include 'dbconns.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || !isset($_GET['teacher_id'])) {
        echo json_encode(["status" => "error", "message" => "Missing id or teacher_id"]);
        exit;
    }

    $id = intval($_GET['id']);
    $teacher_id = intval($_GET['teacher_id']);

    $sql = "SELECT id, teacher_id, title, meeting_date, start_time, end_time, purpose, notify_parents, status, created_at, updated_at, meeting_link 
            FROM meeting 
            WHERE id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["status" => "success", "data" => $row]);
    } else {
        echo json_encode(["status" => "error", "message" => "Meeting not found"]);
    }

    $stmt->close();
    $conn->close();
}
?>
