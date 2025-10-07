<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if (!isset($_GET['user_id'])) {
    echo json_encode(["status" => "error", "message" => "user_id is required"]);
    exit;
}

$user_id = $_GET['user_id'];

$sql = "SELECT u.name, c.class_name, c.section
        FROM user u
        JOIN student_profile sp ON u.id = sp.user_id
        JOIN class c ON sp.class_id = c.id
        WHERE u.id='$user_id'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
} else {
    echo json_encode(["status" => "error", "message" => "No data found"]);
}
?>
