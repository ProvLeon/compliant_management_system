	<?php
	//$conn = mysqli_connect("localhost","root","root","complaint_nitc17") or die("data base not connected");
	include('connection.php');
	$conn = Connect();

		if(isset($_POST['submit']))
		{
		//$un=$_POST['userName'];
		$cp=$_POST['currentPassword'];
		$np=$_POST['newPassword'];
		$cfp=$_POST['confirmPassword'];
		$sql_query="select * from student where password='$cp'";
		$r=mysqli_query($conn,$sql_query);
		$result=mysqli_fetch_assoc($r);
		if($np==$cfp)
		{
			$rw=mysqli_num_rows($r);
			echo $rw;

			if($result["password"]==$cp)
			{
					$update_password="UPDATE student SET password='$np' WHERE password='$cp'";
					if(mysqli_query($conn,$update_password))
					{
						echo '<script type=text/javascript> alert("Password Updated Sucessfully!!!")</script>';
						header("Location: student/index.php?loginDone");
					//echo "update_password_sucessfully";
					}
					else
					{
						echo '<script type=text/javascript> alert("Error While Updating!!!")</script>';
						header("Location: student/index.php?loginDone");
						//echo "Query not execute";
					}
			}
			else
			{
				echo '<script type=text/javascript> alert("old Password Not Correct!!!")</script>';
				header("Location: student/index.php?loginDone");
				//echo "Sorry !!! YOUR CURRENT PASSWORD IS NOT MATCH WITH DATA BASE";
			}

		}


	else
	{
		echo '<script type=text/javascript> alert("New Password & confirmPassword  Not Matched!!!")</script>';
		header("Location: student/index.php?loginDone");
		//echo "CONFORM PASSWORD OT MATCHED";
	}
}
	?>
