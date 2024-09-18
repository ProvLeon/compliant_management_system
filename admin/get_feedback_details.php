<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['fid'])) {
    $conn = Connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT * FROM feedback WHERE fid = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $_GET['fid']);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if($feedback = $result->fetch_assoc()) {
        echo "<div class='feedback-details'>";
        echo "<h5>Feedback ID: " . htmlspecialchars($feedback['fid']) . "</h5>";
        echo "<p><strong>Student ID:</strong> " . htmlspecialchars($feedback['sid']) . "</p>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($feedback['name']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($feedback['email']) . "</p>";
        echo "<p><strong>Feedback Description:</strong></p>";
        echo "<div class='feedback-description'>" . nl2br(htmlspecialchars($feedback['description'])) . "</div>";
        echo "</div>";

        // Add some inline styles
        echo "<style>
            .feedback-details {
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            .feedback-details h5 {
                color: #333;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
                margin-bottom: 15px;
            }
            .feedback-details p {
                margin-bottom: 10px;
            }
            .feedback-description {
                background-color: #f9f9f9;
                border-left: 3px solid #007bff;
                padding: 15px;
                margin-top: 10px;
            }
        </style>";
    } else {
        echo "<p class='text-danger'>Feedback not found.</p>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<p class='text-danger'>Invalid request. No feedback ID provided.</p>";
}
?>
