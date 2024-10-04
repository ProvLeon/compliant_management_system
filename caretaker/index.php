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
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
        while ($row = $result->fetch_assoc()) {
            $shortDescription = mb_strlen($row['description']) > 50 ? mb_substr($row['description'], 0, 47) . '...' : $row['description'];
            echo '<tr>
                    <td>' . $row['cid'] . '</td>
                    <td>' . htmlspecialchars($shortDescription) . '</td>
                    <td>' . $row['type'] . '</td>
                    <td>' . $row['Cby'] . ' (ID: ' . $row['sid'] . ')</td>
                    <td><span class="status-badge status-' . strtolower($row['status']) . '">' . $row['status'] . '</span></td>
                    <td>' . date('Y-m-d', strtotime($row['date'])) . '</td>
                    <td>
                        <button class="btn btn-sm btn-info btn-action view-complaint" data-cid="' . $row['cid'] . '">View</button>
                        <button class="btn btn-sm btn-primary btn-action reply-complaint" data-email="' . $row['SEmail'] . '">Reply</button>
                    </td>
                  </tr>';
        }
        echo '</tbody></table></div></div></div>';
    } else {
        echo '<div class="alert alert-info">No complaints found.</div>';
    }
}

// Function to generate feedback table
function generateFeedbackTable($result) {
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
        while ($row = $result->fetch_assoc()) {
            $shortDescription = mb_strlen($row['description']) > 50 ? mb_substr($row['description'], 0, 47) . '...' : $row['description'];
            echo '<tr>
                    <td>' . $row['fid'] . '</td>
                    <td>
                        <div>' . $row['name'] . '</div>
                        <small class="text-muted">' . $row['email'] . '</small>
                    </td>
                    <td>' . htmlspecialchars($shortDescription) . '</td>
                    <td>
                        <button class="btn btn-sm btn-info view-feedback" data-fid="' . $row['fid'] . '">View</button>
                    </td>
                  </tr>';
        }
        echo '</tbody></table></div></div></div>';
    } else {
        echo '<div class="alert alert-info">No feedbacks found.</div>';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Caretaker Dashboard - Complaint Management System</title>
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
        /* .status-pending { background-color: #ffc107; color: #212529; }
        .status-approved { background-color: #28a745; color: #fff; }
        .status-discard { background-color: #dc3545; color: #fff; } */
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        /* .modal-body {
            padding: 20px;
        } */
        .feedback-card {
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    margin: 20px auto;
                    max-width: 700px;
                    padding: 30px;
                    font-family: Arial, sans-serif;
                }
                .feedback-header {
                    border-bottom: 2px solid #f0f0f0;
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                }
                .feedback-title {
                    color: #333;
                    font-size: 24px;
                    margin: 0 0 10px;
                }
                .feedback-content {
                    margin-bottom: 25px;
                }
                .feedback-section {
                    margin-bottom: 25px;
                }
                .feedback-section h3 {
                    color: #444;
                    font-size: 18px;
                    margin-bottom: 15px;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #eee;
                }
                .feedback-section p {
                    margin: 10px 0;
                    line-height: 1.6;
                }
                .feedback-description {
                    background-color: #f9f9f9;
                    border-left: 4px solid #1a73e8;
                    padding: 15px;
                    margin-top: 10px;
                    border-radius: 4px;
                    font-style: italic;
                }


                .complaint-card {
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        margin: 20px auto;
                        max-width: 700px;
                        padding: 30px;
                        font-family: Arial, sans-serif;
                    }
                    .complaint-header {
                        border-bottom: 2px solid #f0f0f0;
                        margin-bottom: 20px;
                        padding-bottom: 15px;
                    }
                    .complaint-title {
                        color: #333;
                        font-size: 24px;
                        margin: 0 0 10px;
                    }
                    .complaint-date {
                        color: #777;
                        font-size: 14px;
                    }
                    .complaint-content {
                        margin-bottom: 25px;
                    }
                    .complaint-section {
                        margin-bottom: 25px;
                    }
                    .complaint-section h3 {
                        color: #444;
                        font-size: 18px;
                        margin-bottom: 15px;
                        padding-bottom: 8px;
                        border-bottom: 1px solid #eee;
                    }
                    .complaint-section p {
                        margin: 10px 0;
                        line-height: 1.6;
                    }
                    .complaint-description {
                        background-color: #f9f9f9;
                        border-left: 4px solid #1a73e8;
                        padding: 15px;
                        margin-top: 10px;
                        border-radius: 4px;
                        font-style: italic;
                    }
                    .complaint-footer {
                        border-top: 2px solid #f0f0f0;
                        padding-top: 20px;
                        display: flex;
                        justify-content: flex-end;
                        gap: 10px;
                    }
                    .status-badge {
                        display: inline-block;
                        padding: 5px 10px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: bold;
                        text-transform: uppercase;
                    }
                    .status-pending { background-color: #fef3cd; color: #856404; }
                    .status-approved { background-color: #d4edda; color: #155724; }
                    .status-discard { background-color: #f8d7da; color: #721c24; }
            </style>
</head>
<body>
    <nav class="navbar navbar-dark sticky-top flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Caretaker Dashboard</a>
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
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#searchModal">
                                <i class="fas fa-search"></i> Search Complaints
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
                    // List All Complaints
                    if (isset($_GET['listall'])) {
                        $listAll = "SELECT * FROM `complaint` WHERE type=? ORDER BY date DESC";
                        $stmt = $conn->prepare($listAll);
                        $stmt->bind_param("s", $_SESSION['ajay']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        generateComplaintTable($result, "All Complaints");
                    }

                    // Approved Complaints
                    if (isset($_GET['approvedall'])) {
                        $approvedAll = "SELECT * FROM `complaint` WHERE type=? AND status='approved' ORDER BY date DESC";
                        $stmt = $conn->prepare($approvedAll);
                        $stmt->bind_param("s", $_SESSION['ajay']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        generateComplaintTable($result, "Approved Complaints");
                    }

                    // Pending Complaints
                    if (isset($_GET['pendingall'])) {
                        $pendingAll = "SELECT * FROM `complaint` WHERE type=? AND status='pending' ORDER BY date DESC";
                        $stmt = $conn->prepare($pendingAll);
                        $stmt->bind_param("s", $_SESSION['ajay']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        generateComplaintTable($result, "Pending Complaints");
                    }

                    // Feedbacks
                    if (isset($_GET['feedbackall'])) {
                        $feedbackAll = "SELECT * FROM `feedback` ORDER BY fid DESC";
                        $result = $conn->query($feedbackAll);
                        generateFeedbackTable($result);
                    }
                    ?>
                </main>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search Complaints</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../caretaker_search.php" method="post">
                        <div class="form-group">
                            <label for="Cby">Student Name</label>
                            <input type="text" class="form-control" id="Cby" name="Cby">
                        </div>
                        <div class="form-group">
                            <label for="cid">Complaint ID</label>
                            <input type="text" class="form-control" id="cid" name="cid">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Search</button>
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
                    <form action="../caretaker_change_password.php" method="post">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Details Modal -->
    <div class="modal fade" id="complaintDetailsModal" tabindex="-1" role="dialog" aria-labelledby="complaintDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
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
                            <textarea class="form-control" id="replyMessage" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="feedbackDetailsModal" tabindex="-1" role="dialog" aria-labelledby="feedbackDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackDetailsModalLabel">Feedback Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="feedbackDetailsContent">
                        <!-- Feedback details will be loaded here -->
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
                        url: 'get_complaint_details.php',
                        type: 'GET',
                        data: { cid: cid },
                        success: function(response) {
                            $('#complaintDetailsContent').html(response);
                            $('#complaintDetailsModal').modal('show');
                        },
                        error: function() {
                            alert('Error fetching complaint details.');
                        }
                    });
                });

              $('.view-feedback').click(function() {
                    var fid = $(this).data('fid');
                    $.ajax({
                        url: 'get_feedback_details.php',
                        type: 'GET',
                        data: { fid: fid },
                        success: function(response) {
                            $('#feedbackDetailsContent').html(response);
                            $('#feedbackDetailsModal').modal('show');
                        },
                        error: function() {
                            alert('Error fetching feedback details.');
                        }
                    });
                });

                $('.reply-complaint').click(function() {
                    var email = $(this).data('email');
                    var cid = $(this).closest('tr').find('td:first').text();
                    $('#replyEmail').val(email);
                    $('#replyCid').val(cid);
                    $('#replyModal').modal('show');
                });

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
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred while sending the reply.');
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
                                location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred while updating the status.');
                        }
                    });
                });
            });
        </script>
    </body>
    </html>
