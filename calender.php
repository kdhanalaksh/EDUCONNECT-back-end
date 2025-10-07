<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT 'event' AS type, id, title, date, time, description 
            FROM event
            UNION ALL
            SELECT 'meeting' AS type, id, agenda AS title, meeting_date AS date, time, agenda AS description 
            FROM meeting";

    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $data]);
}
?>
