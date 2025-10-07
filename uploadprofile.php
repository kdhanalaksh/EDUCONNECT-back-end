<?php
header("Content-Type: application/json");  // Ensures JSON response
include __DIR__ . '/dbconns.php';  // safer include

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email']) || !isset($_FILES['profile_picture'])) {
        echo json_encode(["status" => "error", "message" => "Email and profile picture required"]);
        exit;
    }

    $email = $conn->real_escape_string($_POST['email']);
    $file_name = time() . "_" . basename($_FILES["profile_picture"]["name"]);
    $target_dir = __DIR__ . "/../../uploads/";
    $target_file = $target_dir . $file_name;

    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $conn->query("UPDATE user SET profile_picture='$file_name' WHERE email='$email'");
        echo json_encode(["status" => "success", "message" => "Profile picture uploaded"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Upload failed"]);
    }
}
?>
