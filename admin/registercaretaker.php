<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../connection.php';

$conn = Connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $tid = filter_var($_POST['tid'], FILTER_SANITIZE_NUMBER_INT); // Assuming tid should be an integer
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $ctype = htmlspecialchars($_POST['ctype'], ENT_QUOTES, 'UTF-8');
    $contact = htmlspecialchars($_POST['contact'], ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Consider hashing this password

    // Validate that $conn is available
    if (!isset($conn) || !$conn) {
        die("Database connection not available.");
    }

    $stmt = $conn->prepare("INSERT INTO caretaker (tid, name, ctype, contact, address, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("issssss", $tid, $name, $ctype, $contact, $address, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Caretaker registered successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error registering caretaker: " . $stmt->error . "'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
