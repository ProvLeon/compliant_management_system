<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/student/sendMail/PHPMailerAutoload.php';

$mail = isset($_GET['mail']) ? htmlspecialchars($_GET['mail']) : '';

if (isset($_POST['send'])) {
    $to = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = "Password Recovery";
    $message = "Your password recovery request has been received. Please contact the admin for further assistance.";

    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = EMAIL_ACC;
        $mailer->Password = EMAIL_PASSWORD;
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 587;

        $mailer->setFrom(EMAIL_ACC, 'KTUComplaintHUB2024');
        $mailer->addAddress($to);
        $mailer->addReplyTo(EMAIL_ACC, 'KTUComplaintHUB2024');

        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body    = $message;

        $mailer->send();
        echo "<script>alert('Password recovery instructions sent to your email');</script>";
        echo "<script>window.location.href = '" . BASE_URL . "/index.php';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Email could not be sent. Error: " . addslashes($mailer->ErrorInfo) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Password Recovery</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-image: url(images/img-1.jpg);
            background-size: cover;
            padding-top: 50px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-container">
                    <h2 class="text-center">Student Password Recovery</h2>
                    <form action="forward_mail.php" method="post">
                        <div class="form-group">
                            <label for="email">Enter Your Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required pattern="\S+@ktu\.edu\.gh$">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="send">Recover Your Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
