<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// set_include_path(get_include_path() . PATH_SEPARATOR . 'vendor/phpmailer/phpmailer/src');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'Exception.php';
// require 'PHPMailer.php';
// require 'SMTP.php';


include ('student/sendMail/PHPMailerAutoload.php');
include('connection.php');

$rollno = $_POST['rollno'];
$name = $_POST['name'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$hostel =$_POST['hostel'];
$course =$_POST['course'];

if($password == $cpassword) {
	$sql = "select * from student where rollno='$rollno'";
	$res = mysqli_query($conn,$sql);
	$count = mysqli_num_rows($res);

	if($count > 0) {
		echo '<script type="text/javascript">alert("You are already registered!!!!!......login Now.");
		location.href="index.html";</script>';
	} else {
		$sql1 = "insert into student (rollno,name,contact,email,hostel,course,password,active) values('$rollno','$name','$contact','$email','$hostel','$course','$password','n')";

		if(mysqli_query($conn,$sql1)) {
			echo '<script type="text/javascript">alert("Registered successfully!!!!!......login Now.")</script>';

			$mail = new PHPMailer();

			try {
				//Server settings
				$mail->SMTPDebug = 0;                      
				$mail->isSMTP();                                           
				$mail->Host       = 'smtp.gmail.com';                    
				$mail->SMTPAuth   = true;                                   
				$mail->Username   = 'neilohene@gmail.com';                    
				$mail->Password   = 'cmtkbjexpyicmpxh';                 //    Gmail uses App passwords now, so set one up          
				$mail->SMTPSecure = 'ssl';                            
				$mail->Port       = 465;                                   

				//Recipients
				$mail->setFrom('KTUComplaintHUB@ktu.edu.gh', 'KTUComplaintHUB2024');
				$mail->addAddress($email);     // Add a recipient

				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Activate Your Account';
				$mail->Body    = '<h1>Hello </h1> '.$name.'..!<br/>You are registered, please click the link below</h1><br/><a href="http://localhost:3000/active.php?usercode='.$_POST['email'].'">Activate Now</a>';

				$mail->send();
				echo 'Email sent successfully.';
			} catch (Exception $e) {
				echo 'Failed to send email. Error: ', $mail->ErrorInfo;
			}

			header("Refresh : 0; URL=index.html");
		}
	}
} else {
	echo '<script type="text/javascript">alert("Password not matched !!!!!......login Now.")</script>';
	header("Location: index.html");
	exit();
}
