<?php
session_start();
require_once(__DIR__ . '/../connection.php');

$conn = Connect();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['admin_login'])) {
    $mail = trim($_POST['email']);
    $pass = trim($_POST['passwd']);

    $sql = "SELECT `email` FROM `admin` WHERE `email`=? AND `pass`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $mail, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['userMail'] = $mail;
        $_SESSION['userPass'] = $pass;
        header('Location: index.php?loginDone');
        exit();
    } else {
        echo '<script type="text/javascript">alert("Invalid login. Try Again");</script>';
        echo '<script type="text/javascript">window.location.href = "../index.html";</script>';
    }
}
?>
