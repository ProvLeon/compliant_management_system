<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['passwd'];

    if ($email && $password) {
        $sql = "SELECT `email`, `pass` FROM `admin` WHERE `email` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['pass'])) {
            $_SESSION['admin_email'] = $email;
            header('Location: index.php?loginDone');
            exit();
        } else {
            $error = "Invalid login credentials.";
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
