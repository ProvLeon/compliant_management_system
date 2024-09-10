<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_login'])) {
    $rollno = filter_input(INPUT_POST, 'rollno', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    if ($rollno && $password) {
        $sql = "SELECT `rollno`, `password`, `active` FROM `student` WHERE `rollno` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $rollno);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student && password_verify($password, $student['password'])) {
            if ($student['active'] === 'y') {
                $_SESSION['student_rollno'] = $rollno;
                header('Location: index.php?loginDone');
                exit();
            } else {
                $error = "Please activate your account before logging in.";
            }
        } else {
            $error = "Invalid login credentials.";
        }
    } else {
        $error = "Please enter both roll number and password.";
    }

    if (isset($error)) {
        $_SESSION['login_error'] = $error;
        header('Location: ../index.html');
        exit();
    }
}

header('Location: ../index.html');
exit();
?>
