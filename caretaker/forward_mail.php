<?php
require_once __DIR__ . '/../config.php'; // Adjust the path as needed

$mail = @$_GET['mail'];

if(isset($_POST['send']))
{
    $to = $_POST['email'];
    $subject = "Complaint Forward";
    $message = $_POST['msg'];

    // Additional headers
    $headers = array(
        'From: KTUComplaintHUB2022 <KTUComplaintHUB@ktu.edu.gh>',
        'Reply-To: KTUComplaintHUB@ktu.edu.gh',
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    );

    if(@mail($to, $subject, $message, implode("\r\n", $headers))) {
        echo "<script>alert('Email accepted for delivery');</script>";
    } else {
        $error = error_get_last();
        echo "<script>alert('Email could not be sent. Error: " . addslashes($error['message']) . "');</script>";
    }

    // Attempt to send the email
    if(mail($to, $subject, $message, implode("\r\n", $headers)))
    {
        echo "<script>alert('Email sent successfully');</script>";
        echo "<script>window.location.href = '" . BASE_URL . "/caretaker/index.php';</script>";
        exit;
    }
    else
    {
        $error = error_get_last()['message'];
        echo "<script>alert('Email could not be sent. Error: " . addslashes($error) . "');</script>";
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
        <form action="forward_mail.php<?php echo isset($_GET['mail']) ? '?mail=' . urlencode($_GET['mail']) : ''; ?>" method="post">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2" align="center"><h3>Email Confirmation Form</h3></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="text" class="form-control" value='<?php echo htmlspecialchars($mail); ?>' readonly /></td>
                    </tr>
                    <tr>
                        <td>To:</td>
                        <td><input type="email" class="form-control" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"></td>
                    </tr>
                    <tr>
                        <td>Message:</td>
                        <td><textarea class="form-control" rows="5" name="msg" required></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="text-center">
                                <button class="btn btn-primary" name="send" type="submit">Send Mail</button>
                                <a href="<?php echo BASE_URL; ?>/caretaker/index.php" class="btn btn-secondary">Back</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
