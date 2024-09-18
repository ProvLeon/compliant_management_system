<?php
session_start();
if (!isset($_SESSION['userMail'])) {
    header('location:student/login.php');
    exit();
}

require_once(__DIR__ . '/../connection.php');
$conn = Connect();
$userMail = $_SESSION['userMail'];

  function show_forward_form()
  {
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
  <html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Student Dashboard - Complaint Management System</title>
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
              margin-bottom: 20px;
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
          .status-badge {
              padding: 0.25em 0.6em;
              font-size: 75%;
              font-weight: 700;
              border-radius: 0.25rem;
          }
          .status-pending { background-color: #ffc107; color: #212529; }
          .status-approved { background-color: #28a745; color: #fff; }
          .status-discarded { background-color: #dc3545; color: #fff; }
      </style>
  </head>
  <body>
      <nav class="navbar navbar-dark sticky-top flex-md-nowrap p-0">
          <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Student Dashboard</a>
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
                              <a class="nav-link" href="#" data-toggle="modal" data-target="#createComplaintModal">
                                  <i class="fas fa-plus-circle"></i> Create Complaint
                              </a>
                          </li>
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
                              <a class="nav-link" href="#" data-toggle="modal" data-target="#changePasswordModal">
                                  <i class="fas fa-key"></i> Change Password
                              </a>
                          </li>
                      </ul>
                  </div>
              </nav>

              <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 content">
                  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                      <h1 class="h2">Dashboard</h1>
                  </div>

                  <?php
                  // Function to generate complaint table
                  function generateComplaintTable($result, $title) {
                      if ($result->num_rows > 0) {
                          echo '<div class="card">
                                  <div class="card-header">
                                      <h5 class="mb-0">' . $title . '</h5>
                                  </div>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-hover">
                                              <thead>
                                                  <tr>
                                                      <th>CID</th>
                                                      <th>Complaint Description</th>
                                                      <th>Type</th>
                                                      <th>Status</th>
                                                      <th>Date</th>
                                                      <th>Actions</th>
                                                  </tr>
                                              </thead>
                                              <tbody>';
                          while ($row = $result->fetch_assoc()) {
                              echo '<tr>
                                      <td>' . $row['cid'] . '</td>
                                      <td>' . substr($row['description'], 0, 50) . '...</td>
                                      <td>' . $row['type'] . '</td>
                                      <td><span class="status-badge status-' . strtolower($row['status']) . '">' . $row['status'] . '</span></td>
                                      <td>' . $row['date'] . '</td>
                                      <td>
                                          <button class="btn btn-sm btn-info btn-action view-complaint" data-cid="' . $row['cid'] . '">View</button>
                                      </td>
                                    </tr>';
                          }
                          echo '</tbody></table></div></div></div>';
                      } else {
                          echo '<div class="alert alert-info">No complaints found.</div>';
                      }
                  }

                  // List All Complaints
                  if (isset($_GET['listall'])) {
                      $listAll = "SELECT * FROM complaint WHERE sid='$userMail' ORDER BY date DESC";
                      $result = $conn->query($listAll);
                      generateComplaintTable($result, "All Complaints");
                  }

                  // Approved Complaints
                  if (isset($_GET['approvedall'])) {
                      $approvedAll = "SELECT * FROM complaint WHERE sid='$userMail' AND status='approved' ORDER BY date DESC";
                      $result = $conn->query($approvedAll);
                      generateComplaintTable($result, "Approved Complaints");
                  }

                  // Pending Complaints
                  if (isset($_GET['pendingall'])) {
                      $pendingAll = "SELECT * FROM complaint WHERE sid='$userMail' AND status='pending' ORDER BY date DESC";
                      $result = $conn->query($pendingAll);
                      generateComplaintTable($result, "Pending Complaints");
                  }
                  ?>
              </main>
          </div>
      </div>

      <!-- Create Complaint Modal -->
      <div class="modal fade" id="createComplaintModal" tabindex="-1" role="dialog" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="createComplaintModalLabel">Create New Complaint</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="../createcomplaint.php" method="POST">
                          <div class="form-group">
                              <label for="Cby">Name:</label>
                              <input type="text" class="form-control" id="Cby" name="Cby" required>
                          </div>
                          <div class="form-group">
                              <label for="email">Email:</label>
                              <input type="email" class="form-control" id="email" name="email" required>
                          </div>
                          <div class="form-group">
                              <label for="type">Complaint Type:</label>
                              <select class="form-control" id="type" name="type" required>
                                  <option value="Hostel">Hostel</option>
                                  <option value="Bullying">Bullying</option>
                                  <option value="Harrassment">Harassment</option>
                                  <option value="Academics">Academics</option>
                                  <option value="Others">Others</option>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="description">Complaint Description:</label>
                              <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary">Submit Complaint</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      <!-- Change Password Modal -->
      <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="../changepassnew.php" method="post">
                          <div class="form-group">
                              <label for="currentPassword">Current Password:</label>
                              <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                          </div>
                          <div class="form-group">
                              <label for="newPassword">New Password:</label>
                              <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                          </div>
                          <div class="form-group">
                              <label for="confirmPassword">Confirm New Password:</label>
                              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                          </div>
                          <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade" id="complaintDetailsModal" tabindex="-1" role="dialog" aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
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

      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script>
          $(document).ready(function() {
              $('.view-complaint').click(function() {
                  var cid = $(this).data('cid');
                  $.ajax({
                      url: 'get_student_complaint_details.php',
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
          });
      </script>
  </body>
  </html>
