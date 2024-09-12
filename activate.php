<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/connection.php';

$conn = Connect();

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE `student` SET `active` = 'y' WHERE `student`.`email` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    $res = mysqli_stmt_execute($stmt);

    if($res){
        echo '<script type="text/javascript">alert("Activation Successful!!!\n\nPlease Login")</script>';
        header("Refresh: 0; URL=" . BASE_URL);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    echo 'Invalid activation link';
}

mysqli_close($conn);
?>
