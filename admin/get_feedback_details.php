<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['fid'])) {
    $conn = Connect();
    $stmt = $conn->prepare("SELECT * FROM feedback WHERE fid = ?");
    $stmt->bind_param("i", $_GET['fid']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($feedback = $result->fetch_assoc()) {
        echo "<h5>Feedback ID: " . htmlspecialchars($feedback['fid']) . "</h5>";
        echo "<p><strong>Student ID:</strong> " . htmlspecialchars($feedback['sid']) . "</p>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($feedback['name']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($feedback['email']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($feedback['description']) . "</p>";
    } else {
        echo "Feedback not found.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
