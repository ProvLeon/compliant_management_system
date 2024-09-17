<?php
session_start();

if(!isset($_SESSION['userMail'])){
    header('Location: ../index.html');
    exit();
}

require_once(__DIR__ . '/../connection.php');

$conn = Connect();

// Check if $conn is defined and is a valid mysqli connection
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Database connection failed. Please check your connection file.");
}

// Fetch caretaker type
$sql_query = "SELECT ctype FROM caretaker WHERE email = ?";
$stmt = $conn->prepare($sql_query);
$stmt->bind_param("s", $_SESSION['userMail']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $_SESSION['ajay'] = $row['ctype'];
}
$stmt->close();

function show_forward_form(){
    echo '<form action="index.php" method="GET" >
                <table class="table">
                    <tr>
                      <td>Forward To </td>
                      <td><input class="form-control" type="text" name="email" placeholder="Enter Email Address to forward to "></td>
                    </tr>

                     <tr>
                      <td ></td>
                      <td><button type="submit" class="btn btn-danger"  name="forwardd" >Forward Now </button></td>
                    </tr>
              </table>
          <form>';

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome Caretaker</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<style>
    .navbar{background-color: black;}
    .navbar-header{background-color:black;}
    .nav li{background-color:black;}
</style>
<style>
.complaint-list {
    border: 1px solid #ddd;
    max-height: 500px;
    overflow-y: auto;
}
.complaint-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.complaint-item:hover {
    background-color: #f5f5f5;
}
.complaint-main {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.complaint-subject {
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.complaint-student {
    font-size: 0.9em;
    color: #666;
}
.complaint-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.complaint-status, .complaint-date {
    margin-left: 10px;
    white-space: nowrap;
}
.status-pending { color: #f29900; }
.status-approved { color: #0f9d58; }
.status-discard { color: #db4437; }

.feedback-list {
    border: 1px solid #ddd;
    max-height: 500px;
    overflow-y: auto;
}
.feedback-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.feedback-item:hover {
    background-color: #f5f5f5;
}
.feedback-main {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.feedback-subject {
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.feedback-student {
    font-size: 0.9em;
    color: #666;
}
.feedback-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.feedback-email {
    font-size: 0.9em;
    color: #1a73e8;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php" style="background-color:black;font-size:19px;"><i class="fa fa-user-circle" aria-hidden="true">&nbsp;</i><?php echo $_SESSION['userMail'];?></a>
            </div>

        <div style="color: white;
            padding: 15px 50px 5px 50px;
            float: right;
            font-size: 16px;">
        <div id="txt" style="color:white;">

        </div><a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>

        </nav>

        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive"/>
					</li>

                     <li>
                        <a  href="index.php?listall" id="listAll"><i class="fa fa-desktop fa-2x"></i>List All Complaints</a>
                    </li>


                    <li>
                         <a  href="index.php?approvedall" id="approvedAll"><i class="fa fa-desktop fa-2x"></i>Approved Complaints</a>
                    </li>
                    <!-- <li>
                        <a  href="index.php?discardall" id="discardAll"><i class="fa fa-desktop fa-2x"></i>Discarded Complaints</a>
                    </li> -->
                     <li>
                        <a  href="index.php?pendingall" id="pendingAll"><i class="fa fa-desktop fa-2x"></i>Pending Complaints</a>
                    </li>
                    <li>
                        <a  href="index.php?feedbackall" id="feedbackAll"><i class="fa fa-desktop fa-2x"></i>List all Feedbacks</a>
                    </li>
                     <li>
                       <li>

                        <a href="#" id="search" data-toggle="modal" data-target="#myModalab"><i class="fa fa-desktop fa-2x"></i>Search Complaints</a>
                    </li>



                    </li>


                    <li>

                        <a id="careAll" data-toggle="modal" data-target="#myModala"><i class="fa fa-desktop fa-2x"></i>Change Password</a>
                    </li>



                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">



            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <?php
                        if(isset($_GET['listall']))
                        {
                            // // Debug information
                            // echo "Session ajay: " . (isset($_SESSION['ajay']) ? $_SESSION['ajay'] : "Not set") . "<br>";

                            $listAll = "SELECT c.*, s.name as student_name
                                        FROM `complaint` c
                                        LEFT JOIN `student` s ON c.sid = s.rollno
                                        WHERE c.type=?
                                        ORDER BY c.date DESC";
                            // echo "SQL Query: " . $listAll . "<br>";
                            // echo "Type: " . $_SESSION['ajay'] . "<br>";

                            $stmt = $conn->prepare($listAll);
                            $stmt->bind_param("s", $_SESSION['ajay']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // echo "Number of rows: " . $result->num_rows . "<br>";

                            if($result->num_rows > 0){
                                echo '<div class="panel-heading">
                                        <h2 align="center">List All Complaints</h2>
                                      </div>
                                      <div class="panel-body">
                                        <div class="complaint-list">';

                                while($temp = $result->fetch_assoc()){
                                    $shortDescription = mb_strlen($temp['description']) > 50 ?
                                                        mb_substr($temp['description'], 0, 47) . '...' :
                                                        $temp['description'];

                                    echo '<div class="complaint-item" data-cid="'.$temp['cid'].'">';
                                    echo '<div class="complaint-main">';
                                    echo '<span class="complaint-subject">'.htmlspecialchars($shortDescription).'</span>';
                                    echo '<span class="complaint-student">by '.htmlspecialchars($temp['Cby']).' (ID: '.htmlspecialchars($temp['sid']).')</span>';
                                    echo '</div>';
                                    echo '<div class="complaint-meta">';
                                    echo '<span class="complaint-status status-'.strtolower($temp['status']).'">'.$temp['status'].'</span>';
                                    echo '<span class="complaint-date">'.date('Y-m-d', strtotime($temp['date'])).'</span>';
                                    echo '</div>';
                                    echo '</div>';
                                }

                                echo '</div></div>';
                            } else {
                                echo '<div class="alert alert-info">No Complaints found</div>';
                            }

                            // // Additional debug information
                            // $checkComplaints = "SELECT COUNT(*) as count FROM complaint";
                            // $result = $conn->query($checkComplaints);
                            // $row = $result->fetch_assoc();
                            // echo "Total complaints in database: " . $row['count'] . "<br>";

                            // $checkComplaintsType = "SELECT COUNT(*) as count FROM complaint WHERE type=?";
                            // $stmt = $conn->prepare($checkComplaintsType);
                            // $stmt->bind_param("s", $_SESSION['ajay']);
                            // $stmt->execute();
                            // $result = $stmt->get_result();
                            // $row = $result->fetch_assoc();
                            // echo "Complaints with type '" . $_SESSION['ajay'] . "': " . $row['count'] . "<br>";

                            // $checkStudents = "SELECT COUNT(*) as count FROM student";
                            // $result = $conn->query($checkStudents);
                            // $row = $result->fetch_assoc();
                            // echo "Total students in database: " . $row['count'] . "<br>";
                        }                      ?>


<!--Approved Complaint starts here-->
 <div id="page-wrapper1" >
            <div id="page-inner1">

            <div class="row1">
                <div class="col-md-12 col-sm-12 col-xs-121">


                                    <?php
                                    if(isset($_GET['approvedall']))
                                    {
                                        $approvedAll = "SELECT c.*, s.name as student_name
                                                        FROM `complaint` c
                                                        LEFT JOIN `student` s ON c.sid = s.rollno
                                                        WHERE c.type=? AND c.status='approved'
                                                        ORDER BY c.date DESC";
                                        $stmt = $conn->prepare($approvedAll);
                                        $stmt->bind_param("s", $_SESSION['ajay']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if($result->num_rows > 0){
                                            echo '<div class="panel-heading">
                                                    <h2 align="center">Approved Complaints</h2>
                                                  </div>
                                                  <div class="panel-body">
                                                    <div class="complaint-list">';

                                            while($temp = $result->fetch_assoc()){
                                                $shortDescription = mb_strlen($temp['description']) > 50 ?
                                                                    mb_substr($temp['description'], 0, 47) . '...' :
                                                                    $temp['description'];

                                                echo '<div class="complaint-item" data-cid="'.$temp['cid'].'">';
                                                echo '<div class="complaint-main">';
                                                echo '<span class="complaint-subject">'.htmlspecialchars($shortDescription).'</span>';
                                                echo '<span class="complaint-student">by '.htmlspecialchars($temp['Cby']).' (ID: '.htmlspecialchars($temp['sid']).')</span>';
                                                echo '</div>';
                                                echo '<div class="complaint-meta">';
                                                echo '<span class="complaint-status status-'.strtolower($temp['status']).'">'.$temp['status'].'</span>';
                                                echo '<span class="complaint-date">'.date('Y-m-d', strtotime($temp['date'])).'</span>';
                                                echo '</div>';
                                                echo '</div>';
                                            }

                                            echo '</div></div>';
                                        } else {
                                            echo '<div class="alert alert-info">No Approved Complaints found</div>';
                                        }
                                    }                                    ?>

<!--ends here-->


<!--Discard Complaint starts here-->
 <div id="page-wrapper2" >
            <div id="page-inner2">



            <div class="row2">
                <div class="col-md-12 col-sm-12 col-xs-122">
                    <div class="panel panel-default2">


                                    <?php
                                        if(isset($_GET['discardall']))
                                        {

                                            $discardAll = "SELECT * FROM `complaint` WHERE type='".$_SESSION['ajay']."' and status='discard'";
                                            $result = $conn->query($discardAll);

                                              if($result->num_rows>0){

                                              		echo ' <div class="panel-heading2">
                            <h2 align="center">Discard Complaints </h2>
                        </div>

                        <div class="panel-body2">

                            <div class="" id="discardAll">';
                                                     echo '<table class="table ">';
                                                    echo '<tr><th>CID</th>
                                                            <th>Description</th>
                                                            <th>Type</th>
                                                            <th>SID</th>
                                                            <th>Student Email</th>
<th>Complaint By</th>
                                                            <th>Status</th>


                                                             <th>Edit</th>
                                                            <th>Delete</th>
                                                            <th>Reply</th><tr>';


                                                    while($temp = $result->fetch_assoc()){
                                                         echo  '<tr><td>'.$temp['cid'].'</td>';
                                                        echo  '<td>'.$temp['description'].'</td>';

                                                        echo  '<td>'.$temp['type'].'</td>';
                                                        echo  '<td>'.$temp['sid'].'</td>';
                                                        echo  '<td>'.$temp['SEmail'].'</td>';
 echo  '<td>'.$temp['Cby'].'</td>';

                                                        echo  '<form action="edit.php" method="POST">
                                                        		<td><select name="select_status">
                                                        			<option value="'.$temp['status'].'" selected>'.$temp['status'].'</option>
                                                        			<option value="approved">approved</option>
  																	<option value="pending">Pending</option>
  																	<option value="discard">discarded</option>
                   </select>
  																	</td>';

                                                        //echo  '<td>'.$temp['Cby'].'</td>';
                                                         // echo  '<td><a href="index.php?forwardId='.$temp['cid'].'">Forward</a></td>';
                                                       // echo  '<td><a href="index.php?EditId='.$temp['cid'].'">edit</a></td>';
                                                        echo  '<td><input type="hidden" name="hidden_cid" value="'.$temp['cid'].'"><input type="submit" name="edit" value="Edit" style="text-decoration:none;"></td></form>';

                                                        echo  '<td><a href="index.php?deleteId='.$temp['cid'].'">Delete </a></td>';
echo  '<td><a href="forward_mail.php?mail='.$temp['SEmail'].'">Mail </a></td></tr>';

                                                    }
                                                    echo '</table>';




                                                  } else{


                                                echo 'No Complaint is found ';
                                              }

                                        }
                                    ?>





<!--ends here-->

<!--Pending Complaint starts here-->
 <div id="page-wrapper3" >
            <div id="page-inner3">




            <div class="row3">
                <div class="col-md-12 col-sm-12 col-xs-123">
                    <div class="panel panel-default3">


                                    <?php
                                    if(isset($_GET['pendingall']))
                                    {
                                        $pendingAll = "SELECT c.*, s.name as student_name
                                                       FROM `complaint` c
                                                       LEFT JOIN `student` s ON c.sid = s.rollno
                                                       WHERE c.type=? AND c.status='pending'
                                                       ORDER BY c.date DESC";
                                        $stmt = $conn->prepare($pendingAll);
                                        $stmt->bind_param("s", $_SESSION['ajay']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if($result->num_rows > 0){
                                            echo '<div class="panel-heading">
                                                    <h2 align="center">Pending Complaints</h2>
                                                  </div>
                                                  <div class="panel-body">
                                                    <div class="complaint-list">';

                                            while($temp = $result->fetch_assoc()){
                                                $shortDescription = mb_strlen($temp['description']) > 50 ?
                                                                    mb_substr($temp['description'], 0, 47) . '...' :
                                                                    $temp['description'];

                                                echo '<div class="complaint-item" data-cid="'.$temp['cid'].'">';
                                                echo '<div class="complaint-main">';
                                                echo '<span class="complaint-subject">'.htmlspecialchars($shortDescription).'</span>';
                                                echo '<span class="complaint-student">by '.htmlspecialchars($temp['Cby']).' (ID: '.htmlspecialchars($temp['sid']).')</span>';
                                                echo '</div>';
                                                echo '<div class="complaint-meta">';
                                                echo '<span class="complaint-status status-'.strtolower($temp['status']).'">'.$temp['status'].'</span>';
                                                echo '<span class="complaint-date">'.date('Y-m-d', strtotime($temp['date'])).'</span>';
                                                echo '</div>';
                                                echo '</div>';
                                            }

                                            echo '</div></div>';
                                        } else {
                                            echo '<div class="alert alert-info">No Pending Complaints found</div>';
                                        }
                                    }
                                    ?>
<!--ends here-->
<!--feedback starts here-->
 <div id="page-wrapper1" >
            <div id="page-inner1">




            <div class="row1">
                <div class="col-md-12 col-sm-12 col-xs-121">
                    <div class="panel panel-default1">


                        <!-- List All Feedbacks -->
                        <?php
                        if(isset($_GET['feedbackall']))
                        {
                            $feedbackAll = "SELECT * FROM `feedback` ORDER BY fid DESC";
                            $result = $conn->query($feedbackAll);

                            if($result->num_rows > 0){
                                echo '<div class="panel-heading">
                                        <h2 align="center">List All Feedbacks</h2>
                                      </div>
                                      <div class="panel-body">
                                        <div class="feedback-list">';

                                while($temp = $result->fetch_assoc()){
                                    $shortDescription = mb_strlen($temp['description']) > 50 ?
                                                        mb_substr($temp['description'], 0, 47) . '...' :
                                                        $temp['description'];

                                    echo '<div class="feedback-item" data-fid="'.$temp['fid'].'">';
                                    echo '<div class="feedback-main">';
                                    echo '<span class="feedback-subject">'.htmlspecialchars($shortDescription).'</span>';
                                    echo '<span class="feedback-student">by '.htmlspecialchars($temp['name']).' (ID: '.htmlspecialchars($temp['sid']).')</span>';
                                    echo '</div>';
                                    echo '<div class="feedback-meta">';
                                    echo '<span class="feedback-email">'.$temp['email'].'</span>';
                                    echo '</div>';
                                    echo '</div>';
                                }

                                echo '</div></div>';
                            } else {
                                echo '<div class="alert alert-info">No Feedbacks found</div>';
                            }
                        }
                        ?>


<!--ends here-->

                                    <?php


                                    if(isset($_GET['deleteId']))
                                    {
                                        $stmt = $conn->prepare("DELETE FROM `complaint` WHERE `cid` = ?");
                                        $stmt->bind_param("s", $_GET['deleteId']);
                                        if($stmt->execute())
                                        {
                                            echo '<script type="text/javascript">alert("Complaint deleted successfully!!")</script>';
                                        }
                                        else
                                        {
                                            echo '<h2 align="center">Error While Deleting: ' . $conn->error . '</h2>';
                                        }
                                    }


                                  if(isset($_GET['deleteIdd']))
                                    {

                                        $temp = "DELETE FROM `feedback` WHERE `fid` = '".$_GET['deleteIdd']."'";
                                        if($conn->query($temp))
                                        {

                                              //echo '<h2 align="center">Complaint Deleted Succesully </h2>';
                                             echo '<script type=text/javascript> alert("Feedback deleted  successfully!!")</script>';

                                        }
                                        else{

                                                echo '<h2 align ="center">Error While Deleting  </h2>';
                                        }


                                    }


                                    if(isset($_GET['forwardId']))
                                    {

                                        show_forward_form();

                                    }

                                    //call Send Mail Function

                                    if(isset($_GET['forwardd']))
                                    {


                                    	$to  = $_GET['email'];
                                    	echo $_GET['email'];
										$subject = 'Complaint Managament System';
										$message = 'Medssege body ';
										$headers = 'From: E-complaint System <br/>';


                                        if(mail($to, $subject, $message, $headers)) {
                                            echo '<h1>Message has not been sent</h1>';
                                            header('Location:inedx.php?SendMailDone=yes');

                                        } else {
                                            echo 'Message could not be sent.';


                                        }
                                            //Send Mail Ends Here
                                    }



                                    if(isset($_GET['SendMailDone']))
                                    {

                                        if($_GET['SendMailDone'] == 'yes'){

                                               echo '<div class="alert alert-success alert-dismissable">Forward mail Done SuccessFully </div>';
                                        }else{

                                                echo '<div class="alert alert-danger alert-dismissable">Invalied Trial ...  </div>';
                                        }

                                    }
                                    ?>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


    </div>
            </div>
        </div>

<!-- Modal -->
  <div class="modal fade" id="myModala" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h3 align="center">Change Your Password !</h3>
        </div>
        <div class="modal-body">
         <form action="../caretaker_change_password.php" method="post">



        <fieldset>

          <label for="currentPassword">Old Password:</label>
          <input type="password" id="currentPassword" name="currentPassword"
class="form-control"required>
          <label for="newPassword">New Password:</label>
          <input type="password" id="newPassword" name="newPassword"
class="form-control"required>
          <label for="confirmPassword">Confirm Password :</label>
          <input type="password" id="confirmPassword" name="confirmPassword"
class="form-control"required>


        </fieldset>
        <br/>
        <button type="submit" name="submit"class="btn btn-danger">Change Your Password</button>
      </form>

        </div>

      </div>

    </div>
  </div>


<!-- Modal -->
<!-- Search Modal -->
<div class="modal fade" id="myModalab" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchModalLabel">Search Your Complaint</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="../caretaker_search.php" method="post">
          <fieldset>
            <div class="form-group">
              <label for="Cby">Student Name</label>
              <input type="text" id="Cby" name="Cby" class="form-control">
            </div>
            <div class="form-group">
              <label for="cid">Complaint ID</label>
              <input type="text" id="cid" name="cid" class="form-control">
            </div>
          </fieldset>

          <button type="submit" name="submit" class="btn btn-primary">Search Complaints</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Complaint Details Modal -->
<div class="modal fade" id="complaintDetailsModal" tabindex="-1" role="dialog" aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="complaintDetailsModalLabel">Complaint Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="complaintDetailsContent">
        <!-- Complaint details will be loaded here -->
      </div>
    </div>
  </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>

<script>
$(document).ready(function() {
    // Complaint item click handler
    $(document).on('click', '.complaint-item', function() {
        var cid = $(this).data('cid');
        $.ajax({
            url: 'get_complaint_details.php',
            type: 'GET',
            data: {cid: cid},
            success: function(response) {
                $('#complaintDetailsContent').html(response);
                $('#complaintDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                alert("An error occurred while fetching complaint details. Please try again.");
            }
        });
    });

    // Status change handler
    $(document).on('change', '#status-form select', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert(response.message);
                    // Optionally refresh the complaint list or update the status in the current view
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating the status.');
            }
        });
    });

    // // Ensure search modal opens
    // $('#search').on('click', function(e) {
    //     e.preventDefault();
    //     $('#myModalab').modal('show');
    // });
});
</script>
</body>
</html>
