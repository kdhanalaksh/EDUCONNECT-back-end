<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting (for development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';
include 'dbconns.php';

header('Content-Type: application/json');

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["email"]) || empty(trim($data["email"]))) {
            echo json_encode(["status" => false, "message" => "Email is required"]);
            exit;
        }

        $email = filter_var(trim($data["email"]), FILTER_SANITIZE_EMAIL);
        $otp = rand(100000, 999999);
        $otp_created_at = date("Y-m-d H:i:s");

        // Check if email exists
        $checkStmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            echo json_encode(["status" => false, "message" => "Email not registered"]);
            exit;
        }

        // Save OTP and timestamp to the user table
        $updateStmt = $conn->prepare("UPDATE user SET otp = ?, otp_created_at = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $otp, $otp_created_at, $email);
        $updateStmt->execute();

        // Send OTP via Email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kdhanalakshmi2005@gmail.com'; // Replace with your email
        $mail->Password = 'stth lhvp egwr ycfv';         // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('kdhanalakshmi2005@gmail.com', 'EduConnect');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<p>Your OTP is: <strong>$otp</strong><br>It is valid for 5 minutes.</p>";

        $mail->send();

        echo json_encode([
            "status" => true,
            "message" => "OTP sent successfully"
            // Don't return OTP in production!
        ]);

    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => "Mail Error: " . $mail->ErrorInfo]);
    } catch (\Throwable $e) {
        echo json_encode(["status" => false, "message" => "Server Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid request method"]);
}
?>
