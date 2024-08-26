<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($email) && !empty($name) && !empty($message)) {
        // Validate email address
        function is_valid_email($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/(content-type|bcc:|cc:|to:)/i', $email);
        }
        
        if (!is_valid_email($email)) {
            echo "Invalid email address.";
        } else {
            // Send email
            $to = "your-email";
            $subject = "Contact Form Submission";
            $headers = "From: " . htmlspecialchars($name) . " <" . htmlspecialchars($email) . ">\r\n";
            $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            $message = htmlspecialchars($message);

            if (mail($to, $subject, $message, $headers)) {
                echo "Email sent successfully.";
            } else {
                echo "Failed to send email.";
            }
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>
