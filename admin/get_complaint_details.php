<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['cid'])) {
    $conn = Connect();
    $stmt = $conn->prepare("SELECT c.*, s.name as student_name FROM complaint c LEFT JOIN student s ON c.sid = s.rollno WHERE c.cid = ?");
    $stmt->bind_param("i", $_GET['cid']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($complaint = $result->fetch_assoc()) {
        echo '<div class="complaint-card">';

        // Header
        echo '<div class="complaint-header">';
        echo '<h2 class="complaint-title">Complaint #'.htmlspecialchars($complaint['cid']).'</h2>';
        echo '<span class="complaint-date">Submitted on '.date('F j, Y', strtotime($complaint['date'])).' at '.date('g:i A', strtotime($complaint['date'])).'</span>';
        echo '</div>';

        // Main content
        echo '<div class="complaint-content">';

        // Student Information
        echo '<div class="complaint-section">';
        echo '<h3>Student Information</h3>';
        echo '<p><strong>Name:</strong> '.htmlspecialchars($complaint['student_name']).'</p>';
        echo '<p><strong>Student ID:</strong> '.htmlspecialchars($complaint['sid']).'</p>';
        echo '<p><strong>Email:</strong> '.htmlspecialchars($complaint['SEmail']).'</p>';
        echo '</div>';

        // Complaint Details
        echo '<div class="complaint-section">';
        echo '<h3>Complaint Details</h3>';
        echo '<p><strong>Type:</strong> '.htmlspecialchars($complaint['type']).'</p>';
        echo '<p><strong>Status:</strong> <span class="status-badge status-'.strtolower($complaint['status']).'">'.ucfirst($complaint['status']).'</span></p>';
        echo '<p><strong>Complaint Description:</strong></p>';
        echo '<div class="complaint-description">'.nl2br(htmlspecialchars($complaint['description'])).'</div>';
        echo '</div>';

        echo '</div>'; // End of complaint-content

        // Footer with actions
        echo '<div class="complaint-footer">';
        echo '<button class="btn btn-primary reply-btn" data-toggle="modal" data-target="#replyModal" data-email="'.htmlspecialchars($complaint['SEmail']).'" data-cid="'.htmlspecialchars($complaint['cid']).'">Reply</button>';
        echo '<button class="btn btn-danger delete-btn" data-cid="'.htmlspecialchars($complaint['cid']).'">Delete</button>';
        echo '</div>';

        echo '</div>'; // End of complaint-card

        // Reply Modal
        echo '<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
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
                            <input type="hidden" id="replyEmail" name="email" value="'.htmlspecialchars($complaint['SEmail']).'">
                            <input type="hidden" id="replyCid" name="cid" value="'.htmlspecialchars($complaint['cid']).'">
                            <div class="form-group">
                                <label for="replyMessage">Message:</label>
                                <textarea class="form-control" id="replyMessage" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
        // CSS for styling
        echo '<style>
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
            .btn {
                padding: 10px 20px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .btn-primary {
                background-color: #1a73e8;
                color: white;
                border: none;
            }
            .btn-danger {
                background-color: #ea4335;
                color: white;
                border: none;
            }
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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

            .modal-content {
                border-radius: 8px;
            }
            .modal-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
            }
            .modal-title {
                color: #333;
            }
            #replyMessage {
                resize: vertical;
            }
        </style>';

        // JavaScript for handling the reply
        echo '<script>
        $(document).ready(function() {
            $("#replyForm").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "send_reply.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert("Reply sent successfully!");
                            $("#replyModal").modal("hide");
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while sending the reply.");
                    }
                });
            });
        });
        </script>';

    } else {
        echo '<div class="alert alert-warning">Complaint not found</div>';
    }
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-danger">Invalid request</div>';
}
?>
