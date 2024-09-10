<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['caretaker_login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if ($email && $password) {
        $sql = "SELECT `email`, `password` FROM `caretaker` WHERE `email` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $caretaker = $result->fetch_assoc();

        if ($caretaker && password_verify($password, $caretaker['password'])) {
            $_SESSION['caretaker_email'] = $email;
            header('Location: index.php?loginDone');
            exit();
        } else {
            $error = "Invalid caretaker credentials.";
        }
    } else {
        $error = "Please enter both email and password.";
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
