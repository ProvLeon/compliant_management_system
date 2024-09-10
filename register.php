<?php
// register.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/student/sendMail/PHPMailerAutoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rollno = filter_input(INPUT_POST, 'rollno', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // We'll hash this later
    $cpassword = $_POST['cpassword'];
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $hostel = filter_input(INPUT_POST, 'hostel', FILTER_SANITIZE_STRING);
    $course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);

    if ($password !== $cpassword) {
        echo '<script>alert("Passwords do not match. Please try again."); window.location.href="index.html";</script>';
        exit();
    }

    // Check if roll number or email already exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE rollno = ? OR email = ?");
    $stmt->bind_param("ss", $rollno, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count > 0) {
        echo '<script>alert("This roll number or email is already registered."); window.location.href="index.html";</script>';
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new student record
    $stmt = $conn->prepare("INSERT INTO student (rollno, name, contact, email, hostel, course, password, active) VALUES (?, ?, ?, ?, ?, ?, ?, 'n')");
    $stmt->bind_param("sssssss", $rollno, $name, $contact, $email, $hostel, $course, $hashed_password);

    if ($stmt->execute()) {
        // Send activation email
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
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Activate Your Account';
            $activation_link = BASE_URL . '/active.php?usercode=' . urlencode($email);
            $mail->Body = "<h1>Hello {$name}</h1><br/>You are registered. Please click the link below to activate your account:<br/><a href='{$activation_link}'>Activate Now</a>";

            $mail->send();
            echo '<script>alert("Registered successfully! Please check your email to activate your account."); window.location.href="index.html";</script>';
        } catch (Exception $e) {
            echo '<script>alert("Registered successfully, but failed to send activation email. Please contact support."); window.location.href="index.html";</script>';
        }
    } else {
        echo '<script>alert("Registration failed. Please try again."); window.location.href="index.html";</script>';
    }
} else {
    header("Location: index.html");
    exit();
}
?>
