<?php
session_start();
if(!isset($_SESSION['userMail'])){
    header('Location: ../index.html');
    exit();
}

 //$conn  = new mysqli('localhost','root','root','complaint_nitc17');
 // require_once __DIR__ . '/sendMail/PHPMailerAutoload.php';

 include('../connection.php');
$conn = Connect();

function show_forward_form(){
    echo '<form action="index.php" method="GET" >
                <table class="table">
                    <tr>
                      <td>Forward To </td>
                      <td><input class="form-control" type="text" name="email" placeholder="Enter Email Addredd to foreward to "></td>
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
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
        }
        .navbar {
            background-color: #343a40;
        }
        .sidebar {
            background-color: #343a40;
            min-height: 100vh;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .content {
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal-content {
            z-index: 1100 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark sticky-top flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Admin Panel</a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="logout.php">Sign out</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?listall">
                                <i class="fas fa-list-alt"></i> All Complaints
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?approvedall">
                                <i class="fas fa-check-circle"></i> Approved Complaints
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pendingall">
                                <i class="fas fa-clock"></i> Pending Complaints
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?feedbackall">
                                <i class="fas fa-comments"></i> Feedbacks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?caretakerall">
                                <i class="fas fa-users"></i> Caretakers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModala">
                                <i class="fas fa-user-plus"></i> Add Caretaker
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?dcareall">
                                <i class="fas fa-user-minus"></i> Delete Caretaker
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 content">
                <div class="card">
                    <div class="card-body">
                        <?php
                        // Function to generate a styled table for complaints
                        function generateComplaintTable($result, $title) {
                            if ($result->num_rows > 0) {
                                echo '<div class="card mb-4">
                                        <div class="card-header">
                                            <h4 class="mb-0">' . $title . '</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>CID</th>
                                                            <th>Complaint Description</th>
                                                            <th>Type</th>
                                                            <th>Student</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';

                                while ($temp = $result->fetch_assoc()) {
                                    $statusClass = getStatusClass($temp['status']);
                                    echo '<tr>
                                            <td>' . $temp['cid'] . '</td>
                                            <td>' . substr(htmlspecialchars($temp['description']), 0, 50) . '...</td>
                                            <td>' . $temp['type'] . '</td>
                                            <td>
                                                <div>' . $temp['Cby'] . '</div>
                                                <small class="text-muted">' . $temp['SEmail'] . '</small>
                                            </td>
                                            <td><span class="badge ' . $statusClass . '">' . ucfirst($temp['status']) . '</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-details" data-cid="' . $temp['cid'] . '">View</button>
                                                <button class="btn btn-sm btn-primary reply" data-email="' . $temp['SEmail'] . '">Reply</button>
                                            </td>
                                          </tr>';
                                }

                                echo '</tbody></table></div></div></div>';
                            } else {
                                echo '<div class="alert alert-info">No complaints found.</div>';
                            }
                        }

                        // Function to get appropriate status class for badges
                        function getStatusClass($status) {
                            switch (strtolower($status)) {
                                case 'approved':
                                    return 'badge-success';
                                case 'pending':
                                    return 'badge-warning';
                                case 'discarded':
                                    return 'badge-danger';
                                default:
                                    return 'badge-secondary';
                            }
                        }

                        // List All Complaints
                        if (isset($_GET['listall'])) {
                            $listAll = "SELECT * FROM `complaint` ORDER BY date DESC";
                            $result = $conn->query($listAll);
                            generateComplaintTable($result, "All Complaints");
                        }

                        // Approved Complaints
                        if (isset($_GET['approvedall'])) {
                            $approvedAll = "SELECT * FROM `complaint` WHERE status='approved' ORDER BY date DESC";
                            $result = $conn->query($approvedAll);
                            generateComplaintTable($result, "Approved Complaints");
                        }

                        // Pending Complaints
                        if (isset($_GET['pendingall'])) {
                            $pendingAll = "SELECT * FROM `complaint` WHERE status='pending' ORDER BY date DESC";
                            $result = $conn->query($pendingAll);
                            generateComplaintTable($result, "Pending Complaints");
                        }

                        // Feedback listing (you can create a similar function for feedback if needed)
                        if (isset($_GET['feedbackall'])) {
                            $feedbackAll = "SELECT * FROM `feedback` ORDER BY fid DESC";
                            $result = $conn->query($feedbackAll);

                            if ($result->num_rows > 0) {
                                echo '<div class="card mb-4">
                                        <div class="card-header">
                                            <h4 class="mb-0">All Feedbacks</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>FID</th>
                                                            <th>Student</th>
                                                            <th>Feedback Description</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';

                                while ($temp = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . $temp['fid'] . '</td>
                                            <td>
                                                <div>' . $temp['name'] . '</div>
                                                <small class="text-muted">' . $temp['email'] . '</small>
                                            </td>
                                            <td>' . substr(htmlspecialchars($temp['description']), 0, 50) . '...</td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-feedback" data-fid="' . $temp['fid'] . '">View</button>
                                            </td>
                                          </tr>';
                                }

                                echo '</tbody></table></div></div></div>';
                            } else {
                                echo '<div class="alert alert-info">No feedbacks found.</div>';
                            }
                        }
                        ?>


<!-- DELETE CARETAKER-->
  <!-- /. NAV SIDE  -->
        <div id="page-wrapper4" >
            <div id="page-inner4">



            <div class="row4">
                <div class="col-md-12 col-sm-12 col-xs-124">
                    <div class="panel panel-default4">


                                    <?php
                                        if(isset($_GET['dcareall']))
                                        {

                                            $dcareAll = "SELECT * FROM `caretaker` WHERE 1";
                                            $result = $conn->query($dcareAll);

                                              if($result->num_rows>0){
                                                echo'<div class="panel-heading4">
                            <h2 align="center">List All Caretakers</h2>
                        </div>

                        <div class="panel-body">

                            <div class="" id="dcareAll">';

                                                     echo '<table class="table ">';
                                                    echo '<tr><th>tid</th>
                                                            <th>name</th>
                                                            <th>ctype</th>
                                                            <th>contact</th>
                                                            <th>address</th>
                                                            <th>email</th>
                                                            <th>Password</th>

                                                            <th>Delete</th><tr>';


                                                    while($temp = $result->fetch_assoc()){
                                                         echo  '<tr><td>'.$temp['tid'].'</td>';
                                                        echo  '<td>'.$temp['name'].'</td>';

                                                        echo  '<td>'.$temp['ctype'].'</td>';
                                                        echo  '<td>'.$temp['contact'].'</td>';
                                                        echo  '<td>'.$temp['address'].'</td>';
                                                        echo  '<td>'.$temp['email'].'</td>';
                                                        echo  '<td>'.$temp['password'].'</td>';
                                                          //echo  '<td><a href="index.php?forwardId='.$temp['cid'].'">Forward</a></td>';
                                                       echo  '<td><a href="index.php?cdeleteId='.$temp['tid'].'">Delete </a></td><tr>';

                                                    }
                                                    echo '</table>';




                                                  } else{


                                                echo 'No Caretakers is found ';
                                              }

                                        }



                                    ?>

 <!--DELETE CARETAKER END HERE-->
 <!-- Show all caretakers Starts Here-->
   <div id="page-wrapper5" >
            <div id="page-inner5">



            <div class="row5">
                <div class="col-md-12 col-sm-12 col-xs-125">
                    <div class="panel panel-default5">


                                    <?php
                                        if(isset($_GET['caretakerall']))
                                        {

                                            $caretakerAll = "SELECT * FROM `caretaker` WHERE 1";
                                            $result = $conn->query($caretakerAll);

                                              if($result->num_rows>0){
                                                echo'<div class="panel-heading4">
                            <h2 align="center">List All Caretakers</h2>
                        </div>

                        <div class="panel-body">

                            <div class="" id="dcareAll">';

                                                     echo '<table class="table ">';
                                                    echo '<tr><th>tid</th>
                                                            <th>name</th>
                                                            <th>ctype</th>
                                                            <th>contact</th>
                                                            <th>address</th>
                                                            <th>Caretaker email</th>
                                                            <th>Password</th>
                                                            <th>Reply</th>
                                                            <tr>';


                                                    while($temp = $result->fetch_assoc()){
                                                         echo  '<tr><td>'.$temp['tid'].'</td>';
                                                        echo  '<td>'.$temp['name'].'</td>';

                                                        echo  '<td>'.$temp['ctype'].'</td>';
                                                        echo  '<td>'.$temp['contact'].'</td>';
                                                        echo  '<td>'.$temp['address'].'</td>';
                                                        echo  '<td>'.$temp['email'].'</td>';
                                                        echo  '<td>'.$temp['password'].'</td>';

                                                          echo  '<td><a href="forward_mail.php?mail='.$temp['email'].'">Mail </a></td></tr>';
                                                       //echo  '<td><a href="index.php?cdeleteId='.$temp['tid'].'">Delete </a></td><tr>';

                                                    }
                                                    echo '</table>';




                                                  } else{


                                                echo 'No Caretakers is found ';
                                              }

                                        }



                                    ?>
 <!-- End here-->
<!--ends here-->




                                    <?php


                                    if(isset($_GET['deleteId']))
                                    {

                                        $temp = "DELETE FROM `complaint` WHERE `cid` = '".$_GET['deleteId']."'";
                                        if($conn->query($temp))
                                        {

                                             // echo '<h2 align="center">Complaint Deleted Succesully </h2>';
                                             echo '<script type=text/javascript> alert("Complaint deleted  successfully!!")</script>';

                                        }
                                        else{

                                                echo '<h2 align ="center">Error While Deleting  </h2>';
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




                                    if(isset($_GET['cdeleteId']))
                                    {

                                        $temp = "DELETE FROM `caretaker` WHERE `tid` = '".$_GET['cdeleteId']."'";
                                        if($conn->query($temp))
                                        {

                                             // echo '<h2 align="center">Complaint Deleted Succesully </h2>';
                                             echo '<script type=text/javascript> alert("Caretaker deleted  successfully!!")</script>';

                                        }
                                        else{

                                                echo '<h2 align ="center">Error While Deleting  </h2>';
                                        }


                                    }
                                         //ends here caretaker delete code


                                    if(isset($_GET['forwardId']))
                                    {

                                        show_forward_form();

                                    }

                                    //call Send Mail Function

                                    // if(isset($_GET['forwardd']))
                                    // {

                                        // require 'sendMail/PHPMailerAutoload.php';
                                        // require_once __DIR__ . '/../config.php';

                                        // $mail = new PHPMailer;
                                        // $mail->isSMTP();
                                        // $mail->Host = SMTP_HOST;
                                        // $mail->SMTPAuth = true;
                                        // $mail->Username = EMAIL_ACC;
                                        // $mail->Password = EMAIL_PASSWORD;
                                        // $mail->SMTPSecure = SMTP_SECURE;
                                        // $mail->Port = SMTP_PORT;
                                        // $mail->addAddress('amitamora@gmail.com', 'SENDER NAME HERE');
                                        // $mail->isHTML(true);
                                        // $mail->Subject = 'SOME TEXT HERE';
                                        // $mail->Body    = 'BODY HERE';
                                        // $mail->AltBody = 'ALTERNATE TEXT HERE';

                                        // if(!$mail->send()) {
                                        //     echo 'Message could not be sent.';
                                        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
                                        // } else {
                                        //     echo '<h1>Message has been sent</h1>';
                                        // }
                                        // if(!mail('amitamora@gmail.com','kuchh ni ','No msg ','No header ','No prama ')) {
                                        //     echo 'Message could not be sent.';
                                        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
                                        // } else {
                                        //     echo '<h1>Message has been sent</h1>';
                                        //    // header('Location:inedx.php?SendMailDone=yes');
                                        // }
                                            //Send Mail Ends Here
                                    // }



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
           <h3 align="center">Caretaker Registration Form !</h3>
        </div>
        <div class="modal-body">
          <form action="registercaretaker.php" method="post">



        <fieldset>

          <label for="rollno">Caretaker id:</label>
          <input type="text" id="tid" name="tid" class="form-control" required>
            <label for="rollno">Name:</label>
          <input type="text" id="name" name="name" class="form-control" required>

          <label for="ctype">Type:</label>
        <select id="ctype" name="ctype" class="form-control" required>
          <optgroup label="type">
          <option value="Hostel">Hostel</option >
            <option value="Academics">Academics</option>
            <option value="Harrassment">Harrassment</option>
            <option value="Other">Others</option></optgroup></select>
           <label for="contact">Contact:</label>
          <input type="text" id="contact" name="contact" class="form-control" required>
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" class="form-control" required>
          <label for="address">Email:</label>
          <input type="email" id="email" name="email" class="form-control" required>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" class="form-control" required>

        </fieldset>
       <br/>
        <button type="submit" class="btn btn-danger">Register Now</button>
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
              <div class="modal-body" id="complaintDetailsBody">
                  <!-- Complaint details will be loaded here -->
              </div>
          </div>
      </div>
  </div>

  <!-- Reply Modal -->
  <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="replyModalLabel">Reply to Complaint</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form id="replyForm">
                      <input type="hidden" id="replyEmail" name="email">
                      <input type="hidden" id="replyCid" name="cid">
                      <div class="form-group">
                          <label for="replyMessage">Message:</label>
                          <textarea class="form-control" id="replyMessage" name="message" rows="4" required></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Send Reply</button>
                  </form>
              </div>
          </div>
      </div>

      <!-- Feedback Details Modal -->
      <div class="modal fade" id="feedbackDetailsModal" tabindex="-1" role="dialog" aria-labelledby="feedbackDetailsModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="feedbackDetailsModalLabel">Feedback Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body" id="feedbackDetailsBody">
                      <!-- Feedback details will be loaded here -->
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>


      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
  $(document).ready(function() {
      // View complaint details
      $(document).on('click', '.view-details', function() {
          var cid = $(this).data('cid');
          $.ajax({
              url: 'get_complaint_details.php',
              type: 'GET',
              data: { cid: cid },
              success: function(response) {
                  $('#complaintDetailsBody').html(response);
                  $('#complaintDetailsModal').modal('show');
              },
              error: function() {
                  alert('Error fetching complaint details.');
              }
          });
      });

      // Reply to complaint
      $(document).on('click', '.reply', function() {
          var email = $(this).data('email');
          var cid = $(this).closest('tr').find('td:first').text();
          $('#replyEmail').val(email);
          $('#replyCid').val(cid);
          $('#replyModal').modal('show');
      });

      // View feedback details
      // Manual trigger for testing
      $('body').append('<button id="testFeedbackModal" class="btn btn-primary mt-3">Test Feedback Modal</button>');

      $('#testFeedbackModal').on('click', function() {
          console.log('Test button clicked');
          $('#feedbackDetailsBody').html('This is a test message.');
          $('#feedbackDetailsModal').modal('show');
      });

      // View feedback details
      $(document).on('click', '.view-feedback', function(e) {
          e.preventDefault();
          console.log('View feedback clicked');
          var fid = $(this).data('fid');
          console.log('FID:', fid);

          // Immediate modal show for testing
          $('#feedbackDetailsBody').html('Loading feedback details...');
          // $('#feedbackDetailsModal').modal('show');
          $('#feedbackDetailsModal').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
          });

          $.ajax({
              url: 'get_feedback_details.php',
              type: 'GET',
              data: { fid: fid },
              success: function(response) {
                  console.log('AJAX Success');
                  $('#feedbackDetailsBody').html(response);
              },
              error: function(xhr, status, error) {
                  console.error("AJAX Error: " + status + "\nError: " + error);
                  console.log(xhr.responseText);
                  $('#feedbackDetailsBody').html('Error loading feedback details.');
              }
          });
      });

      // Modal event listeners for debugging
      $('#feedbackDetailsModal').on('show.bs.modal', function () {
          console.log('Modal show event triggered');
      });

      $('#feedbackDetailsModal').on('shown.bs.modal', function () {
          console.log('Modal shown event triggered');
      });


      // Submit reply form
      $('#replyForm').submit(function(e) {
          e.preventDefault();
          $.ajax({
              url: 'send_reply.php',
              type: 'POST',
              data: $(this).serialize(),
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      alert(response.message);
                      $('#replyModal').modal('hide');
                  } else {
                      alert('Error: ' + response.message);
                  }
              },
              error: function() {
                  alert('An error occurred while sending the reply.');
              }
          });
      });

      // $('#feedbackDetailsModal').modal({
      //     show: true,
      //     backdrop: 'static',
      //     keyboard: false
      // });
  });
  </script>

</body>
</html>
