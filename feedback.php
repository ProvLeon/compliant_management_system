<?php
// feedback.php
require_once __DIR__ . '/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Prepare and execute the query to check if the student exists
    $stmt = $conn->prepare("SELECT rollno FROM student WHERE rollno = ? AND email = ?");
    $stmt->bind_param("ss", $sid, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $stmt->close();

    if ($count > 0) {
        $fid = mt_rand(1, 10000);

        // Prepare and execute the query to insert feedback
        $stmt = $conn->prepare("INSERT INTO feedback (sid, name, email, description, fid) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $sid, $name, $email, $description, $fid);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Thank you for your feedback. Your Feedback ID is ' . $fid . '");</script>';
            echo '<script type="text/javascript">window.location.href = "' . BASE_URL . '";</script>';
        } else {
            echo '<script type="text/javascript">alert("Error submitting feedback. Please try again.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script type="text/javascript">alert("Invalid User! Please login.");</script>';
        echo '<script type="text/javascript">window.location.href = "' . BASE_URL . '";</script>';
    }
} else {
    // If the request method is not POST, redirect to the home page
    header("Location: " . BASE_URL);
    exit();
}
?>
