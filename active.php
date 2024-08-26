<?php

// $conn = mysqli_connect("localhost","root","dbsm","id1382631_cmsnitc17");
include 'connection.php';

if(isset($_GET['usercode'])){

	$sql="UPDATE `student` SET `active` = 'y' WHERE `student`.`email` ='".$_GET['usercode']."'";
	$res=mysqli_query($conn, $sql);
	if($res){

		header("Location:active.php?activated");
	}

}

if(isset($_GET['activated'])){
	echo '<script type=text/javascript> alert("Activation Successful!!!")</script>';
						header("Refresh : 0; URL=http://localhost:3000/");
}
else{
	echo 'Cant Login ...:(';
}
