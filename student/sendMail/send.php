<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '#change email';
    $mail->Password = '#change password';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('KTUComplaintHUB2024@ktu.edu.gh', 'Project');
    $mail->addAddress('KTUComplaintHUB2024@ktu.edu.gh', 'Project');
    $mail->isHTML(true);
    $mail->Subject = 'Congratulations! <br/> You are Registered Successfully .....';
    $mail->Body = '<h1>Hello Participants..!<br/>You are registered Please contact </h1>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo '<h1>Message has been sent</h1>';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
?>