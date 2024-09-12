<?php
include('connection.php');

$conn = Connect();

session_start();
	$sid = $_SESSION['userMail'];
	$email = $_POST['email'];
	$ctype = $_POST['type'];
	$cby = $_POST['Cby'];
	$status = 'pending';
	$description = $_POST['description'];

    	$sql1="insert into complaint (description,sid,type,SEmail,status,Cby) values('$description','$sid','$ctype','$email','$status','$cby')";
		if ($conn->query($sql1) === TRUE)
		{
    	echo '<script type=text/javascript> setTimeout(function(){alert("Complaint Registered successfully!!!!!")}, 3000)</script>';
    	}

	else
	{
		echo '<script type=text/javascript> alert("Error While Registering!!!!!......login Now.")</script>';
	}
    	$sql= "select cid from complaint";
    	$res=mysqli_query($conn,$sql);
		$count=mysqli_num_rows($res);
		$i=1;
		$var_cid="";
		while($row=mysqli_fetch_assoc($res))
		{
				if($i==$count){
				$var_cid=$row['cid'];}
				$i+=1;

		}
		//echo $i."<br>".$count;
    	echo "<script type=text/javascript> alert('Thank You For Complaint  .Your Complaint no is ".$var_cid."')</script>";

  header ("Location: student/index.php?loginDone");
	exit();
?>
