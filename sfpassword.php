<?php
session_start();
include 'dbconfig.php';
include 'connection.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$newpassword = $_POST['newpassword'] ?? '';
$confirmnewpassword = $_POST['confirmnewpassword'] ?? '';

$result = mysqli_query($conn, "SELECT password FROM student WHERE rollno='$username'");

if (!$result) {
  echo "The username you entered does not exist";
} else if ($password != mysqli_fetch_assoc($result)['password']) {
  echo "You entered an incorrect password";
} else if ($newpassword != $confirmnewpassword) {
  echo "Passwords do not match";
} else {
  $sql = mysqli_query($conn, "UPDATE student SET password='$newpassword' WHERE rollno='$username'");
  if ($sql) {
    echo "Congratulations! You have successfully changed your password";
  } else {
    echo "Failed to update password";
  }
}
?>
