<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_POST['deleteId'])) {
    $conn = Connect();
    $deleteId = $_POST['deleteId'];

    $stmt = $conn->prepare("DELETE FROM `complaint` WHERE `cid` = ?");
    $stmt->bind_param("i", $deleteId);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Complaint deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting complaint']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
