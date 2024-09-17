<?php
session_start();
require_once __DIR__ . '/../connection.php';

$conn = Connect();

if(isset($_POST['edit']) && isset($_POST['hidden_cid']) && isset($_POST['select_status'])) {
    $c_id = $_POST['hidden_cid'];
    $stats = $_POST['select_status'];

    $stmt = $conn->prepare("UPDATE complaint SET status=? WHERE cid=?");
    $stmt->bind_param("si", $stats, $c_id);

    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status Updated Successfully!']);
        header("Refresh: 0; URL=" . BASE_URL . '/caretaker/index.php');
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
