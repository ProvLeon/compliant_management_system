<?php
session_start();
require_once(__DIR__ . '/connection.php');

$conn = Connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $rollno = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
    $current_password = $_POST['currentPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['confirmPassword'];

    if ($rollno && $current_password && $new_password && $confirm_password) {
        if ($new_password === $confirm_password) {
            $sql = "SELECT `password` FROM `student` WHERE `rollno` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $rollno);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();

            if ($student && password_verify($current_password, $student['password'])) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE `student` SET `password` = ? WHERE `rollno` = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $hashed_new_password, $rollno);

                if ($update_stmt->execute()) {
                    $_SESSION['password_change_success'] = "Password updated successfully.";
                    header('Location: index.html');
                    exit();
                } else {
                    $error = "Error updating password.";
                }
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "New password and confirm password do not match.";
        }
    } else {
        $error = "Please fill in all fields.";
    }

    if (isset($error)) {
        $_SESSION['password_change_error'] = $error;
        header('Location: sforget.html');
        exit();
    }
}

header('Location: index.html');
exit();
?>
