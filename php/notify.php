<?php
// notify.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../student/sendMail/PHPMailerAutoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_ACC;
            $mail->Password = EMAIL_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom(EMAIL_ACC, 'KTUComplaintHUB2024');
            $mail->addAddress('neilohene@gmail.com');
            $mail->addReplyTo($email);

            $mail->isHTML(true);
            $mail->Subject = 'New message from website contact form';
            $mail->Body = "Email: {$email}";

            $mail->send();
            echo "Your message has been sent!";
        } catch (Exception $e) {
            echo "Oops! Something went wrong. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid email address.";
    }
} else {
    header("Location: " . BASE_URL);
    exit();
}
?>
