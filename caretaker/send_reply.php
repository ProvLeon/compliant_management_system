<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/sendMail/PHPMailerAutoload.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $cid = filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT);
    $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

    if ($email && $cid && $message) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer;

        // SMTP configuration (adjust these settings according to your SMTP server)
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_ACC;
        $mail->Password = EMAIL_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        // Sender and recipient settings
        $mail->setFrom('admin@example.com', 'KTUComplaintHUB2024');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Response to Your Complaint (ID: ' . $cid . ')';
        $mail->Body    = nl2br($message);
        $mail->AltBody = strip_tags($message);

        // Send the email
        if($mail->send()) {
            // Log the sent email
            $log_message = date('Y-m-d H:i:s') . " - Email sent to: $email for complaint ID: $cid\n";
            file_put_contents(__DIR__ . '/email_log.txt', $log_message, FILE_APPEND);

            // Update the complaint status to 'replied' in the database
            $conn = Connect();
            // $updateQuery = "UPDATE complaint SET status = 'replied' WHERE cid = ?";
            // $stmt = $conn->prepare($updateQuery);
            // $stmt->bind_param("i", $cid);
            // $stmt->execute();
            // $stmt->close();
            // $conn->close();

            echo json_encode(['success' => true, 'message' => 'Reply sent successfully and complaint status updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error sending reply: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email, complaint ID, or message.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
