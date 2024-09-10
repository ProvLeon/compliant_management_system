<?php
// Include the config file
require_once __DIR__ . '/config.php';

// Include the connection file
require_once __DIR__ . '/connection.php';

// Check if the connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_GET['usercode'])){
    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE `student` SET `active` = 'y' WHERE `student`.`email` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET['usercode']);
    $res = mysqli_stmt_execute($stmt);

    if($res){
        header("Location: active.php?activated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

if(isset($_GET['activated'])){
    echo '<script type="text/javascript">alert("Activation Successful!!!")</script>';
    header("Refresh: 0; URL=" . BASE_URL);
    exit();
} else {
    echo 'Cant Login ...:(';
}

// Close the database connection
mysqli_close($conn);
