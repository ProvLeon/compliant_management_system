<?php
// sendmail.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../student/sendMail/PHPMailerAutoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if ($email && $name && $message) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            exit();
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_ACC;
            $mail->Password = EMAIL_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($email, $name);
            $mail->addAddress(EMAIL_ACC, 'KTUComplaintHUB2024');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Contact Form Submission';
            $mail->Body = "Name: {$name}<br>Email: {$email}<br>Message: {$message}";

            $mail->send();
            echo "Email sent successfully.";
        } catch (Exception $e) {
            echo "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Please fill in all required fields.";
    }
} else {
    header("Location: " . BASE_URL);
    exit();
}
?>
