<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/student/sendMail/PHPMailerAutoload.php';

$conn = Connect();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $rollno = htmlspecialchars(trim($_POST['rollno']), ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $contact = htmlspecialchars(trim($_POST['contact']), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $hostel = htmlspecialchars(trim($_POST['hostel']), ENT_QUOTES, 'UTF-8');
    $course = htmlspecialchars(trim($_POST['course']), ENT_QUOTES, 'UTF-8');

    // Validate inputs
    if (!preg_match('/^[A-Za-z0-9]{10}$/', $rollno)) {
        die('<script>alert("Invalid Index Number. It should be exactly 10 characters long and contain only letters and numbers."); window.location.href="registration.html";</script>');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('<script>alert("Invalid email format."); window.location.href="registration.html";</script>');
    }

    if (strlen($password) < 8) {
        die('<script>alert("Password must be at least 8 characters long."); window.location.href="registration.html";</script>');
    }

    if ($password !== $cpassword) {
        die('<script>alert("Passwords do not match."); window.location.href="registration.html";</script>');
    }

    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        die('<script>alert("Contact number must be 10 digits."); window.location.href="registration.html";</script>');
    }

    // Check if roll number or email already exists
    $stmt = $conn->prepare("SELECT * FROM student WHERE rollno = ? OR email = ?");
    $stmt->bind_param("ss", $rollno, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count > 0) {
        die('<script>alert("This roll number or email is already registered."); window.location.href="registration.html";</script>');
    }

    // Insert new student record with plain text password
    $stmt = $conn->prepare("INSERT INTO student (rollno, name, contact, email, hostel, course, password, active) VALUES (?, ?, ?, ?, ?, ?, ?, 'n')");
    $stmt->bind_param("sssssss", $rollno, $name, $contact, $email, $hostel, $course, $password);

    if (!$stmt->execute()) {
        $error = $stmt->error;
        die("<script>alert('Registration failed: $error'); window.location.href='registration.html';</script>");
    }

    // Send activation email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = EMAIL_ACC;
        $mail->Password   = EMAIL_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        // Recipients
        $mail->setFrom(EMAIL_ACC, 'KTU Complaint Hub');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Activate Your KTU Complaint Hub Account';
        $activation_link = BASE_URL . '/activate.php?email=' . urlencode($email) . '&token=' . bin2hex(random_bytes(16));
        $mail->Body    = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <h2>Welcome to KTU Complaint Hub, $name!</h2>
            <p>Thank you for registering with us. To activate your account, please click the button below:</p>
            <p style='text-align: center;'>
                <a href='$activation_link' style='display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Activate Your Account</a>
            </p>
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p>$activation_link</p>
            <p>If you didn't register for a KTU Complaint Hub account, please ignore this email.</p>
            <p>Best regards,<br>The KTU Complaint Hub Team</p>
        </body>
        </html>";

        $mail->send();
        echo '<script>alert("Registered successfully! Please check your email to activate your account."); window.location.href="index.html";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Registered successfully, but failed to send activation email. Error: ' . $mail->ErrorInfo . '"); window.location.href="index.html";</script>';
    }
} else {
    header("Location: registration.html");
    exit();
}
?>
