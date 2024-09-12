<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../student/sendMail/PHPMailerAutoload.php';

$mail = isset($_GET['mail']) ? htmlspecialchars($_GET['mail'], ENT_QUOTES, 'UTF-8') : '';

if(isset($_POST['send'])) {
    $to = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $subject = "Complaint Forward";
    $message = htmlspecialchars($_POST['msg'], ENT_QUOTES, 'UTF-8');

    if (!$to) {
        echo "<script>alert('Invalid email address');</script>";
    } else {
        $mailer = new PHPMailer(true);
        try {
            // Server settings
            $mailer->SMTPDebug = 2; // Enable verbose debug output
            $mailer->isSMTP();
            $mailer->Host       = SMTP_HOST;
            $mailer->SMTPAuth   = true;
            $mailer->Username   = EMAIL_ACC;
            $mailer->Password   = EMAIL_PASSWORD;
            $mailer->SMTPSecure = SMTP_SECURE;
            $mailer->Port       = SMTP_PORT;

            // Recipients
            $mailer->setFrom(EMAIL_ACC, 'KTU Complaint Hub');
            $mailer->addAddress($to);
            $mailer->addReplyTo(EMAIL_ACC, 'KTU Complaint Hub');

            // Content
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body    = $message;

            $mailer->send();
            echo "<script>alert('Email sent successfully');</script>";
            echo "<script>window.location.href = '" . BASE_URL . "/admin/index.php';</script>";
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: " . addslashes($mailer->ErrorInfo) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forward Mail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <form action="forward_mail.php<?php echo $mail ? '?mail=' . urlencode($mail) : ''; ?>" method="post">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" align="center"><h3>Email Confirmation Form</h3></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="text" class="form-control" value='<?php echo $mail; ?>' readonly /></td>
                    </tr>
                    <tr>
                        <td>To:</td>
                        <td><input type="email" class="form-control" name="email" required ></td>
                    </tr>
                    <tr>
                        <td>Message:</td>
                        <td><textarea class="form-control" rows="5" name="msg" required></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="text-center">
                                <button class="btn btn-primary" name="send" type="submit">Send Mail</button>
                                <a href="<?php echo BASE_URL; ?>/admin/index.php" class="btn btn-secondary">Back</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
