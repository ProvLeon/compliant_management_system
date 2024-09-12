<?php
session_start();
require_once 'connection.php';

$conn = Connect();

$db = mysqli_select_db($conn, 'rick');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = mysqli_real_escape_string($conn, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);

    $query = "SELECT * FROM student WHERE rollno='" . $_SESSION['rollno'] . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($currentPassword, $row['password'])) {
        $query = "UPDATE student SET password='" . password_hash($newPassword, PASSWORD_DEFAULT) . "' WHERE userId='" . $_SESSION['userId'] . "'";
        mysqli_query($conn, $query);
        $message = "Password Changed";
    } else {
        $message = "Current Password is not correct";
    }
}
?>
