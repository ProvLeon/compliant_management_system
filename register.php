<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('student/sendMail/PHPMailerAutoload.php');
include('connection.php');

$rollno = $_POST['rollno'];
$name = $_POST['name'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$hostel = $_POST['hostel'];
$course = $_POST['course'];

if($password == $cpassword) {
    // Check if roll number already exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE rollno = ?");
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if($count > 0) {
        echo '<script type="text/javascript">alert("You are already registered! Please login.");
        location.href="index.html";</script>';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM student WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $email_count = $result->num_rows;

        if($email_count > 0) {
            echo '<script type="text/javascript">alert("This email is already registered. Please use a different email.");
            location.href="index.html";</script>';
        } else {
            // Insert new student record
            $stmt = $conn->prepare("INSERT INTO student (rollno, name, contact, email, hostel, course, password, active) VALUES (?, ?, ?, ?, ?, ?, ?, 'n')");
            $stmt->bind_param("sssssss", $rollno, $name, $contact, $email, $hostel, $course, $password);

            if($stmt->execute()) {
                echo '<script type="text/javascript">alert("Registered successfully! Please login.")</script>';

                $mail = new PHPMailer();

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'neilohene@gmail.com';
                    $mail->Password   = 'cmtkbjexpyicmpxh';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port       = 465;

                    //Recipients
                    $mail->setFrom('KTUComplaintHUB@ktu.edu.gh', 'KTUComplaintHUB2024');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Activate Your Account';
                    $mail->Body    = '<h1>Hello ' . $name . '</h1><br/>You are registered, please click the link below to activate your account<br/><a href="http://localhost:8000/active.php?usercode=' . $email . '">Activate Now</a>';

                    $mail->send();
                    echo 'Email sent successfully.';
                } catch (Exception $e) {
                    echo 'Failed to send email. Error: ', $mail->ErrorInfo;
                }

                header("Refresh : 0; URL=index.html");
            } else {
                echo '<script type="text/javascript">alert("Registration failed. Please try again.")</script>';
                header("Refresh : 0; URL=index.html");
            }
        }
    }
} else {
    echo '<script type="text/javascript">alert("Password not matched! Please try again.")</script>';
    header("Location: index.html");
    exit();
}
?>
