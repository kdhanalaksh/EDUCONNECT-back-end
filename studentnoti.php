<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM notification WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode(["status" => "success", "notifications" => $notifications]);
?>
