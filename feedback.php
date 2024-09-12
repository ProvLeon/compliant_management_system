<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $sid = htmlspecialchars(trim($_POST['sid']), ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');

    if (!$email) {
        die("Invalid email address");
    }

    // Generate a random feedback ID
    $fid = mt_rand(1, 10000);

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO feedback (fid, sid, name, email, description) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }

    $stmt->bind_param("issss", $fid, $sid, $name, $email, $description);

    try {
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Thank you for your feedback. Your Feedback ID is " . $fid . "');</script>";
            echo "<script type='text/javascript'>window.location.href = 'index.html';</script>";
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<script type='text/javascript'>alert('Error submitting feedback: " . $e->getMessage() . "');</script>";
        echo "<script type='text/javascript'>window.location.href = 'index.html';</script>";
    }

    $stmt->close();
} else {
    header("Location: index.html");
    exit();
}
?>
