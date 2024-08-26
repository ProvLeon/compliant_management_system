<?php
// Email Submit
if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Sanitize email to prevent header injections
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Construct email headers
    $headers = "From: $email\r\n" .
               "Reply-To: $email\r\n" .
               "X-Mailer: PHP/" . phpversion();
    
    // Send email
    $recipient = "neilohene@gmail.com"; // Replace with your email
    $subject = "New message from website contact form";
    $message = "Email: $email";
    
    if (mail($recipient, $subject, $message, $headers)) {
        echo "Your message has been sent!";
    } else {
        echo "Oops! Something went wrong.";
    }
}
?>
